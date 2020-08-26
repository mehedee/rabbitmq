<?php


namespace app\components\queue\workers\Spec;

use PhpAmqpLib\Message\AMQPMessage;

interface QueueWorkerInterface
{
    public function process();
    public static function task(AMQPMessage $message);
}
