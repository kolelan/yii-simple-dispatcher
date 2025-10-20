-- Основная таблица для хранения событий
CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    group_name VARCHAR(255) NOT NULL,
    payload JSONB,
    success BOOLEAN NOT NULL DEFAULT true,
    counter INTEGER NOT NULL DEFAULT 1,
    data JSONB,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Индекс для быстрого поиска по имени события
CREATE INDEX idx_events_name ON events(name);

-- Индекс для поиска по группе
CREATE INDEX idx_events_group ON events(group_name);

-- Индекс для фильтрации по успешности
CREATE INDEX idx_events_success ON events(success);

-- Индекс для временных запросов
CREATE INDEX idx_events_created_at ON events(created_at);

-- Составной индекс для частых запросов по группе и имени
CREATE INDEX idx_events_group_name ON events(group_name, name);

-- Индекс для полнотекстового поиска в JSON payload
CREATE INDEX idx_events_payload_gin ON events USING GIN (payload);

-- Индекс для полнотекстового поиска в JSON data
CREATE INDEX idx_events_data_gin ON events USING GIN (data);