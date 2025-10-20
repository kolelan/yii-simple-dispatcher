-- Представление для получения сводной статистики
CREATE VIEW event_summary AS
SELECT
    name,
    group_name,
    COUNT(*) as total_events,
    SUM(CASE WHEN success THEN 1 ELSE 0 END) as successful_events,
    SUM(CASE WHEN NOT success THEN 1 ELSE 0 END) as failed_events,
    SUM(counter) as total_count,
    MIN(created_at) as first_occurrence,
    MAX(created_at) as last_occurrence
FROM events
GROUP BY name, group_name;

-- Представление для последних событий
CREATE VIEW recent_events AS
SELECT
    id,
    name,
    group_name,
    success,
    counter,
    created_at
FROM events
ORDER BY created_at DESC
LIMIT 1000;