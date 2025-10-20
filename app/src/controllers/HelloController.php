<?php

namespace app\controllers;

use Yii;
use yii\console\Controller;

class HelloController extends Controller
{
    public function actionIndex()
    {
        echo "Привет из консольного приложения на Yii!\n";
    }

    public function actionDbCheck()
    {
        echo "Проверка соединения с БД\n";
        $sql = "SELECT 1";
        $res = Yii::$app->db->createCommand($sql)->queryScalar();
        var_dump($res);
        echo "OK\n";
    }
}