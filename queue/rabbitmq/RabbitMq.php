<?php


namespace app\components\queue\rabbitmq;

use app\components\Settings;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMq
{
    protected $exchange;
    protected $queue;
    protected $routingKey;
    protected $connectionTimeOut = 5.0;

    public function __construct(string $exchange, string $queue)
    {
        $this->exchange = $exchange;
        $this->queue = $queue;
        $this->routingKey = 'route.'.$queue;
    }

    protected function createConnection()
    {
        try {
            return new AMQPStreamConnection(
                getenv('host'),
                getenv('port'),
                getenv('user'),
                getenv('password'),
                getenv('vhost'),
            );
        } catch (Exception $e) {
//            $response = $e->getResponse();
            return false;
        }
    }

    protected function initializeChannel(AMQPStreamConnection $connection)
    {
        $channel = $connection->channel();
        $channel->exchange_declare($this->exchange, 'direct', false, true, false);
        $channel->queue_declare(
            $this->queue,    #queue name, the same as the sender
            false,           #passive
            true,            #durable
            false,           #exclusive
            false            #autodelete
        );
        $channel->queue_bind($this->queue, $this->exchange, $this->routingKey);

        return $channel;
    }

    protected function closeConnection($connection, $channel)
    {
        $channel->close();
        $connection->close();
    }
}
