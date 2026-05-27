<?php

namespace App\Tests\Functional\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private static ?\Symfony\Bundle\FrameworkBundle\KernelBrowser $client = null;
    private ?EntityManagerInterface $em = null;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::$client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    protected function tearDown(): void
    {
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);

        parent::tearDown();
    }

    public function testLoginPageReturns200(): void
    {
        self::$client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
    }

    public function testLoginPageContainsLoginForm(): void
    {
        $crawler = self::$client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('input[name="_username"]');
        $this->assertSelectorExists('input[name="_password"]');
        $this->assertSelectorExists('button[type="submit"]');
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $crawler = self::$client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        self::$client->request('POST', '/login', [
            '_username' => 'nonexistent@example.com',
            '_password' => 'wrongpassword',
            '_csrf_token' => $crawler->filter('input[name="_csrf_token"]')->attr('value'),
        ]);

        $this->assertResponseRedirects('/login');
    }

    public function testLoginPageShowsSignInHeading(): void
    {
        self::$client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Please sign in', self::$client->getResponse()->getContent());
    }
}
