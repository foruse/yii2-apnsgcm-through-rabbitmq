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
        $channel->queue_declare($amqp->queueName, false, true, false, false);
        $message = $channel->basic_get($amqp->queueName);
        $body = json_decode($message->body);

        if (!$body) {
            $channel->basic_ack($message->delivery_info['delivery_tag']);
            Yii::error('Has no body. json: ' . json_last_error_msg(), 'ApnsGcm');
            return false;
        }

        // Log data
        Yii::info($message->body, 'ApnsGcm');

        /* @var $apnsGcm \bryglen\apnsgcm\ApnsGcm */
        $result = Yii::$app->apnsGcm->send(
            $body->type,
            (array)$body->token,
            $body->text,
            $body->payloadData,
            $body->args
        );

        $channel->basic_ack($message->delivery_info['delivery_tag']);
        $channel->close();

        return $result;
    }
}
