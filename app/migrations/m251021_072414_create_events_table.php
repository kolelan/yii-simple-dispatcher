<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%events}}`.
 */
class m251021_072414_create_events_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%events}}', [
             'id' => $this->primaryKey(),
             'name' => $this->string(255)->notNull(),
             'group_name' => $this->string(255)->notNull(),
             'payload' => "JSONB",
             'success' => $this->boolean()->notNull()->defaultValue(true),
             'counter' => $this->integer()->notNull()->defaultValue(1),
             'data' => "JSONB",
             'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
             'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Индексы
        $this->createIndex('idx_events_name', 'events', 'name');
        $this->createIndex('idx_events_group', 'events', 'group_name');
        $this->createIndex('idx_events_success', 'events', 'success');
        $this->createIndex('idx_events_created_at', 'events', 'created_at');

        // Составной индекс
        $this->createIndex('idx_events_group_name', 'events', ['group_name', 'name']);

        // GIN-индексы для JSONB полей
        $this->execute("CREATE INDEX idx_events_payload_gin ON events USING GIN (payload)");
        $this->execute("CREATE INDEX idx_events_data_gin ON events USING GIN (data)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%events}}');
    }
}
