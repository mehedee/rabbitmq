<?php


namespace app\components\queue\rabbitmq;

use PhpAmqpLib\Message\AMQPMessage;
use yii\helpers\Json;

class Publisher extends RabbitMq
{
    protected $exchange;

    public function __construct(string $exchange, string $queue)
    {
        parent::__construct($exchange, $queue);

    }

    public function publish(array $messages): void
    {
        $connection = $this->createConnection();
        $channel = $this->initializeChannel($connection);
        $channel->basic_qos(
            null,   #prefetch size - prefetch window size in octets, null meaning "no specific limit"
            1,      #prefetch count - prefetch window in terms of whole messages
            null    #global - global=null to mean that the QoS settings should apply per-consumer, global=true to mean that the QoS settings should apply per-channel
        );
        $message = $this->generateMessage($messages);
        $channel->basic_publish($message, $this->exchange, $this->routingKey);
        $this->closeConnection($connection, $channel);
    }

    private function generateMessage(array $messages)
    {
        return new AMQPMessage(Json::encode($messages), ["delivery_mode" => 2]);
    }
}
