<?php


namespace app\components\queue\publishers\Spec;

trait QueuePublisherTrait
{
    public function process(): void
    {
        $messages = $this->prepareMessage();
        parent::publish($messages);
    }
}
