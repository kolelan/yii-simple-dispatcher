<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
$_ENV = [];

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=10.99.17.171;port=5433;dbname=db_kaponir',
    'username' => 'postgres',
    'password' => '',
//    'dsn' => $_ENV['IDB_DSN'],
//    'username' => $_ENV['IDB_USERNAME'],
//    'password' => $_ENV['IDB_PASSWORD'],
    'charset' => 'utf8',
];