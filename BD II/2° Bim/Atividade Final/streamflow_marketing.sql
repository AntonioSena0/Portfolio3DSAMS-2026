use streamflow_database;

-- SELECT permitido na view de LGPD
select * from v_marketing_engagement limit 10;

-- SELECT proibido em dados sensíveis (deve falhar)
select * from customers limit 3;
select * from profiles limit 3;

-- INSERT proibido (deve falhar)
insert into customers (full_name, email, password_hash, cpf, state_uf, date_of_birth)
values ('Teste Marketing', 'teste.marketing@example.com', 'senha1234', '000.000.000-00', 'SP', '1995-01-01');

-- UPDATE proibido (deve falhar)
update customers
set full_name = 'Nome Alterado'
where id = 1;

-- DELETE proibido (deve falhar)
delete from customers where id = 1;

-- DDL proibido (deve falhar)
create table teste_marketing_permissao (id int);