<?php

namespace app\components;

use Closure;
use yii\base\Component;
use app\events\EventInterface;
use app\models\Event;
use app\commands\EventJob;

class EventDispatcher extends Component
{
    private array $listeners = [];

    /**
     * Подписка на событие
     */
    public function subscribe(string $eventName, callable $handler): void
    {
        if (!isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = [];
        }

        $this->listeners[$eventName][] = $handler;
    }

    /**
     * Вызов события
     */
    public function dispatch(EventInterface $event): void
    {
        $eventName = $event->getName();

        if (!isset($this->listeners[$eventName])) {
            return;
        }

        foreach ($this->listeners[$eventName] as $handler) {
            if ($handler instanceof Closure) {
                $handler($event);
            } else {
                call_user_func($handler, $event);
            }
        }
    }

    public function pushToQueue(EventInterface $event): void
    {
        // Получаем или создаём запись события
        $record = Event::findOne(['name' => $event->getName()]);
        if (!$record) {
            $record = new Event();
            $record->name = $event->getName();
            $record->group_name = 'default';
            $record->payload = ['event' => $event->getName()];
            $record->success = false;
            $record->pending = false;
            $record->save();
        }

        // Если событие ещё не обработано и не заблокировано
        if (!$record->success && !$record->pending) {
            $record->pending = true;
            $record->save();

            // Отправляем в очередь
            \Yii::$app->queue->push(new EventJob([
                'eventId' => $record->id,
            ]));
        }
    }
}