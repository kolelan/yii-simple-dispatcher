-- Вставка примера события
INSERT INTO events (name, group_name, payload, success, counter, data)
VALUES (
    'user_registration',
    'authentication',
    '{"user_id": 123, "email": "user@example.com"}',
    true,
    1,
    '{"ip_address": "192.168.1.1", "user_agent": "Mozilla/5.0"}'
);

-- Поиск событий по имени
SELECT * FROM events WHERE name = 'user_registration';

-- Статистика по группам
SELECT
    group_name,
    COUNT(*) as event_count,
    AVG(counter) as avg_counter
FROM events
GROUP BY group_name
ORDER BY event_count DESC;

-- События за последние 24 часа
SELECT
    name,
    group_name,
    COUNT(*) as count
FROM events
WHERE created_at >= NOW() - INTERVAL '24 hours'
GROUP BY name, group_name;