<?php


namespace Trinity\Bundle\BunnyBundle\Producer;

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


    /**
     * Producer constructor.
     *
     * @param BaseRabbitSetup $rabbitSetup
     */
    public function __construct(BaseRabbitSetup $rabbitSetup)
    {
        $this->rabbitSetup = $rabbitSetup;
    }


    /**
     * Publish data to the queue.
     *
     * @param string $data
     * @param string $exchangeName
     *
     * @return
     */
    abstract public function publish(string $data, string $exchangeName);


    /**
     * Publish message to error message exchange
     *
     * @param string $data
     *
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
            ''
        );
    }

    /**
     * @return BaseRabbitSetup
     */
    public function getRabbitSetup()
    {
        return $this->rabbitSetup;
    }
}
