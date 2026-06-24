create user if not exists 'streamflow_app'@'localhost' identified by 'AppSecurePassword123!';
create user if not exists 'streamflow_auditor'@'localhost' identified by 'AuditorSecurePassword456!';
create user if not exists 'streamflow_marketing'@'localhost' identified by 'MarketingSecurePassword789!';

-- App
grant select, insert, update on streamflow_database.customers to 'streamflow_app'@'localhost';
grant select, insert, update on streamflow_database.profiles to 'streamflow_app'@'localhost';
grant select, insert, update on streamflow_database.studios to 'streamflow_app'@'localhost';
grant select, insert, update on streamflow_database.categories to 'streamflow_app'@'localhost';
grant select, insert, update on streamflow_database.videos to 'streamflow_app'@'localhost';
grant select on streamflow_database.device_types to 'streamflow_app'@'localhost';
grant select, insert, update on streamflow_database.watching_history to 'streamflow_app'@'localhost';
grant select, insert, update on streamflow_database.subscription_plans to 'streamflow_app'@'localhost';
grant select, insert, update on streamflow_database.payments to 'streamflow_app'@'localhost';
grant select, insert, update on streamflow_database.account_balance to 'streamflow_app'@'localhost';
grant insert on streamflow_database.play_logs to 'streamflow_app'@'localhost';
revoke delete on streamflow_database.play_logs from 'streamflow_app'@'localhost';
revoke update on streamflow_database.play_logs from 'streamflow_app'@'localhost';
revoke create, alter, drop on streamflow_database.* from 'streamflow_app'@'localhost';

-- Auditor
grant select on streamflow_database.play_logs to 'streamflow_auditor'@'localhost';
grant select on streamflow_database.watching_history to 'streamflow_auditor'@'localhost';
revoke insert, update, delete on streamflow_database.* from 'streamflow_auditor'@'localhost';
revoke create, alter, drop on streamflow_database.* from 'streamflow_auditor'@'localhost';

-- Marketing
grant select on streamflow_database.v_marketing_engagement to 'streamflow_marketing'@'localhost';
revoke select on streamflow_database.customers from 'streamflow_marketing'@'localhost';
revoke select on streamflow_database.profiles from 'streamflow_marketing'@'localhost';
revoke insert, update, delete on streamflow_database.* from 'streamflow_marketing'@'localhost';
revoke create, alter, drop on streamflow_database.* from 'streamflow_marketing'@'localhost';

-- Verificações
flush privileges;

show grants for 'streamflow_app'@'localhost';
show grants for 'streamflow_auditor'@'localhost';
show grants for 'streamflow_marketing'@'localhost';

-- Caso necessário
-- drop user if exists 'streamflow_app'@'localhost';
-- drop user if exists 'streamflow_auditor'@'localhost';
-- drop user if exists 'streamflow_marketing'@'localhost';