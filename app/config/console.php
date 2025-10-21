<?php

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'components' => [
            'db' => require __DIR__ . '/db.php',
            'export_db' => require __DIR__ . '/export_db.php',
            'import_db' => require __DIR__ . '/export_db.php',
            'service_db' => require __DIR__ . '/export_db.php',
        ],
];