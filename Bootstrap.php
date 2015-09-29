<?php

namespace gbksoft\apnsGcm;

use yii\console\Application as ConsoleApplication;

class Bootstrap implements BootstrapInterface
{
    /** @inheritdoc */
    public function bootstrap($app)
    {
        if ($app instanceof ConsoleApplication) {
            $app->controllerMap[$this->id] = [
                'class' => 'yii\gii\console\GenerateController',
                'generators' => array_merge($this->coreGenerators(), $this->generators),
                'module' => $this,
            ];
        }
    }
}