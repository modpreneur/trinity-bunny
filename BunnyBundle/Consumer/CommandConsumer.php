<?php

namespace Trinity\Bundle\BunnyBundle\Consumer;

use Bunny\Message;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Trinity\Bundle\BunnyBundle\Event\RabbitMessageConsumedEvent;
use Trinity\Bundle\BunnyBundle\Setup\BaseRabbitSetup;

/**
 * Class CommandConsumer
 *
 * @package Trinity\Bundle\BunnyBundle\Consumer
 */
class CommandConsumer extends Consumer
{
    /** @var  EventDispatcherInterface */
    protected $eventDispatcher;


    /**
     * CommandConsumer constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param BaseRabbitSetup $setup
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, BaseRabbitSetup $setup)
    {
        $this->eventDispatcher = $eventDispatcher;
        parent::__construct($setup);
    }


    /**
     * Consume message
     *
     * @param Message $message
     *
     * @param string  $queueName
     */
    public function consume(Message $message, string $queueName)
    {
        /** @var RabbitMessageConsumedEvent $event */
        $this->eventDispatcher->dispatch(
            RabbitMessageConsumedEvent::EVENT_NAME,
            new RabbitMessageConsumedEvent($message, $queueName)
        );
    }
}
