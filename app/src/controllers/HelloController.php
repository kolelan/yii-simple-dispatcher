<?php

namespace app\controllers;

use yii\console\Controller;

class HelloController extends Controller
{
    public function actionIndex()
    {
        echo "Привет из консольного приложения на Yii!\n";
    }
}