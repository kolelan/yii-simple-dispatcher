<?php

use Dotenv\Dotenv;
use yii\helpers\VarDumper;

require_once dirname(__DIR__) . '/vendor/autoload.php';
$_ENV = [];

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();



return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=10.99.17.21;port=5432;dbname=db_kaponir',
    'username' => 'postgres',
    'password' => '',
//    'dsn' => $_ENV['EDB_DSN'],
//    'username' => $_ENV['EDB_USERNAME'],
//    'password' => $_ENV['EDB_PASSWORD'],
    'charset' => 'utf8',
];