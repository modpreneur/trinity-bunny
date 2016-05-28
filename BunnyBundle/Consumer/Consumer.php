<?php
/**
 * Created by PhpStorm.
 * User: Jakub Fajkus
 * Date: 19.03.16
 * Time: 13:10
 */

namespace Trinity\Bundle\BunnyBundle\Consumer;


use Bunny\Channel;
use Bunny\Message;
use Trinity\Bundle\BunnyBundle\Producer\Producer;
use Trinity\Bundle\BunnyBundle\Setup\BaseRabbitSetup;

/**
 * Class Consumer
 * @package Trinity\Bundle\BunnyBundle\Consumer
 */
abstract class Consumer
{
    /**
     * @var BaseRabbitSetup
     */
    protected $setup;


    /**
     * @var Producer
     */
    protected $producer;


    /**
     * ServerConsumer constructor.
     *
     * @param BaseRabbitSetup $setup
     * @param Producer $producer
     */
    public function __construct(BaseRabbitSetup $setup, Producer $producer = null)
    {
        $this->setup = $setup;
        $this->producer = $producer;
    }


    /**
     * Consume message
     *
     * @param Message $message
     *
     * @param string $sourceQueue Source queue
     */
    abstract public function consume(Message $message, string $sourceQueue);


    /**
     * Start reading messages from rabbit.
     * @throws \Exception
     */
    public function startConsuming()
    {
        $this->setup->setUp();

        foreach ($this->setup->getListeningQueues() as $listeningQueue) {
            $this->consumeQueue($listeningQueue);
        }

        $this->setup->getClient()->run();
    }


    /**
     * Set up client to consume given queue
     *
     * @param string $sourceQueue
     */
    protected function consumeQueue(string $sourceQueue)
    {
        $channel = $this->setup->getChannel();

        $channel->consume(function (Message $message, Channel $channel) use ($sourceQueue) {
            try {
                $this->consume($message, $sourceQueue);
                $channel->ack($message);
            } catch (\Exception $e) {
                $channel->nack($message, false, false); //discard the message
            }
        }, $sourceQueue, '', false, false, false, false);
    }
}
