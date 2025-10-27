# Конфигурирование миграций

Покажу различные способы конфигурации компонента для работы с миграциями в Yii2.

## 1. Базовая конфигурация в console.php

```php
<?php

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@base' => dirname(__DIR__),
        '@app' => dirname(__DIR__).'/src',
        '@console' => dirname(__DIR__),
        '@yii' => dirname(__DIR__) . '/vendor/yiisoft/yii2',
        '@upload' => dirname(__DIR__) . '/uploads',
        '@tests' => dirname(__DIR__) . '/tests',
        '@logs' => dirname(__DIR__) . '/logs',
    ],
    'controllerMap' => [
        // Базовая конфигурация миграций
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@app/migrations',
                '@yii/rbac/migrations', // Миграции RBAC если используете
            ],
            'migrationTable' => '{{%migration}}',
        ],
    ],
    'components' => [
        'db' => require __DIR__ . '/db.php',
        // ... другие компоненты
    ],
];
```

## 2. Расширенная конфигурация с несколькими источниками миграций

```php
'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => [
            '@app/migrations',           // Основные миграции приложения
            '@app/migrations/systems',   // Системные миграции
            '@app/migrations/data',      // Миграции данных
        ],
        'migrationNamespaces' => [
            'yii\queue\redis\migrations', // Миграции очередей
            'zhuravljov\yii\log-migrations', // Миграции для логов в БД
        ],
        'migrationTable' => '{{%migration}}',
        'templateFile' => '@app/views/migration.php', // Кастомный шаблон
    ],
],
```

## 3. Конфигурация для нескольких баз данных

```php
'controllerMap' => [
    // Основная база данных
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => '@app/migrations/main',
        'migrationTable' => '{{%migration}}',
        'db' => 'db', // Компонент основной БД
    ],
    
    // Лог база данных
    'migrate-log' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => '@app/migrations/log',
        'migrationTable' => '{{%migration_log}}',
        'db' => 'logDb', // Компонент БД для логов
    ],
    
    // Внешняя база данных
    'migrate-external' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => '@app/migrations/external',
        'migrationTable' => '{{%migration_external}}',
        'db' => 'edb',
    ],
],
```

## 4. Конфигурация с кастомными параметрами

```php
'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => '@app/migrations',
        'migrationTable' => '{{%migration}}',
        
        // Настройки безопасности
        'useTablePrefix' => true,
        
        // Настройки вывода
        'compact' => false, // Подробный вывод
        'color' => true,    // Цветной вывод
        
        // Кастомный шаблон генерации миграций
        'templateFile' => '@app/views/migration.php',
        
        // Генератор полей
        'generatorTemplateFiles' => [
            'create_table' => '@app/views/createTableMigration.php',
            'drop_table' => '@app/views/dropTableMigration.php',
            'add_column' => '@app/views/addColumnMigration.php',
            'drop_column' => '@app/views/dropColumnMigration.php',
            'create_junction' => '@app/views/createTableMigration.php',
        ],
    ],
],
```

## 5. Создание кастомного шаблона миграций

**Создайте файл `views/migration.php`:**

```php
<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */

/** @var string $className the new migration class name without namespace */
/** @var string $namespace the new migration class namespace */
/** @var string $table the table name */
/** @var array $fields the fields */
/** @var array $foreignKeys the foreign keys */

echo "<?php\n";
if (!empty($namespace)) {
    echo "\nnamespace {$namespace};\n";
}
?>

use yii\db\Migration;

/**
 * Миграция <?= $className . "\n" ?>
 */
class <?= $className ?> extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
<?php if (!empty($table) && !empty($fields)): ?>
        $this->createTable('<?= $table ?>', [
<?php foreach ($fields as $field): ?>
            <?= $field ?>,
<?php endforeach; ?>
        ]);

<?php endif; ?>
<?php if (!empty($foreignKeys)): ?>
<?php foreach ($foreignKeys as $key): ?>
        $this->addForeignKey(
            '<?= $key['fk'] ?>',
            '<?= $key['table'] ?>',
            '<?= $key['fields'] ?>',
            '<?= $key['refTable'] ?>',
            '<?= $key['refFields'] ?>',
            'CASCADE'
        );
<?php endforeach; ?>
<?php endif; ?>
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
<?php if (!empty($foreignKeys)): ?>
<?php foreach ($foreignKeys as $key): ?>
        $this->dropForeignKey('<?= $key['fk'] ?>', '<?= $key['table'] ?>');
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($table)): ?>
        $this->dropTable('<?= $table ?>');
<?php endif; ?>
    }
}
```

## 6. Конфигурация для модульных миграций

```php
'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => [
            '@app/migrations',
        ],
        'migrationNamespaces' => [
            'app\modules\user\migrations',
            'app\modules\catalog\migrations',
            'app\modules\order\migrations',
            'app\modules\payment\migrations',
        ],
        'migrationTable' => '{{%migration}}',
    ],
],
```

## 7. Конфигурация с environment-переменными

```php
'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => [
            '@app/migrations',
        ],
        'migrationTable' => '{{%migration}}',
        
        // Environment-specific настройки
        'compact' => YII_ENV_PROD, // В production компактный вывод
        'interactive' => !YII_ENV_PROD, // В production неинтерактивный режим
    ],
],
```

## 8. Компонент для управления миграциями через конфиг

**Создайте файл `config/migrations.php`:**

```php
<?php

return [
    'paths' => [
        '@app/migrations',
        '@app/migrations/systems',
        '@app/migrations/data',
    ],
    'namespaces' => [
        'yii\queue\redis\migrations',
    ],
    'table' => '{{%migration}}',
    'template' => '@app/views/migration.php',
];
```

**Используйте в console.php:**

```php
$migrationConfig = require __DIR__ . '/migrations.php';

'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => $migrationConfig['paths'],
        'migrationNamespaces' => $migrationConfig['namespaces'],
        'migrationTable' => $migrationConfig['table'],
        'templateFile' => $migrationConfig['template'],
    ],
],
```

## 9. Пример использования команд

После настройки можно использовать:

```bash
# Создание миграции
docker compose exec php-cli php /app/bin/yii migrate/create create_user_table

# Применение миграций
docker compose exec php-cli php /app/bin/yii migrate

# Откат миграций
docker compose exec php-cli php /app/bin/yii migrate/down

# Просмотр истории
docker compose exec php-cli php /app/bin/yii migrate/history

# Для конкретной БД
docker compose exec php-cli php /app/bin/yii migrate-log
docker compose exec php-cli php /app/bin/yii migrate-external
```

## 10. Полная конфигурация для вашего случая

```php
'controllerMap' => [
    'migrate' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => [
            '@app/migrations',
            '@app/migrations/logs', // Для миграций таблиц логов
        ],
        'migrationNamespaces' => [
            'yii\queue\redis\migrations', // Если используете очереди
        ],
        'migrationTable' => '{{%migration}}',
        'templateFile' => '@app/views/migration.php',
    ],
    
    // Отдельная команда для миграций логов
    'migrate-logs' => [
        'class' => 'yii\console\controllers\MigrateController',
        'migrationPath' => '@app/migrations/logs',
        'migrationTable' => '{{%migration_log}}',
        'db' => 'db', // Или отдельная БД для логов если нужно
    ],
],
```

Такая конфигурация позволяет гибко управлять миграциями в проекте, поддерживать несколько баз данных и модулей, а также кастомизировать процесс генерации и выполнения миграций.