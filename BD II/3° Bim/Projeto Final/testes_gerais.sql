USE streamflow_database;
SET NAMES utf8mb4;

-- F1.1 DEVE: com saldo debita e retorna o novo saldo
UPDATE account_balance SET balance = 100.00 WHERE id_customer = 1;
CALL realizar_cobranca_mensal(1, 29.90, @novo_saldo);
SELECT @novo_saldo AS novo_saldo;

-- F1.2 DEVE: insere o play e devolve o id
CALL registrar_reproducao(1, 1, '192.168.0.10', 1, @rid);
SELECT @rid AS nova_reproducao;
SELECT ip_address FROM play_logs WHERE id = @rid;

-- F1.3 DEVE: gera 1 linha por estudio na competencia 2026-06-01
CALL gerar_faturamento_mensal('2026-06-20');
SELECT s.name, b.reference_date, b.minutes_consumed 
FROM studio_billing b 
JOIN studios s ON s.id = b.studio_id 
ORDER BY s.id;

-- F1.4 DEVE: minutos concluidos de jun/2026 por estudio
SELECT minutos_assistidos_por_produtora(1, '2026-06-15') AS min_alpha;
SELECT minutos_assistidos_por_produtora(2, '2026-06-15') AS min_beta;

-- F1.5 DEVE: idade sem mostrar a data
SELECT calcular_idade('1990-01-15') AS idade_joao;
SELECT calcular_idade(NULL) AS idade_null;

-- F2.3 DEVE: update no perfil grava OLD/NEW em audit_log
UPDATE profiles SET display_name = 'JOAO ATUALIZADO' WHERE id = 1;
SELECT table_name, operation, user_name 
FROM audit_log 
WHERE table_name = 'profiles' 
ORDER BY id DESC 
LIMIT 1;

SELECT * FROM audit_log;

-- F2.4 DEVE: insert sai com nome padronizado
INSERT INTO customers (full_name, email, password_hash, cpf, state_uf, date_of_birth) 
VALUES ('  ana beatriz  ', 'ANA@EXAMPLE.COM', 'hashsenha000', '222.333.444-55', 'SP', '1998-04-01');
SELECT full_name, email FROM customers WHERE cpf = '222.333.444-55';

-- F2.4 DEVE: update preenche updated_at
UPDATE customers SET state_uf = 'RJ' WHERE cpf = '222.333.444-55';
SELECT updated_at IS NOT NULL AS tem_timestamp FROM customers WHERE cpf = '222.333.444-55';
DELETE FROM customers WHERE cpf = '222.333.444-55';

-- F2.5 DEVE: cada insert soma +1 no contador
SELECT total_plays FROM reproduction_summary WHERE id = 1;
CALL registrar_reproducao(2, 3, '10.1.1.1', 2, @rid2);
SELECT total_plays FROM reproduction_summary WHERE id = 1;

-- F2.6 DEVE: completar ate 5 perfis funciona
INSERT INTO profiles (display_name, id_customer) 
VALUES ('Extra 1', 1), ('Extra 2', 1), ('Extra 3', 1);

-- F2.6 DEVE: outro assinante ainda pode criar
INSERT INTO profiles (display_name, id_customer) 
VALUES ('Extra Pedro', 3);
DELETE FROM profiles WHERE display_name LIKE 'Extra%';

-- F LGPD DEVE: idade e totais sem cpf/email/nascimento
SELECT customer_id, state_uf, age FROM v_marketing_engagement LIMIT 3;

UPDATE account_balance SET balance = 10.00 WHERE id_customer = 1;
CALL realizar_cobranca_mensal(1, 29.90, @novo_saldo);
SELECT balance FROM account_balance WHERE id_customer = 1;

-- 

-- F1.1 NAO DEVE: valor negativo falha
CALL realizar_cobranca_mensal(1, -5.00, @x);

-- F1.2 NAO DEVE: perfil inexistente falha sem inserir
CALL registrar_reproducao(99999, 1, '10.0.0.9', 1, @rid);

-- F2.1 NAO DEVE: saldo negativo via update direto falha
UPDATE account_balance SET balance = -1 WHERE id_customer = 2;

-- F2.2 NAO DEVE: log nao pode ser alterado nem apagado
UPDATE play_logs SET ip_address = '9.9.9.9' WHERE id = 1;
DELETE FROM play_logs WHERE id = 1;

-- F2.6 NAO DEVE: o 6o perfil falha
INSERT INTO profiles (display_name, id_customer) 
VALUES ('Extra 6', 1);