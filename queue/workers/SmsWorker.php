<?php


namespace app\components\queue\workers;


use app\components\notification\SmsService;
use app\components\queue\rabbitmq\Worker;
use app\components\queue\workers\Spec\QueueWorkerTrait;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use yii\helpers\Json;

class SmsWorker extends Worker implements Spec\QueueWorkerInterface
{
    use QueueWorkerTrait;
    protected $queue    = 'smsNotification';
    protected $exchange = 'exchangeName';

    public static function task(AMQPMessage $message)
    {
        $message = (object)Json::decode($message->body);
        try {
                //$success = do your operations here;
            if ($success) {
                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            }
        }
        catch (Exception $exception) {

        }
    }
}