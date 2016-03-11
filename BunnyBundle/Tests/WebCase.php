<?php


namespace Trinity\Bundle\BunnyBundle\Tests;

use  Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WebCase extends WebTestCase
{

    protected static function getKernelClass()
    {
        require_once __DIR__.'/app/AppKernel.php';
        return 'Trinity\Bundle\BunnyBundle\Tests\app\AppKernel';
    }

}