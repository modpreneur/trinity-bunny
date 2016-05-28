<?php
/**
 * Created by PhpStorm.
 * User: Jakub Fajkus
 * Date: 19.03.16
 * Time: 11:17
 */

namespace Trinity\Bundle\BunnyBundle\Setup;

use Bunny\Channel;
use Bunny\Client;

/**
 * Class BaseRabbitSetup
 *
 * @package Trinity\Bundle\BunnyBundle\Setup
 */
class BaseRabbitSetup
{
    /**
     * @var Client
     */
    protected $client;


    /**
     * @var Channel
     */
    protected $channel;


    /**
     * @var string[]
     */
    protected $listeningQueues;


    /**
     * @var string
     */
    protected $outputMessagesExchange;


    /**
     * BaseRabbitSetup constructor.
     *
     * @param Client $client
     * @param string[] $listeningQueues
     * @param string $outputMessagesExchange
     */
    public function __construct(
        Client $client,
        array $listeningQueues,
        string $outputMessagesExchange
    ) {
        $this->client = $client;
        $this->listeningQueues = $listeningQueues;
        $this->outputMessagesExchange = $outputMessagesExchange;
    }


    /**
     * Set up the rabbit queue, exchanges and so.
     *
     * @throws \Exception
     */
    public function setUp()
    {
        $this->createChannel();
    }


    /**
     * Get name of the queue which will be listening to.
     *
     * @return string[]
     */
    public function getListeningQueues()
    {
        return $this->listeningQueues;
    }


    /**
     * Used in Producer
     *
     * @return string
     */
    public function getOutputErrorMessagesExchange()
    {
        return $this->getOutputMessagesExchangeName();
    }


    /**
     * Create channel to the rabbit.
     *
     * @throws \Exception
     */
    protected function createChannel()
    {
        if (null === $this->channel) {
            $this->client->connect();
            $this->channel = $this->client->channel();
        }
    }


    /**
     * Get channel which will be used for publishing/listening messages.
     *
     * @return \Bunny\Channel
     * @throws \Exception
     */
    public function getChannel()
    {
        $this->createChannel();

        return $this->channel;
    }


    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }


    /**
     * Disconnect from the rabbit server.
     */
    public function disconnect()
    {
        $this->client->disconnect();
    }


    /**
     * Get exchange name which will be used to produce messages
     *
     * @return string
     */
    public function getOutputMessagesExchangeName()
    {
        return $this->outputMessagesExchange;
    }
}
