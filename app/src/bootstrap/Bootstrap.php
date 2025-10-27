<?php

namespace app\bootstrap;

use yii\base\BootstrapInterface;
use Yii;
use yii\di\Container;
use yii\log\Logger;

class Bootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $container = Yii::$container;

        try{
            echo 'Bootstrap databases definition', PHP_EOL;
        }catch (\Exception $e){
            echo 'Error in bootstrap databases definition', $e->getMessage(), PHP_EOL;
        }

    }
}