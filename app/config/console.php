<?php

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'components' => [
        'db' => require __DIR__ . '/db.php',
        'eventDispatcher' => [
            'class' => 'app\components\EventDispatcher',
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
    ]
];