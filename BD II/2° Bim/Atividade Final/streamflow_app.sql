use streamflow_database;

-- SELECT básico em tabelas de domínio
select * from customers limit 3;
select * from profiles limit 3;
select * from studios limit 3;
select * from categories limit 3;
select * from videos limit 3;
select * from device_types limit 3;

-- SELECT em histórico e financeiro
select * from watching_history limit 3;
select * from subscription_plans limit 3;
select * from payments limit 3;
select * from account_balance limit 3;
select * from play_logs limit 3;

-- INSERT permitido em watching_history
insert into watching_history (started_at, time_watched_seconds, is_completed, is_paused, id_profile, id_video)
values (current_timestamp(), 600, false, true, 1, 1);

-- UPDATE permitido em watching_history
update watching_history
set time_watched_seconds = 1200
where id_profile = 1
limit 1;

-- INSERT permitido em play_logs
insert into play_logs (ip_address, play_timestamp, id_profile, id_device_type, id_video)
values ('10.0.0.1', current_timestamp(), 1, 1, 1);

-- DELETE proibido em play_logs (deve falhar)
delete from play_logs
where ip_address = '10.0.0.1'
limit 1;

-- UPDATE proibido em play_logs (deve falhar)
update play_logs
set ip_address = '10.0.0.2'
where ip_address = '10.0.0.1'
limit 1;

-- DDL proibido (deve falhar)
create table teste_app_permissao (id int);