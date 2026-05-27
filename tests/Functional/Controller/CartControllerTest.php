<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    private ?EntityManagerInterface $em = null;
    private ?Product $product = null;
    private static ?\Symfony\Bundle\FrameworkBundle\KernelBrowser $client = null;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        self::$client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $category = new Category();
        $category->setTitle('Cart Category');
        $this->em->persist($category);

        $this->product = new Product();
        $this->product->setName('Cart Product');
        $this->product->setPrice(50);
        $this->product->setBrand('CartBrand');
        $this->product->setQuantity(10);
        $this->product->setCategory($category);
        $this->em->persist($this->product);
        $this->em->flush();
    }

    protected function tearDown(): void
    {
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);

        parent::tearDown();
    }

    public function testCartListReturns200(): void
    {
        self::$client->request('GET', '/cart');
        $this->assertResponseIsSuccessful();
    }

    public function testAddToCartRedirectsToCartList(): void
    {
        self::$client->request('GET', '/cart/add/' . $this->product->getId());
        $this->assertResponseRedirects('/cart');
    }

    public function testAddToCartShowsFlashMessage(): void
    {
        self::$client->followRedirects(true);
        self::$client->request('GET', '/cart/add/' . $this->product->getId());
        $this->assertStringContainsString('Product added to cart', self::$client->getResponse()->getContent());
    }

    public function testAddAndRemoveFromCart(): void
    {
        // Add product to cart
        self::$client->request('GET', '/cart/add/' . $this->product->getId());
        $this->assertResponseRedirects('/cart');

        // Remove product from cart
        self::$client->request('GET', '/cart/remove/' . $this->product->getId());
        $this->assertResponseRedirects('/cart');
    }

    public function testCartListWithItems(): void
    {
        // Add product to cart
        self::$client->request('GET', '/cart/add/' . $this->product->getId());

        // View cart
        self::$client->followRedirects(true);
        self::$client->request('GET', '/cart');
        $this->assertResponseIsSuccessful();
    }

    public function testCartListEmptyShowsNoErrors(): void
    {
        self::$client->request('GET', '/cart');
        $this->assertResponseIsSuccessful();
    }
}
