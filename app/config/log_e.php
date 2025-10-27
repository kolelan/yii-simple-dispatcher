<?php
// Основной лог для сообщений ошибок
const LOCAL_LOG_LEVEL_ERROR = 'error';
return [
    'class' => 'yii\log\FileTarget',
    'levels' => [LOCAL_LOG_LEVEL_ERROR],
    'logFile' => '@logs/' . LOCAL_LOG_LEVEL_ERROR . '/' . date('Y-m-d') . '_' . LOCAL_LOG_LEVEL_ERROR . '.log',
    'categories' => ['application'],
    'maxFileSize' => 0, # 1024 * 10, // 10 MB, 0 - Отключаем ротацию по размеру
    'maxLogFiles' => 30, // Храним логи за 30 дней
    'logVars' => [], // Не логировать переменные $_GET, $_POST и т.д.
    'prefix' => function ($message) {
        $pid = function_exists('posix_getpid') ? posix_getpid() : 'unknown';
        $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
        $route = Yii::$app->requestedRoute ?? 'console';
        return "[pid:$pid][mem:{$memory}MB][$route]";
    },
    'exportInterval' => 1, // Записывать после каждого сообщения
];