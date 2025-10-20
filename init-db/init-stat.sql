-- Таблица для агрегированной статистики по событиям
CREATE TABLE event_stats (
    id SERIAL PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_group VARCHAR(255) NOT NULL,
    total_count BIGINT NOT NULL DEFAULT 0,
    success_count BIGINT NOT NULL DEFAULT 0,
    failure_count BIGINT NOT NULL DEFAULT 0,
    last_occurrence TIMESTAMP WITH TIME ZONE,
    first_occurrence TIMESTAMP WITH TIME ZONE,
    UNIQUE(event_name, event_group)
);

-- Индексы для статистики
CREATE INDEX idx_event_stats_name ON event_stats(event_name);
CREATE INDEX idx_event_stats_group ON event_stats(event_group);