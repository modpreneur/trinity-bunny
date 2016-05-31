<?php

namespace Trinity\Bundle\BunnyBundle\Event;

use Bunny\Message;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class RabbitMessageConsumedEvent
 *
 * @package Trinity\Bundle\BunnyBundle\Event
 */
class RabbitMessageConsumedEvent extends Event
{
    const EVENT_NAME = 'trinity.bunny.rabbitMessageConsumed';

    /** @var  \Bunny\Message */
    protected $message;

    /** @var  string */
    protected $sourceQueue;

    /**
     * RabbitMessageConsumedEvent constructor.
     *
     * @param Message $message
     * @param string  $sourceQueue
     */
    public function __construct(Message $message, $sourceQueue)
    {
        $this->message = $message;
        $this->sourceQueue = $sourceQueue;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getSourceQueue()
    {
        return $this->sourceQueue;
    }

    /**
     * @param string $sourceQueue
     */
    public function setSourceQueue($sourceQueue)
    {
        $this->sourceQueue = $sourceQueue;
    }
}

