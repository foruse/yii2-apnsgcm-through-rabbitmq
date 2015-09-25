<?php

namespace gbksoft\apnsGcm;

use Yii;
use PhpAmqpLib\Message\AMQPMessage;

class ApnsGcm extends \bryglen\apnsgcm\ApnsGcm
{
    /**
     * add to queue a push notification depending on type
     * @param $type
     * @param $token
     * @param $text
     * @param array $payloadData
     * @param array $args
     * @return boolean
     */
    public function addToQueue($type, $token, $text, $payloadData = [], $args = [])
    {
        $amqp = Yii::$app->amqp;
        $channel = $amqp->getChannel();
        $channel->queue_declare('apns-gcm', false, true, false, false);

        $msg = new AMQPMessage(
            json_encode([
                'type' => $type,
                'token' => $token,
                'text' => $text,
                'payloadData' => $payloadData,
                'args' => $args,
            ]),
            [
                'content_type' => 'application/json',
                'delivery_mode' => 2
            ]
        );

        $result = $channel->basic_publish($msg, '', 'apns-gcm');
        $channel->close();

        return $result;
    }
}
