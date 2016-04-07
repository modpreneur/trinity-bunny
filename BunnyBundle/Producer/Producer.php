<?php


namespace Trinity\Bundle\BunnyBundle\Consumer;


use Trinity\Bundle\BunnyBundle\Setup\BaseRabbitSetup;

/**
 * Class Producer
 * @package Trinity\Bundle\BunnyBundle\Consumer
 */
abstract class Producer
{
    /**
     * @var BaseRabbitSetup
     */
    protected $rabbitSetup;


    public function __construct(BaseRabbitSetup $rabbitSetup)
    {
        $this->rabbitSetup = $rabbitSetup;
    }

    /**
     * Publish data to the queue.
     *
     * @param string $data
     */
    abstract public function publish(string $data);


    /**
     * Publish message to error message exchange
     *
     * @param string $data
     * @throws \Exception
     */
    public function publishErrorMessage(string $data)
    {
        $this->rabbitSetup->setUp();

        $channel = $this->rabbitSetup->getChannel();

        $channel->publish(
            $data,
            [],
            $this->rabbitSetup->getOutputErrorMessagesExchange(),
            ""
        );
    }

}