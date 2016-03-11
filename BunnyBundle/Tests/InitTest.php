<?php


namespace Trinity\Bundle\BunnyBundle\Tests;


use Symfony\Component\DependencyInjection\ContainerInterface;


class InitTest extends WebCase
{

    public function testI(){

        /** @var ContainerInterface $c */
        $c = self::createClient()->getContainer();
        $c->get('necktie.bunny.client');

        $this->assertTrue(true);
    }

}