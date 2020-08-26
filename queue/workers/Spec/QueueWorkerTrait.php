<?php


namespace app\components\queue\workers\Spec;

/**
 * Trait QueueWorkerTrait
 * @package app\components\queue\workers\Spec
 */
trait QueueWorkerTrait
{
    public function __construct()
    {
        parent::__construct($this->exchange, $this->queue);
    }

    public function process()
    {
        $this->listen('task');
    }
}
