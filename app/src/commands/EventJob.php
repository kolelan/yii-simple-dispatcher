<?php

namespace app\commands;

use yii\base\BaseObject;
use yii\queue\JobInterface;
use app\models\Event;
use app\handlers\ExampleEventHandler;

class EventJob extends BaseObject implements JobInterface
{
    public int $eventId;

    public function execute($queue)
    {
        $eventModel = Event::findOne($this->eventId);

        if (!$eventModel || $eventModel->success) {
            return;
        }

        try {
            // Блокируем событие
            $eventModel->pending = true;
            $eventModel->save();

            // Создаём событие
            $event = new \app\events\ExampleEvent($eventModel->name);

            // Вызываем обработчик
            $handler = new ExampleEventHandler();
            $handler->handle($event);

            // Обновляем статус
            $eventModel->success = true;
            $eventModel->pending = false;
            $eventModel->save();
        } catch (\Exception $e) {
            // В случае ошибки — увеличиваем счётчик и не удаляем из очереди
            $eventModel->counter += 1;
            $eventModel->pending = false;
            $eventModel->save();

            throw $e;
        }
    }
}