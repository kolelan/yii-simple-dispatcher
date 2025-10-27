<?php

return [
    'class' => 'app\components\PostgresDbTarget',
    'levels' => ['error', 'warning', 'info'],
    'categories' => ['application','debug'],
    'logVars' => [],
    'prefix' => function ($message) {
        $pid = function_exists('posix_getpid') ? posix_getpid() : getmypid();
        $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
        $route = Yii::$app->requestedRoute ?? 'console';
        return "[pid:$pid][mem:{$memory}MB][$route]";
    },
    // Интервал экспорта (для базы лучше реже)
    'exportInterval' => 10,
];
