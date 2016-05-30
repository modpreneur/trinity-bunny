<?php

namespace Trinity\Bundle\BunnyBundle\Consumer;

use Bunny\Message;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Trinity\Bundle\BunnyBundle\Event\RabbitMessageConsumedEvent;

/**
 * Class CommandConsumer
 *
 * @package Trinity\Bundle\BunnyBundle\Consumer
 */
class CommandConsumer extends Consumer
{
    /** @var  EventDispatcher */
    protected $eventDispatcher;

    /**
     * Consume message
     *
     * @param Message $message
     *
     * @param string  $queueName
     */
    public function consume(Message $message, string $queueName)
    {
        if ($this->eventDispatcher->hasListeners(RabbitMessageConsumedEvent::EVENT_NAME)) {
            /** @var RabbitMessageConsumedEvent $event */
            $this->eventDispatcher->dispatch(
                RabbitMessageConsumedEvent::EVENT_NAME,
                new RabbitMessageConsumedEvent($message, $queueName)
            );
        }
    }
}
