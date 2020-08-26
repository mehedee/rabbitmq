<?php


namespace app\components\queue\publishers;

use app\components\queue\publishers\Spec\QueuePublisherTrait;
use app\components\queue\rabbitmq\Publisher;

class SmsPublisher extends Publisher implements Spec\QueuePublisherInterface
{
    use QueuePublisherTrait;

    protected $queue    = 'smsNotification';
    protected $exchange = 'exchangeName';
    private $message;
    private $number;

    public function __construct(string $message, string $sendToNumber)
    {
        parent::__construct($this->exchange, $this->queue);
        $this->message = $message;
        $this->number = $sendToNumber;
    }


    private function prepareMessage()
    {
        return [
            'sendTo'  => $this->number,
            'message' => urlencode($this->message)
        ];
    }
}
