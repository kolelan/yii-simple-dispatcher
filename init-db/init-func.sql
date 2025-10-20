-- Функция для автоматического обновления updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Триггер для автоматического обновления updated_at
CREATE TRIGGER update_events_updated_at
    BEFORE UPDATE ON events
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

-- Функция для обновления статистики
CREATE OR REPLACE FUNCTION update_event_stats()
RETURNS TRIGGER AS $$
BEGIN
    -- Обновляем или вставляем статистику
    INSERT INTO event_stats (
        event_name,
        event_group,
        total_count,
        success_count,
        failure_count,
        last_occurrence,
        first_occurrence
    )
    VALUES (
        NEW.name,
        NEW.group_name,
        NEW.counter,
        CASE WHEN NEW.success THEN NEW.counter ELSE 0 END,
        CASE WHEN NOT NEW.success THEN NEW.counter ELSE 0 END,
        NEW.created_at,
        NEW.created_at
    )
    ON CONFLICT (event_name, event_group)
    DO UPDATE SET
        total_count = event_stats.total_count + NEW.counter,
        success_count = event_stats.success_count +
            CASE WHEN NEW.success THEN NEW.counter ELSE 0 END,
        failure_count = event_stats.failure_count +
            CASE WHEN NOT NEW.success THEN NEW.counter ELSE 0 END,
        last_occurrence = GREATEST(event_stats.last_occurrence, NEW.created_at);

    RETURN NEW;
END;
$$ language 'plpgsql';

-- Триггер для автоматического обновления статистики
CREATE TRIGGER update_stats_on_event_insert
    AFTER INSERT ON events
    FOR EACH ROW
    EXECUTE FUNCTION update_event_stats();