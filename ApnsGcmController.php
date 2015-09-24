<?php

namespace gbksoft\apnsGcm;

use webtoucher\amqp\controllers\AmqpConsoleController;
use PhpAmqpLib\Message\AMQPMessage;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ApnsGcmController extends AmqpConsoleController
{
    public function actionRun()
    {
    }
    
    public function actionSend()
    {
        $channel = $this->getChannel();
        $channel->queue_declare('task_queue', false, true, false, false);

        $data = fgets(STDIN);
        $msg = new AMQPMessage($data, ['delivery_mode' => 2]);
        $channel->basic_publish($msg, '', 'task_queue');

        echo " [x] Sent ", $data, "\n";

        $channel->close();
        $this->connection->close();
    }
    
    public function actionReceive()
    {
        $channel = $this->getChannel();
        $channel->queue_declare('task_queue', false, true, false, false);
        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

        $channel->basic_qos(null, 1, null);

        
        $channel->basic_consume('task_queue', '', false, false, false, false, [$this->className(), 'receiveCallback']);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $this->connection->close();
    }
    
    public static function receiveCallback($msg)
    {
        echo " [x] Received ", $msg->body, "\n";
        sleep(substr_count($msg->body, '.'));
        echo " [x] Done", "\n";
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }
}
