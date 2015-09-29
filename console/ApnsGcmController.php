<?php

namespace gbksoft\apnsGcm\console;

use Yii;
use webtoucher\amqp\controllers\AmqpConsoleController;

class ApnsGcmController extends AmqpConsoleController
{
    public function actionRun()
    {
        $amqp = Yii::$app->amqp;
        $channel = $amqp->getChannel();
        $channel->queue_declare('apns-gcm', false, true, false, false);
        $message = $channel->basic_get('apns-gcm');
        $body = json_decode($message->body);

        if (!$body) {
            $channel->basic_ack($message->delivery_info['delivery_tag']);
            Yii::error('Has no body', 'ApnsGcm');
            return false;
        }

        // Log data
        Yii::info($message->body, 'ApnsGcm');

        /* @var $apnsGcm \bryglen\apnsgcm\ApnsGcm */
        $result = Yii::$app->apnsGcm->send(
            $body->type,
            (array)$body->tokens,
            $body->text,
            $body->payloadData,
            $body->args
        );

        $channel->close();

        return $result;
    }
}
