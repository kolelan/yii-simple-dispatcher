<?php

namespace app\handlers;

use app\models\Event;

class ExampleEventHandler
{
    const EVENT_NAME = 'CreateOvi';
    public function handle($event): void
    {
        // Логика обработки события
        echo "Обработка события: {$event->getName()}", PHP_EOL;
        $payload = $event->payload;
        $event->payload=['date'=>DATE('Y-m-d H:i:s')];
        $data = $event->data;
        $event->data = array_merge($data,$payload, ['handle_data'=>DATE('Y-m-d H:i:s')]);
        $event->success = true;
        $event->save();
        echo "Обработка события произведена", PHP_EOL;

    }
}