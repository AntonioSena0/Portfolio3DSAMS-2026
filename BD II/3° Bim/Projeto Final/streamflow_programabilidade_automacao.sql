USE streamflow_database;
SET NAMES utf8mb4;

-- 0 DROP de tudo para poder rodar este arquivo de novo sem erro
DROP TRIGGER IF EXISTS trg_assinantes_saldo_nao_negativo_bi;
DROP TRIGGER IF EXISTS trg_assinantes_saldo_nao_negativo_bu;
DROP TRIGGER IF EXISTS trg_reproducoes_imutabilidade_bu;
DROP TRIGGER IF EXISTS trg_reproducoes_imutabilidade_bd;
DROP TRIGGER IF EXISTS trg_perfis_auditoria_au;
DROP TRIGGER IF EXISTS trg_assinantes_timestamp_saneamento_bi;
DROP TRIGGER IF EXISTS trg_assinantes_timestamp_saneamento_bu;
DROP TRIGGER IF EXISTS trg_resumo_reproducao_row_ai;
DROP TRIGGER IF EXISTS limit_profiles;
DROP PROCEDURE IF EXISTS realizar_cobranca_mensal;
DROP PROCEDURE IF EXISTS registrar_reproducao;
DROP PROCEDURE IF EXISTS gerar_faturamento_mensal;
DROP FUNCTION IF EXISTS minutos_assistidos_por_produtora;
DROP FUNCTION IF EXISTS calcular_idade;

-- 0 Garante a linha 1 do resumo e acerta o contador com os plays que ja existem
INSERT IGNORE INTO reproduction_summary (id, total_plays) VALUES (1, 0);
UPDATE reproduction_summary SET total_plays = (SELECT COUNT(*) FROM play_logs) WHERE id = 1;

-- 1.1 Cobra a mensalidade do saldo e devolve quanto sobrou (saldo nunca fica negativo)
DELIMITER $$
CREATE PROCEDURE realizar_cobranca_mensal(
    IN p_assinante_id BIGINT,
    IN p_valor_mensalidade DECIMAL(10,2),
    OUT p_novo_saldo DECIMAL(10,2)
)
BEGIN
    DECLARE v_saldo DECIMAL(10,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    IF p_valor_mensalidade IS NULL OR p_valor_mensalidade <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Valor da mensalidade deve ser maior que zero';
    END IF;

    START TRANSACTION;

    SELECT balance INTO v_saldo
      FROM account_balance
     WHERE id_customer = p_assinante_id
     FOR UPDATE;

    IF v_saldo IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Assinante sem conta de saldo (account_balance nao encontrada)';
    END IF;

    IF v_saldo < p_valor_mensalidade THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Saldo insuficiente para cobranca';
    END IF;

    UPDATE account_balance
       SET balance = balance - p_valor_mensalidade,
           last_transaction_at = NOW()
     WHERE id_customer = p_assinante_id;

    SET p_novo_saldo = v_saldo - p_valor_mensalidade;

    COMMIT;
END$$
DELIMITER ;

-- 3 Cobranca: valor ruim, sem conta ou sem saldo -> 45000; resto -> ROLLBACK

-- 1.2 Registra um play com a hora do servidor e devolve o id que foi criado
DELIMITER $$
CREATE PROCEDURE registrar_reproducao(
    IN p_perfil_id BIGINT,
    IN p_video_id BIGINT,
    IN p_ip_conexao VARCHAR(45),
    IN p_device_type_id INT,
    OUT p_reproducao_id BIGINT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Falha ao registrar reproducao: verifique perfil, video, dispositivo e IP';
    END;

    IF p_ip_conexao IS NULL OR TRIM(p_ip_conexao) = '' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IP de conexao e obrigatorio';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM profiles WHERE id = p_perfil_id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Perfil nao encontrado';
    END IF;
    IF NOT EXISTS (SELECT 1 FROM videos WHERE id = p_video_id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Video nao encontrado';
    END IF;
    IF NOT EXISTS (SELECT 1 FROM device_types WHERE id = p_device_type_id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Tipo de dispositivo invalido';
    END IF;

    START TRANSACTION;

    INSERT INTO play_logs (ip_address, play_timestamp, id_profile, id_device_type, id_video)
    VALUES (TRIM(p_ip_conexao), NOW(), p_perfil_id, p_device_type_id, p_video_id);

    SET p_reproducao_id = LAST_INSERT_ID();

    COMMIT;
END$$
DELIMITER ;

-- 3 Registro: dado errado -> 45000 explicando; erro de banco -> ROLLBACK

-- 1.3 Fecha o faturamento do mes passando estudio por estudio com cursor
DELIMITER $$
CREATE PROCEDURE gerar_faturamento_mensal(IN p_competencia DATE)
BEGIN
    DECLARE v_done BOOLEAN DEFAULT FALSE;
    DECLARE v_id_studio BIGINT;
    DECLARE v_ini DATE;
    DECLARE v_min INT;

    DECLARE c_studios CURSOR FOR SELECT id FROM studios ORDER BY id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = TRUE;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    IF p_competencia IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Competencia e obrigatoria (DATE)';
    END IF;

    SET v_ini = DATE_FORMAT(p_competencia, '%Y-%m-01');

    START TRANSACTION;

    OPEN c_studios;
    loop_studios: LOOP
        FETCH c_studios INTO v_id_studio;
        IF v_done THEN
            LEAVE loop_studios;
        END IF;

        SET v_min = minutos_assistidos_por_produtora(v_id_studio, v_ini);

        INSERT INTO studio_billing (studio_id, reference_date, minutes_consumed)
        VALUES (v_id_studio, v_ini, v_min)
        ON DUPLICATE KEY UPDATE minutes_consumed = VALUES(minutes_consumed);
    END LOOP;
    CLOSE c_studios;

    COMMIT;
END$$
DELIMITER ;

-- 3 Faturamento: sem competencia -> 45000; erro no meio do lote -> ROLLBACK

-- 1.4 Soma os minutos concluidos do estudio no mes (nao e DETERMINISTIC porque le tabelas)
DELIMITER $$
CREATE FUNCTION minutos_assistidos_por_produtora(p_id_produtora BIGINT, p_competencia DATE)
RETURNS INT
NOT DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_ini DATE;
    DECLARE v_fim DATE;
    DECLARE v_min INT DEFAULT 0;

    IF p_id_produtora IS NULL OR p_competencia IS NULL THEN
        RETURN 0;
    END IF;

    SET v_ini = DATE_FORMAT(p_competencia, '%Y-%m-01');
    SET v_fim = v_ini + INTERVAL 1 MONTH;

    SELECT COALESCE(FLOOR(SUM(wh.time_watched_seconds) / 60), 0)
      INTO v_min
      FROM watching_history wh
      JOIN videos v ON wh.id_video = v.id
     WHERE v.id_studio = p_id_produtora
       AND wh.is_completed = TRUE
       AND wh.started_at >= v_ini
       AND wh.started_at < v_fim;

    RETURN v_min;
END$$
DELIMITER ;

-- 1.5 Calcula a idade sem mostrar o nascimento (usa CURDATE, por isso nao e DETERMINISTIC)
DELIMITER $$
CREATE FUNCTION calcular_idade(p_data_nascimento DATE)
RETURNS INT
NOT DETERMINISTIC
NO SQL
BEGIN
    IF p_data_nascimento IS NULL THEN
        RETURN NULL;
    END IF;
    RETURN TIMESTAMPDIFF(YEAR, p_data_nascimento, CURDATE())
         - (DATE_FORMAT(CURDATE(), '%m-%d') < DATE_FORMAT(p_data_nascimento, '%m-%d'));
END$$
DELIMITER ;

-- 1.5 View LGPD refeita usando a funcao de idade
CREATE OR REPLACE VIEW v_marketing_engagement AS
SELECT
    c.id                                            AS customer_id,
    c.state_uf,
    calcular_idade(c.date_of_birth)                 AS age,
    COUNT(DISTINCT p.id)                            AS total_profiles,
    COALESCE(SUM(wh.time_watched_seconds) / 3600, 0) AS total_hours_watched
FROM customers c
LEFT JOIN profiles p          ON c.id = p.id_customer
LEFT JOIN watching_history wh ON p.id = wh.id_profile
GROUP BY c.id, c.state_uf, c.date_of_birth;

-- 2.1 Nao deixa o saldo ficar negativo no INSERT (vale mesmo sem passar pela procedure)
DELIMITER $$
CREATE TRIGGER trg_assinantes_saldo_nao_negativo_bi
BEFORE INSERT ON account_balance
FOR EACH ROW
BEGIN
    IF NEW.balance < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Saldo de creditos nao pode ser negativo (RN01)';
    END IF;
END$$
DELIMITER ;

-- 2.1 Nao deixa o saldo ficar negativo no UPDATE (o CHECK barra, mas com erro generico)
DELIMITER $$
CREATE TRIGGER trg_assinantes_saldo_nao_negativo_bu
BEFORE UPDATE ON account_balance
FOR EACH ROW
BEGIN
    IF NEW.balance < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Saldo de creditos nao pode ser negativo (RN01)';
    END IF;
END$$
DELIMITER ;

-- 2.2 Play nao pode ser alterado (historico e definitivo)
DELIMITER $$
CREATE TRIGGER trg_reproducoes_imutabilidade_bu
BEFORE UPDATE ON play_logs
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Reproducoes sao imutaveis: UPDATE bloqueado (RN03)';
END$$
DELIMITER ;

-- 2.2 Play nao pode ser apagado (historico e definitivo)
DELIMITER $$
CREATE TRIGGER trg_reproducoes_imutabilidade_bd
BEFORE DELETE ON play_logs
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Reproducoes sao imutaveis: DELETE bloqueado (RN03)';
END$$
DELIMITER ;

-- 2.3 Todo UPDATE em perfil vira uma linha em audit_log com o antes, o depois e quem fez
DELIMITER $$
CREATE TRIGGER trg_perfis_auditoria_au
AFTER UPDATE ON profiles
FOR EACH ROW
BEGIN
    INSERT INTO audit_log (table_name, operation, user_name, old_value, new_value, occurred_at)
    VALUES (
        'profiles',
        'UPDATE',
        CURRENT_USER(),
        JSON_OBJECT('id', OLD.id, 'display_name', OLD.display_name,
                    'avatar_color', OLD.avatar_color, 'is_active', OLD.is_active,
                    'id_customer', OLD.id_customer),
        JSON_OBJECT('id', NEW.id, 'display_name', NEW.display_name,
                    'avatar_color', NEW.avatar_color, 'is_active', NEW.is_active,
                    'id_customer', NEW.id_customer),
        NOW()
    );
END$$
DELIMITER ;

-- 2.4 Nome sempre em maiusculo no INSERT
DELIMITER $$
CREATE TRIGGER trg_assinantes_timestamp_saneamento_bi
BEFORE INSERT ON customers
FOR EACH ROW
BEGIN
    SET NEW.full_name = UPPER(TRIM(NEW.full_name));
    SET NEW.email = LOWER(TRIM(NEW.email));
END$$
DELIMITER ;

-- 2.4 No UPDATE repete a arrumacao e carimba a hora em updated_at
DELIMITER $$
CREATE TRIGGER trg_assinantes_timestamp_saneamento_bu
BEFORE UPDATE ON customers
FOR EACH ROW
BEGIN
    SET NEW.full_name = UPPER(TRIM(NEW.full_name));
    SET NEW.email = LOWER(TRIM(NEW.email));
    SET NEW.updated_at = NOW();
END$$
DELIMITER ;

-- 2.5 Cada play soma +1 no resumo (MySQL nao tem FOR EACH STATEMENT, so ROW)
DELIMITER $$
CREATE TRIGGER trg_resumo_reproducao_row_ai
AFTER INSERT ON play_logs
FOR EACH ROW
BEGIN
    UPDATE reproduction_summary SET total_plays = total_plays + 1 WHERE id = 1;
END$$
DELIMITER ;

-- 2.6 Cada assinante pode ter no maximo 5 perfis
DELIMITER $$
CREATE TRIGGER limit_profiles
BEFORE INSERT ON profiles
FOR EACH ROW
BEGIN
    DECLARE v_profile_count INT DEFAULT 0;
    SELECT COUNT(*) INTO v_profile_count FROM profiles WHERE id_customer = NEW.id_customer;
    IF v_profile_count >= 5 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Maximo de perfis atingidos';
    END IF;
END$$
DELIMITER ;