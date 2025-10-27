<?php

return [
    'class' => 'yii\log\FileTarget',
    'levels' => ['error', 'warning', 'info'],
    'logFile' => '@logs/console_' . date('Y-m-d') . '.log',
    'maxFileSize' => 0, // Отключаем ротацию по размеру
    'maxLogFiles' => 30, // Храним логи за 30 дней
    'logVars' => [],
    'prefix' => function ($message) {
        $pid = function_exists('posix_getpid') ? posix_getpid() : 'unknown';
        $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
        $route = Yii::$app->requestedRoute ?? 'console';
        return "[pid:$pid][mem:{$memory}MB][$route]";
    },
    'exportInterval' => 1, // Записывать после каждого сообщения
];