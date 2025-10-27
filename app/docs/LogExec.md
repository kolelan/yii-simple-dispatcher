# Использование логов
Знание PID (Process ID) в логах Yii дает несколько важных преимуществ для отладки и мониторинга:

## Пример конфигурации логирования
```injectablephp
                # Для всех ошибок и информационных сообщений
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['application'],
                    'logFile' => '@logs/console.log',
                    'maxFileSize' => 1024 * 10,  10 MB
                    'maxLogFiles' => 5,
                    'logVars' => [],  Не логировать переменные $_GET, $_POST и т.д.
                    'prefix' => function ($message) {
                        $context = Yii::$app->controller !== null
                            ? Yii::$app->controller->getUniqueId()
                            : 'unknown';
                        return "[$context]";
                    },
                ],
                # Пример конфигурации логирования для отладки
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['debug'],
                    'logFile' => '@logs/debug.log',
                    'maxFileSize' => 1024 * 10,  10 MB
                    'maxLogFiles' => 5,
                    'logVars' => [],  Не логировать переменные $_GET, $_POST и т.д.
                    'prefix' => function ($message) {
                        $context = Yii::$app->controller !== null
                            ? Yii::$app->controller->getUniqueId()
                            : 'unknown';
                        return "[$context]";
                    },
                ],

```

## 1. **Отслеживание параллельных процессов**
```php
'prefix' => function ($message) {
    $pid = function_exists('posix_getpid') ? posix_getpid() : 'unknown';
    return "[pid:$pid]";
}
```
```
[pid:1234] User login successful
[pid:1235] Processing background job
[pid:1236] Sending email notification
```

## 2. **Диагностика "зависших" процессов**
```bash
# В логах висит процесс с PID 5678
[pid:5678] Started processing large file
[pid:5678] Processing row 1...1000
# Дальше ничего - процесс завис

# Можно проверить состояние процесса
ps aux | grep 5678
kill -9 5678  # Если нужно принудительно завершить
```

## 3. **Анализ утечек памяти**
```php
 В разных процессах видна разная память
[pid:1111][memory:45MB] Processing started
[pid:2222][memory:89MB] Processing started  
[pid:3333][memory:210MB] Processing started  # Утечка!
```

## 4. **Отладка конкурентных операций**
```bash
# При работе с очередями или фоновыми задачами
[pid:1001][queue:order_processing] Started job 123
[pid:1002][queue:order_processing] Started job 124  
[pid:1001][queue:order_processing] Finished job 123
[pid:1003][queue:order_processing] Started job 125
```

## 5. **Мониторинг производительности по процессам**
```php
'prefix' => function ($message) {
    $pid = function_exists('posix_getpid') ? posix_getpid() : 'unknown';
    $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
    return "[pid:$pid][mem:{$memory}MB]";
}
```

## 6. **Пример реального использования**

**Конфигурация:**
```php
'prefix' => function ($message) {
    $pid = function_exists('posix_getpid') ? posix_getpid() : 'unknown';
    $time = date('Y-m-d H:i:s');
    $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
    $route = Yii::$app->requestedRoute ?? 'console';
    
    return "[$time][pid:$pid][mem:{$memory}MB][$route]";
}
```

**В логах:**
```
[2024-01-15 10:30:01][pid:1234][mem:45.2MB][queue/worker] Started processing job
[2024-01-15 10:30:01][pid:1235][mem:43.1MB][queue/worker] Started processing job
[2024-01-15 10:30:02][pid:1234][mem:67.8MB][queue/worker] Memory usage growing
[2024-01-15 10:30:03][pid:1234][mem:210.5MB][queue/worker] CRITICAL: High memory usage!
```

## 7. **Практические сценарии использования**

### Сценарий 1: Поиск проблемного процесса
```bash
# В логах видим
[pid:8888] Error: Database connection timeout

# Ищем процесс
ps aux | grep 8888
# И видим, что он "зомби" и его нужно завершить
```

### Сценарий 2: Анализ нагрузки
```bash
# Считаем уникальные PID в логах за последний час
grep "$(date -d '1 hour ago' '+%Y-%m-%d %H:')" app.log | awk '{print $2}' | sort | uniq | wc -l
# Результат: 15 процессов работало за последний час
```

### Сценарий 3: Отладка deadlock
```bash
[pid:1001] Waiting for database lock...
[pid:1002] Waiting for database lock...
[pid:1001] Still waiting...
# Видим, что два процесса блокируют друг друга
```

## 8. **Ограничения и альтернативы**

Если `posix_getpid()` недоступна (Windows или ограниченный хостинг), можно использовать:

```php
$pid = function_exists('posix_getpid') ? posix_getpid() : getmypid();
 или
$pid = getmypid();  Всегда доступно, но может вернуть ID родительского процесса
```

**Вывод:** PID в логах - это мощный инструмент для отладки многопоточных приложений, поиска утечек памяти и анализа производительности в production-среде.

### Контекст для ошибок
```php
<?php
try {
     какой-то код
} catch (\Exception $e) {
    \Yii::error( [
        'text'=>'Ошибка при обработке заказа',
        'category' => 'orders',
        'order_id' => $orderId,
        'exception' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
}
```

### Для отладки производительности
```php 
<?php
$startTime = microtime(true);
$startMemory = memory_get_usage(true);

 Код который отслеживаем

\Yii::info('Производительность операции', [
    'category' => 'performance',
    'execution_time_sec' => round(microtime(true) - $startTime, 3),
    'memory_used_mb' => round((memory_get_usage(true) - $startMemory) / 1024 / 1024, 2),
    'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2)
]);
```

### Для бизнес-логики
```injectablephp
\Yii::info(, [
    'text'=> 'Заказ создан',
    'category' => 'business',
    'order_id' => $order->id,
    'user_id' => $order->user_id,
    'amount' => $order->amount,
    'items_count' => count($order->items)
]
);
```

### Для системного мониторинга
```injectablephp
\Yii::info('Статус системы', [
    'category' => 'system',
    'load_average' => sys_getloadavg(),
    'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2),
    'disk_free' => round(disk_free_space("/") / 1024 / 1024 / 1024, 2)
]);
```

