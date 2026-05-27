<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private static ?\Symfony\Bundle\FrameworkBundle\KernelBrowser $client = null;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::$client = static::createClient();
    }

    public function testHomepageReturns200(): void
    {
        self::$client->request('GET', '/home');
        $this->assertResponseIsSuccessful();
    }
}
