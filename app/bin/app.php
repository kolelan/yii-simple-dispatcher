#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/vendor/autoload.php';

if (!class_exists('Yii')) {
    require_once dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';
}

use yii\console\Application;

// Подключаем конфигурацию
$config = require dirname(__DIR__) . '/config/console.php';

$application = new Application($config);

$exitCode = $application->run();
exit($exitCode);