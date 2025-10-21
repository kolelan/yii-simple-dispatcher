<?php

namespace app\controllers;

use Yii;
use yii\console\Controller;
use app\models\Event;
use app\components\EventDispatcher;
use app\events\ExampleEvent;
use app\handlers\ExampleEventHandler;
use yii\console\ExitCode;

/**
 * Пример контроллера для консольных приложений с обработчиками событий
 */
class EventHandleController extends Controller
{
    private $dispatcher;
    public function __construct($id, $module, $config = [])
    {
        $this->dispatcher = \Yii::$app->get('eventDispatcher');
        parent::__construct($id, $module, $config);
    }

    /**
     * Обработчик событий
     */
    public function actionIndex()
    {
        // Подписываемся на событие
        $this->dispatcher->subscribe(ExampleEventHandler::EVENT_NAME, [new ExampleEventHandler(), 'handle']);
        // Получаем событие из БД
        $event = Event::findOne(['name'=>ExampleEventHandler::EVENT_NAME,'success' => false]);
        if (!$event) {
            echo "Событие не найдено.\n";
            return ExitCode::OK;
        }
        // Запускаем обработчик событий
        $this->dispatcher->dispatch($event);
        echo "Событие обработано!\n";
        return ExitCode::OK;
    }
}