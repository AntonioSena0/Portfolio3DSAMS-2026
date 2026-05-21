CREATE DATABASE hospital_db;
USE hospital_db;

-- Tabela com dados públicos de agendamento
CREATE TABLE agendamentos (
    id_agenda INT AUTO_INCREMENT PRIMARY KEY,
    paciente_nome VARCHAR(100),
    data_consulta DATE,
    hora_consulta TIME,
    medico_responsavel VARCHAR(100)
);

-- Tabela com dados sensíveis de prontuário
CREATE TABLE prontuarios (
    id_prontuario INT AUTO_INCREMENT PRIMARY KEY,
    paciente_nome VARCHAR(100),
    diagnostico TEXT,
    medicacao_prescrita TEXT,
    historico_familiar TEXT
);

-- Inserindo dados de teste
INSERT INTO agendamentos (paciente_nome, data_consulta, hora_consulta, medico_responsavel) VALUES
('Carlos Silva', '2026-05-10', '08:00', 'Dr. Arnaldo'),
('Maria Souza', '2026-05-10', '09:00', 'Dra. Beatriz');

INSERT INTO prontuarios (paciente_nome, diagnostico, medicacao_prescrita) VALUES
('Carlos Silva', 'Hipertensão Estágio 1', 'Enalapril 20mg'),
('Maria Souza', 'Enxaqueca Crônica', 'Sumatriptana 50mg');

-- Criando usuários e separando permissões
CREATE USER 'recepcao_central'@'%' IDENTIFIED BY 'SenhaRec#123';
CREATE USER 'medico_geral'@'%' IDENTIFIED BY 'Med@Secure!2026';

GRANT SELECT ON hospital_db.agendamentos TO 'recepcao_central'@'%';
GRANT INSERT ON hospital_db.agendamentos TO 'recepcao_central'@'%';

GRANT ALL PRIVILEGES ON hospital_db.agendamentos TO 'medico_geral'@'%';
GRANT ALL PRIVILEGES ON hospital_db.prontuarios TO 'medico_geral'@'%';

REVOKE DELETE ON hospital_db.prontuarios FROM 'medico_geral'@'%';

-- Exibição de Usuários do sistema (usuário, host e senha hash)
SELECT user, host, authentication_string FROM mysql.user;

-- Permissões
SHOW GRANTS FOR 'recepcao_central'@'%';
SHOW GRANTS FOR 'medico_geral'@'%';