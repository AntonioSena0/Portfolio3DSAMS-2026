use hospital_db;

SHOW GRANTS;

-- PRIVILÉGIOS:
SELECT * FROM agendamentos;
INSERT INTO agendamentos VALUES(3, 'Antonio Sena', '2026-05-10', '10:00:00', 'Dra. Vanessa');

-- NÃO TEM ACESSO:
SELECT * FROM prontuarios;
INSERT INTO prontuarios VALUES(3, 'Antonio Sena', 'Diabetes', 'Insulina 20mg', null);