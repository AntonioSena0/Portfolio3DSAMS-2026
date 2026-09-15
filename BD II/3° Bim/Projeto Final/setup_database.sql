create database if not exists streamflow_database;
use streamflow_database;

-- Tables
create table if not exists customers(
	id bigint auto_increment primary key,
	full_name varchar(150) not null,
	email varchar(100) unique not null,
	password_hash varchar(255) not null,
	cpf varchar(14) unique not null,
	state_uf char(2) not null,
	date_of_birth date not null,
	created_at timestamp default current_timestamp(),
	updated_at timestamp null
);

create table if not exists profiles(
	id bigint auto_increment primary key,
	display_name varchar(100) not null,
	avatar_color varchar(7) default '#3498DB',
	is_active boolean default true,
	id_customer bigint not null
);

create table if not exists studios(
	id bigint auto_increment primary key,
	name varchar(100) not null unique,
	contact_email varchar(100) unique,
	tax_id varchar(18),
	created_at timestamp default current_timestamp()
);

create table if not exists categories(

	id integer auto_increment primary key,
	name varchar(50) not null unique,
	description varchar(100),
	category_type ENUM('FILM', 'SERIES', 'DOCUMENTARY', 'KIDS', 'LIVE') not null

);

create table if not exists videos(
	id bigint auto_increment primary key,
	title varchar(150) not null,
	description text,
	duration_seconds integer not null,
	release_date date,
	is_available boolean default true,
	created_at timestamp default current_timestamp(),
	deleted_at timestamp null,
	id_studio bigint not null,
	id_category integer not null
);

create table if not exists device_types(
	id integer auto_increment primary key,
	type_name varchar(50) not null unique,
	description varchar(100),
	created_at timestamp default current_timestamp()
);

create table if not exists play_logs(
	id bigint auto_increment primary key,
	ip_address varchar(45) not null,
	play_timestamp timestamp not null default current_timestamp(),
	id_profile bigint not null,
	id_device_type integer not null,
	id_video bigint not null
);

create table if not exists watching_history(
	id bigint auto_increment primary key,
	started_at timestamp default current_timestamp(),
	stopped_at timestamp null,
	time_watched_seconds integer default 0,
	is_completed boolean default false,
	is_paused boolean default false,
	id_profile bigint not null,
	id_video bigint not null
);

create table if not exists subscription_plans(
	id integer auto_increment primary key,
	subscription_type ENUM('MONTHLY', 'YEARLY') not null,
	start_date date not null,
	end_date date null,
	is_active boolean default true,
	created_at timestamp default current_timestamp(),
	id_customer bigint not null
);

create table if not exists payments(
	id bigint auto_increment primary key,
	payment_date timestamp not null default current_timestamp(),
	amount decimal(10, 2) not null,
	payment_method ENUM('CREDIT_CARD', 'DEBIT_CARD', 'PIX', 'BANK_SLIP') not null,
	status ENUM('PENDING', 'PAID', 'FAILED', 'REFUNDED') not null default 'PENDING',
	id_customer bigint not null,
	id_subscription_plan integer not null
);

create table if not exists account_balance(
	id bigint auto_increment primary key,
	balance decimal(10, 2) not null default 0.00,
	last_transaction_at timestamp default current_timestamp(),
	id_customer bigint not null unique
);

create table if not exists studio_billing(
	id bigint auto_increment primary key,
	id_studio bigint not null,
	competencia date not null,
	minutos_consumidos integer not null default 0,
	criado_em timestamp default current_timestamp(),
	atualizado_em timestamp default current_timestamp() on update current_timestamp()
);

create table if not exists audit_log(
	id bigint auto_increment primary key,
	tabela varchar(64) not null,
	operacao varchar(10) not null,
	usuario varchar(128) not null,
	valor_antigo json null,
	valor_novo json null,
	data_hora timestamp not null default current_timestamp()
);

create table if not exists reproduction_summary(
	id integer primary key,
	total_acessos bigint not null default 0,
	atualizado_em timestamp default current_timestamp() on update current_timestamp(),
	constraint chk_resumo_single_row check (id = 1)
);

-- Constraints
alter table customers add constraint check_password check(CHAR_LENGTH(password_hash) >= 8);
alter table customers add constraint check_cpf check(CHAR_LENGTH(cpf) = 14);
alter table customers add constraint check_uf_format check(state_uf IN ('AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'));

alter table profiles add constraint profiles_customers_fk foreign key (id_customer) references customers(id) on update cascade on delete cascade;
alter table profiles add constraint unique_display_name unique(display_name, id_customer);

alter table categories add constraint check_category_name check(CHAR_LENGTH(name) >= 2);

alter table videos add constraint videos_studios_fk foreign key (id_studio) references studios(id) on update cascade on delete no action;
alter table videos add constraint videos_categories_fk foreign key (id_category) references categories(id) on update cascade on delete no action;

alter table play_logs add constraint play_logs_profiles_fk foreign key (id_profile) references profiles(id) on update cascade on delete no action;
alter table play_logs add constraint play_logs_device_types_fk foreign key (id_device_type) references device_types(id) on update cascade on delete no action;
alter table play_logs add constraint play_logs_videos_fk foreign key (id_video) references videos(id) on update cascade on delete no action;
alter table play_logs add constraint check_ip_format check(ip_address is not null and ip_address != '');

alter table watching_history add constraint watching_history_profiles_fk foreign key (id_profile) references profiles(id) on update cascade on delete no action;
alter table watching_history add constraint watching_history_videos_fk foreign key (id_video) references videos(id) on update cascade on delete no action;
alter table watching_history add constraint check_time_watched check(time_watched_seconds >= 0);
alter table watching_history add constraint check_stopped_after_started check(stopped_at is null or stopped_at >= started_at);

alter table subscription_plans add constraint subscription_plans_customers_fk foreign key (id_customer) references customers(id) on update cascade on delete cascade;
alter table subscription_plans add constraint check_end_after_start check(end_date is null or end_date >= start_date);

alter table payments add constraint payments_customers_fk foreign key (id_customer) references customers(id) on update cascade on delete cascade;
alter table payments add constraint payments_subscription_plans_fk foreign key (id_subscription_plan) references subscription_plans(id) on update cascade on delete no action;
alter table payments add constraint check_amount check(amount > 0);

alter table account_balance add constraint balance_customer_fk foreign key (id_customer) references customers(id) on update cascade on delete cascade;
alter table account_balance add constraint check_balance_non_negative check(balance >= 0);

alter table studio_billing add constraint studio_billing_studios_fk foreign key (id_studio) references studios(id) on update cascade on delete no action;
alter table studio_billing add constraint uq_studio_billing_studio_comp unique (id_studio, competencia);

-- index
alter table play_logs add index idx_play_timestamp (play_timestamp);
alter table play_logs add index idx_profile_video (id_profile, id_video);
alter table watching_history add index idx_watching_profile (id_profile, is_completed, started_at desc);
alter table audit_log add index idx_audit_tabela_data (tabela, data_hora);

-- view de segurança do usuário
create or replace view v_marketing_engagement as
select
    c.id                                             as customer_id,
    c.state_uf,
    timestampdiff(year, c.date_of_birth, current_date()) as age,
    count(distinct p.id)                             as total_profiles,
    coalesce(sum(wh.time_watched_seconds) / 3600, 0) as total_hours_watched
from customers c
left join profiles p          on c.id = p.id_customer
left join watching_history wh on p.id = wh.id_profile
group by c.id, c.state_uf, c.date_of_birth;

-- Caso necessário
-- drop database streamflow_database;