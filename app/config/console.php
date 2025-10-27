<?php

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'aliases' => [
        '@base' => dirname(__DIR__),
        '@app' => dirname(__DIR__) . '/src',
        '@console' => dirname(__DIR__),
        '@yii' => dirname(__DIR__) . '/vendor/yiisoft/yii2',
        '@upload' => dirname(__DIR__) . '/uploads',
        '@tests' => dirname(__DIR__) . '/tests',
        '@migrations' => dirname(__DIR__) . '/migrations',
        '@logs' => dirname(__DIR__) . '/logs',
    ],
    'bootstrap' => [
        'log',
        'app\bootstrap\Bootstrap'
    ],
    'components' => [
        'db' => require __DIR__ . '/db.php',
        'edb' => require __DIR__ . '/edb.php',
        'idb' => require __DIR__ . '/idb.php',
        'eventDispatcher' => [
            'class' => 'app\components\EventDispatcher',
        ],
        'log' => [
            'targets' => [
                require __DIR__ . '/log_db.php', // База данных
                require __DIR__ . '/log_a.php', // Все
                require __DIR__ . '/log_d.php', // Дебаг
                require __DIR__ . '/log_e.php', // Ошибки
                require __DIR__ . '/log_i.php', // Информация
                require __DIR__ . '/log_w.php', // Внимание
            ]
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'redis',
            'port' => 6379,
            'database' => 0,
        ],
        'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'channel' => 'event_queue', // Название очереди
            'redis' => 'redis', // Ссылка на компонент redis

        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => '@migrations',
            'migrationTable' => '{{%migration}}',
            ]
        ],

];