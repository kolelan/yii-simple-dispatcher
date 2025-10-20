-- Создание пользователя для приложения (опционально)
CREATE USER events_user WITH PASSWORD 'secure_password';

-- Выдача прав
GRANT CONNECT ON DATABASE events_db TO events_user;
GRANT USAGE ON SCHEMA public TO events_user;
GRANT SELECT, INSERT, UPDATE ON events TO events_user;
GRANT SELECT ON event_stats TO events_user;
GRANT SELECT ON event_summary TO events_user;
GRANT SELECT ON recent_events TO events_user;
GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO events_user;