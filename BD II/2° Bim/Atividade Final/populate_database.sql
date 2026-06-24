use streamflow_database;

-- valores padrão
INSERT INTO device_types (type_name, description) VALUES
('SmartTV', 'Televisão inteligente com app StreamFlow'),
('Smartphone', 'Celular ou tablet com app StreamFlow'),
('Web', 'Navegador web (desktop/mobile)'),
('Tablet', 'Tablet com app StreamFlow'),
('GameConsole', 'Console de jogos com app StreamFlow');

insert into categories (name, description, category_type) values
('Ação', 'Filmes de ação e aventura', 'FILM'),
('Drama', 'Filmes dramáticos', 'FILM'),
('Comédia', 'Filmes de comédia', 'FILM'),
('Série Teen', 'Séries para adolescentes', 'SERIES'),
('Documentário Geral', 'Documentários variados', 'DOCUMENTARY'),
('Infantil', 'Conteúdo para crianças', 'KIDS'),
('Show ao Vivo', 'Transmissões ao vivo', 'LIVE');

insert into customers (full_name, email, password_hash, cpf, state_uf, date_of_birth) values
('João Silva', 'joao.silva@example.com', 'hashsenha123', '123.456.789-00', 'SP', '1990-01-15'),
('Maria Souza', 'maria.souza@example.com', 'hashsenha456', '987.654.321-00', 'RJ', '1985-06-20'),
('Pedro Santos', 'pedro.santos@example.com', 'hashsenha789', '111.222.333-44', 'MG', '2000-03-10');

insert into profiles (display_name, avatar_color, is_active, id_customer) values
('João', '#3498DB', true, 1),
('Mãe João', '#E74C3C', true, 1),
('Maria', '#9B59B6', true, 2),
('Filho Maria', '#1ABC9C', true, 2),
('Pedro', '#F1C40F', true, 3);

insert into studios (name, contact_email, tax_id) values
('Estúdio Alpha', 'contato@alpha.com', '12.345.678/0001-00'),
('Estúdio Beta', 'contato@beta.com', '98.765.432/0001-99');

insert into videos (title, description, duration_seconds, release_date, id_studio, id_category) values
('Filme Ação 1', 'Filme de ação com muita aventura', 7200, '2024-01-10', 1, 1),
('Filme Drama 1', 'Filme dramático e emocionante', 5400, '2023-11-05', 1, 2),
('Série Teen Ep1', 'Primeiro episódio de série teen', 3600, '2025-02-01', 2, 4),
('Documentário Natureza', 'Documentário sobre natureza', 4200, '2022-08-15', 2, 5),
('Desenho Infantil 1', 'Conteúdo infantil educativo', 1800, '2024-05-20', 1, 6);

insert into subscription_plans (subscription_type, start_date, end_date, is_active, id_customer) values
('MONTHLY', '2026-01-01', null, true, 1),
('YEARLY', '2026-01-01', '2026-12-31', true, 2),
('MONTHLY', '2026-02-01', null, true, 3);

insert into payments (payment_date, amount, payment_method, status, id_customer, id_subscription_plan) values
('2026-01-05 10:00:00', 29.90, 'CREDIT_CARD', 'PAID', 1, 1),
('2026-01-05 11:00:00', 299.90, 'PIX', 'PAID', 2, 2),
('2026-02-05 09:30:00', 29.90, 'BANK_SLIP', 'PAID', 3, 3);

insert into account_balance (balance, last_transaction_at, id_customer) values
(0.00, current_timestamp(), 1),
(0.00, current_timestamp(), 2),
(0.00, current_timestamp(), 3);

insert into watching_history (started_at, stopped_at, time_watched_seconds, is_completed, is_paused, id_profile, id_video) values
('2026-06-01 00:00:00', '2026-06-30 12:00:00', 10800000, true, false, 1, 1),
('2026-06-02 00:00:00', '2026-06-30 06:00:00', 9000000, true, false, 2, 2),
('2026-06-10 08:00:00', '2026-06-18 16:00:00', 720000, false, false, 3, 5),
('2026-06-20 20:00:00', null, 1800, false, true, 1, 1),
('2026-06-21 21:00:00', null, 2400, false, true, 1, 2),
('2026-06-22 22:00:00', null, 1200, false, true, 1, 3);

-- Para testar o index que evita N+1
/*
insert into watching_history (started_at, stopped_at, time_watched_seconds, is_completed, is_paused, id_profile, id_video)
select
    started_at + interval t.n hour,
    stopped_at + interval t.n hour,
    time_watched_seconds,
    is_completed,
    is_paused,
    case when id_profile = 1 then 2 else id_profile end as id_profile,
    id_video
from watching_history
join (
    select 0 as n union all select 1 union all select 2 union all select 3 union all select 4
) t
where id_profile in (2,3);

insert into watching_history (started_at, stopped_at, time_watched_seconds, is_completed, is_paused, id_profile, id_video)
select
    started_at + interval 10 day,
    stopped_at + interval 10 day,
    time_watched_seconds,
    is_completed,
    is_paused,
    id_profile,
    id_video
from watching_history
where id_profile in (2,3);

insert into watching_history (started_at, stopped_at, time_watched_seconds, is_completed, is_paused, id_profile, id_video)
select
    '2026-06-10 00:00:00' + interval t.n day + interval t2.m hour as started_at,
    null as stopped_at,
    1800 + (t2.m * 60) as time_watched_seconds,
    case when t2.m % 2 = 0 then false else true end as is_completed,
    true as is_paused,
    1 as id_profile,
    1 as id_video
from (
    select 0 as n union all select 1 union all select 2 union all select 3 union all select 4
) t
join (
    select 0 as m union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9
) t2;
*/