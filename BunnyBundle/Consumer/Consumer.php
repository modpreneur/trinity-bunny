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
 *
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
     * @param Producer        $producer
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
     * @param string  $queueName
     */
    abstract public function consume(Message $message, string $queueName);


    /**
     * Start reading messages from rabbit.
     *
     * @param string $queueName
     * @param int    $maximumNumberOfMessages
     */
    public function startConsuming(string $queueName, int $maximumNumberOfMessages = 0)
    {
        $this->setup->setUp();
        $channel = $this->setup->getChannel();

        $count = 0;

        $channel->consume(function (Message $message, Channel $channel) use (
            &$count,
            $maximumNumberOfMessages,
            $queueName
        ) {
            try {
                $this->consume($message, $queueName);
                $channel->ack($message);
            } catch (\Exception $e) {
                $channel->nack($message, false, false); //discard the message
            }

            $count++;

            if ($maximumNumberOfMessages !== 0 && $count >= $maximumNumberOfMessages) {
                //maximum number of messages was read; could make errors if the external bunny bundle uses some __destruct() magic
                exit(0);
            }
        }, $queueName, '', false, false, false, false);

        $this->setup->getClient()->run();
    }
}
