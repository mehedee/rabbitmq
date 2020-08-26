<?php


namespace app\components\queue\publishers\Spec;

interface QueuePublisherInterface
{
    public function process();
}
