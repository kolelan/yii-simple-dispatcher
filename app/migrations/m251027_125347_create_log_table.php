<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log}}`.
 */
class m251027_125347_create_log_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->bigPrimaryKey(),
            'level' => $this->string(16)->notNull(),
            'category' => $this->string(255)->notNull(),
            'log_time' => $this->double()->notNull(),
            'prefix' => $this->text(),
            'message' => $this->text()->notNull(),
            'context' => $this->json(), // Для PostgreSQL используем тип JSON
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Индексы для быстрого поиска
        $this->createIndex('idx_log_level', '{{%log}}', 'level');
        $this->createIndex('idx_log_category', '{{%log}}', 'category');
        $this->createIndex('idx_log_time', '{{%log}}', 'log_time');
        $this->createIndex('idx_log_created_at', '{{%log}}', 'created_at');

        // Составной индекс для частых запросов
        $this->createIndex('idx_log_level_category', '{{%log}}', ['level', 'category']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%log}}');
    }
}
