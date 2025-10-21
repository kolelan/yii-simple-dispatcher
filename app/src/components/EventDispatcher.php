<?php

namespace app\components;

use Closure;
use yii\base\Component;
use app\events\EventInterface;

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
}