use streamflow_database;

-- SELECT permitido em logs e histórico
select * from play_logs limit 5;
select * from watching_history limit 5;

-- SELECT em tabelas de domínio (deve falhar)
select * from customers limit 3;
select * from profiles limit 3;

-- INSERT proibido (deve falhar)
insert into play_logs (ip_address, play_timestamp, id_profile, id_device_type, id_video)
values ('10.0.0.3', current_timestamp(), 1, 1, 1);

insert into watching_history (started_at, time_watched_seconds, is_completed, is_paused, id_profile, id_video)
values (current_timestamp(), 300, false, true, 1, 1);

-- UPDATE proibido (deve falhar)
update play_logs
set ip_address = '10.0.0.4'
where id = 1;

-- UPDATE proibido (deve falhar)
update watching_history
set time_watched_seconds = 9999
where id = 1;

-- DELETE proibido (deve falhar)
delete from play_logs where id = 1;
delete from watching_history where id = 1;

-- DDL proibido (deve falhar)
create table teste_auditor_permissao (id int);