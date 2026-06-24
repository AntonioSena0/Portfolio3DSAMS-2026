use streamflow_database;

-- Continuar Assistindo
select
	wh.id as history_id,
	wh.started_at,
	wh.stopped_at,
	wh.time_watched_seconds,
	wh.is_completed,
	wh.is_paused,
	v.id as video_id,
	v.title as video_title,
	v.duration_seconds,
	v.is_available,
	s.name as studio_name,
	c.name as category_name,
	c.category_type as video_type
from watching_history wh 
join videos v on wh.id_video = v.id
join studios s on v.id_studio = s.id
join categories c on v.id_category = c.id
where wh.id_profile = 1 and wh.is_completed = false and wh.time_watched_seconds > 0
order by wh.started_at desc;

-- Relatório de Cobrança dos Estúdios (tempo consumido)
select
	s.name as studio_name,
	sum(wh.time_watched_seconds) / 60 as total_minutes_watched,
	sum(wh.time_watched_seconds) / 3600 as total_hours_watched
from watching_history wh
join videos v on wh.id_video = v.id
join studios s on v.id_studio = s.id
where wh.started_at >= '2026-06-01' and wh.started_at < '2026-07-01'
group by s.name
having sum(wh.time_watched_seconds) / 3600 > 5000
order by total_hours_watched desc;

-- Auditoria de Tráfego por Região
select
	c.state_uf,
	dt.type_name as device_type,
	count(pl.id) as total_accesses
from play_logs pl
join profiles p on pl.id_profile = p.id
join customers c on p.id_customer = c.id
join device_types dt on pl.id_device_type = dt.id
group by c.state_uf, dt.type_name
order by c.state_uf, dt.type_name;

-- Tuning
explain
select
	wh.id as history_id,
	wh.started_at,
	wh.stopped_at,
	wh.time_watched_seconds,
	wh.is_completed,
	wh.is_paused,
	v.id as video_id,
	v.title as video_title,
	v.duration_seconds,
	v.is_available,
	s.name as studio_name,
	c.name as category_name
from watching_history wh
join videos v on wh.id_video = v.id
join studios s on v.id_studio = s.id
join categories c on v.id_category = c.id
where wh.id_profile = 1 and wh.is_completed = false and wh.time_watched_seconds > 0
order by wh.started_at desc;

-- LGPD teste
select * from v_marketing_engagement;