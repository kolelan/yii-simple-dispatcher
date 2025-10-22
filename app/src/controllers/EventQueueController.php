<?php

namespace app\controllers;

use Yii;
use yii\console\Controller;
use app\models\Event;
use Faker\Factory as FakerFactory;
use yii\console\ExitCode;

/**
 * Пример контроллера для консольных приложений с отправкой событий в очередь
 */
class EventQueueController extends Controller
{

    /**
     * Обработчик событий
     */
    public function actionIndex(string $name, int $number)
    {
        /** @var $dispatcher \app\components\EventDispatcher Диспетчер событий */
        $dispatcher = \Yii::$app->get('eventDispatcher');

        $factory = FakerFactory::create('ru_RU');

        foreach (range(1, $number) as $i) {
            $payload = [];
            $payload['data'] = [
                'way' => $factory->uuid,
                'milbase' => $factory->company,
                'kls_id' => $factory->randomNumber()];
            $payload['metadata'] = $factory->word;
            $payload['object'] = $factory->word;
            $payload['function'] = $factory->word;

            $data = [
                'card_id' => $factory->randomNumber(),
                'card_name' => $factory->company,
                'description' => $factory->text(100),
            ];
            $event = new Event();
            $event->name = $name;
            $event->group_name = "Group {$name}";
            $event->payload = $payload;
            $event->data = $data;
            $event->success = false;
            $event->save();
            $dispatcher->pushToQueue($event);
            $this->stdout("{$i} Событие {$name} {$event->id} сгенерировано и отправлено в очередь".PHP_EOL);
        }
        return ExitCode::OK;

    }

}