<?php

namespace app\controllers;

use Yii;
use yii\console\Controller;
use app\models\Event;
use Faker\Factory as FakerFactory;
use yii\console\ExitCode;

/**
 * Пример контроллера для консольных приложений для генерации событий
 */
class EventGenerateController extends Controller
{

    /**
     * Обработчик событий
     */
    public function actionIndex(string $name, int $number)
    {
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
            $this->stdout("{$i} Событие {$name} сгенерировано!\n");
        }
        return ExitCode::OK;

    }
}