use hospital_db;

SHOW GRANTS;

-- PRIVILÉGIOS:
SELECT * FROM agendamentos;
INSERT INTO agendamentos VALUES(4, 'Rafael Carlos', '2026-05-10', '11:00:00', 'Dra. Alice');
SELECT * FROM prontuarios;
INSERT INTO prontuarios VALUES(3, 'Antonio Sena', 'Diabetes', 'Insulina 20mg', null);

-- NÃO TEM ACESSO:
DELETE FROM prontuarios WHERE id = 2;