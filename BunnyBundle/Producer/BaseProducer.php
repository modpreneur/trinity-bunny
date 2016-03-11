<?php


namespace Trinity\Bundle\BunnyBundle\Producer;


use Bunny\Channel;
use Bunny\Client;


/**
 * Class BaseProducer
 * @package Trinity\Bundle\BunnyBundle\Producer
 */
class BaseProducer
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Channel
     */
    protected $channel = NULL;

    /** @var  string */
    protected $exchange = 'necktie_exchange';


    /**
     * Producer constructor.
     * @param Client $client
     * @param string $exchange
     */
    public function __construct(Client $client, $exchange = null)
    {
        $this->client   = $client;
        $this->exchange = $exchange;
    }


    /**
     * @param string $queueName
     * @param string $exchange
     * @throws \Exception
     */
    protected function connectToQueue($queueName, $exchange){

        $queueName = "queue_" . $queueName;

        if( NULL === $this->channel ){

            try{
                $this->client->connect();
            }catch(\Exception $ex){
                // @todo 
            }

            $this->channel = $this->client->channel();
            $this->channel->queueDeclare($queueName);
            $this->channel->exchangeDeclare($exchange);
            $this->channel->queueBind($queueName, $exchange);
        }
    }


    /**
     * @param string $queueName
     * @param mixed $data
     * @param string $exchange
     */
    public function publish($queueName, $data, $exchange = "necktie_exchange"){
        $this->connectToQueue($queueName, $exchange);
        $this->channel->publish(serialize($data), [], $exchange, '', false);
    }


    /**
     * @param string $queueName
     * @param string $exchange
     */
    public function quitConsumer($queueName, $exchange = "necktie_exchange"){
        $this->connectToQueue($queueName, $exchange);
        $this->channel->publish("quit", [], $exchange);
        $this->client->disconnect();
    }


}