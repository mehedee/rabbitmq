<?php


namespace app\components\queue\rabbitmq;

use app\models\Log;
use app\models\Transaction;
use Exception;
use yii\helpers\Json;

class Worker extends RabbitMq
{
    public function __construct(string $exchange, string $queue)
    {
        parent::__construct($exchange, $queue);
    }

    public function listen($task)
    {
        $connection = $this->createConnection();
        $channel = $this->initializeChannel($connection);
        $this->consume($channel, $task);
        while (count($channel->callbacks)) {
            $channel->wait(null, false, $this->connectionTimeOut);
        }

        register_shutdown_function(function ($channel, $connection) {
            $this->closeConnection($connection, $channel);
        });
    }

    private function consume($channel, $callbackFunction)
    {
        $channel->basic_consume(
            $this->queue,                     #queue
            '',                               #consumer tag - Identifier for the consumer, valid within the current channel. just string
            false,                            #no local - TRUE: the server will not send messages to the connection that published them
            false,                            #no ack - send a proper acknowledgment from the worker, once we're done with a task
            false,                            #exclusive - queues may only be accessed by the current connection
            false,                            #no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
            array($this, $callbackFunction)    #callback - method that will receive the message
        );
    }
}
