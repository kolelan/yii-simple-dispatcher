<?php

namespace app\controllers;

use Yii;
use yii\console\Controller;
use app\models\Event;
use yii\console\ExitCode;

/**
 * Пример контроллера для консольных приложений
 */
class HelloController extends Controller
{

    /**
     * Проврка конфигурации и тестирование окружения
     * @return int
     */
    public function actionIndex()
    {
        echo "Привет из консольного приложения на Yii!\n";
        // Разные уровни логирования
        \Yii::info('Запуск дебагера', 'debug');
        \Yii::info('Данные для информирования', 'application');
        \Yii::warning('Предупреждение: что-то пошло не так', 'application');
        \Yii::error('Критическая ошибка', 'application');

        // Логирование с контекстом
        \Yii::info([
            'text'=> 'Обработка данных',
            'category' => 'debug',
            'memory' => memory_get_usage(),
            'time' => time(),
        ],'debug');
        // Логирование с контекстом
        \Yii::info([
            'text'=> 'Обработка иформации',
            'category' => 'debug',
            'memory' => memory_get_usage(),
            'time' => time(),
        ],'application');

        return ExitCode::OK;
    }

    /**
     * Проверка соединения с БД
     * @throws \yii\db\Exception
     */
    public function actionDbCheck()
    {
        echo "Проверка соединения с БД\n";
        $sql = "SELECT 1";
        $res = Yii::$app->db->createCommand($sql)->queryScalar();
        var_dump($res);
        echo "OK\n";
    }

    /**
     * Добавление события
     * @throws \yii\db\Exception
     */
    public function actionAddEvent()
    {
        // Создать новое событие
        $event = new Event();
        $event->name = 'test_event_2';
        $event->group_name = 'group_a';
        $event->payload = [
            'method' => 'create',
            'data' => [
                'way' => 'qop;iwerqpwoeiirhpqepqweriohtpq;owowerijfpqoewweorh',
                'gemoetry_name' => 'geometry_1_test'
            ],
            'metadata' => 'card_ovi_main',
            'function' => 'card_ovi_i',
            'object' => 'card_ovi'
        ];
        $event->success = false;
        $event->counter = 1;
        $event->data = [
            'card_id' => 12345,
            'card_name' => 'test'
        ];
        if ($event->save()) {
            echo "Событие сохранено!\n";
        } else {
            print_r($event->getErrors());
        }
    }

}