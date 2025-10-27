<?php
// Основной лог для вывода ошбибок для разработчика
return [
    'class' => 'yii\log\FileTarget',
    'levels' => ['error', 'warning', 'info'],
    'logFile' => '@logs/debug/' . date('Y-m-d') . '.log',
    'categories' => ['debug'],
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