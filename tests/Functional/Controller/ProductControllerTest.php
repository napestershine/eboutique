<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private ?EntityManagerInterface $em = null;
    private ?Category $category = null;
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

        $this->category = new Category();
        $this->category->setTitle('Electronics');
        $this->em->persist($this->category);
        $this->em->flush();
    }

    protected function tearDown(): void
    {
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);

        parent::tearDown();
    }

    public function testListReturns200(): void
    {
        self::$client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testListShowsProducts(): void
    {
        $product = new Product();
        $product->setName('Test Laptop');
        $product->setPrice(999);
        $product->setBrand('TechCo');
        $product->setQuantity(5);
        $product->setCategory($this->category);
        $this->em->persist($product);
        $this->em->flush();

        self::$client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Test Laptop', self::$client->getResponse()->getContent());
    }

    public function testListShowsEmptyMessageWhenNoProducts(): void
    {
        self::$client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testAddFormIsDisplayed(): void
    {
        $crawler = self::$client->request('GET', '/add');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[type="submit"][value="Submit"]');
    }

    public function testAddProductSuccessfully(): void
    {
        $crawler = self::$client->request('GET', '/add');

        $form = $crawler->selectButton('Submit')->form([
            'product_form[name]' => 'New Product',
            'product_form[price]' => '100',
            'product_form[brand]' => 'Brand',
            'product_form[quantity]' => '10',
            'product_form[category]' => $this->category->getId(),
        ]);
        self::$client->submit($form);

        $this->assertResponseRedirects('/');
        self::$client->followRedirect();
        $this->assertStringContainsString('New Product', self::$client->getResponse()->getContent());
    }

    public function testShowProduct(): void
    {
        $product = new Product();
        $product->setName('Show Product');
        $product->setPrice(500);
        $product->setBrand('ShowBrand');
        $product->setQuantity(3);
        $product->setCategory($this->category);
        $this->em->persist($product);
        $this->em->flush();

        self::$client->request('GET', '/show/' . $product->getId());
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Show Product', self::$client->getResponse()->getContent());
        $this->assertStringContainsString('500', self::$client->getResponse()->getContent());
        $this->assertStringContainsString('ShowBrand', self::$client->getResponse()->getContent());
    }

    public function testShowNonExistentProductShowsFlash(): void
    {
        self::$client->request('GET', '/show/99999');
        $this->assertResponseRedirects('/');
        self::$client->followRedirect();
        $this->assertStringContainsString('Unable to find the product in the database', self::$client->getResponse()->getContent());
    }

    public function testEditFormIsDisplayed(): void
    {
        $product = new Product();
        $product->setName('Editable Product');
        $product->setPrice(200);
        $product->setBrand('EditBrand');
        $product->setQuantity(7);
        $product->setCategory($this->category);
        $this->em->persist($product);
        $this->em->flush();

        $crawler = self::$client->request('GET', '/edit/' . $product->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testEditProductSuccessfully(): void
    {
        $product = new Product();
        $product->setName('Original Name');
        $product->setPrice(100);
        $product->setBrand('OrigBrand');
        $product->setQuantity(5);
        $product->setCategory($this->category);
        $this->em->persist($product);
        $this->em->flush();

        $crawler = self::$client->request('GET', '/edit/' . $product->getId());

        $form = $crawler->selectButton('Submit')->form([
            'product_form[name]' => 'Updated Name',
            'product_form[price]' => '150',
            'product_form[brand]' => 'UpdatedBrand',
            'product_form[quantity]' => '8',
            'product_form[category]' => $this->category->getId(),
        ]);
        self::$client->submit($form);

        $this->assertResponseRedirects('/');
        self::$client->followRedirect();
        $this->assertStringContainsString('Updated Name', self::$client->getResponse()->getContent());
    }

    public function testRemoveProduct(): void
    {
        $product = new Product();
        $product->setName('To Remove');
        $product->setPrice(10);
        $product->setBrand('Brand');
        $product->setQuantity(1);
        $product->setCategory($this->category);
        $this->em->persist($product);
        $this->em->flush();
        $id = $product->getId();

        self::$client->request('GET', '/remove/' . $id);
        $this->assertResponseRedirects('/');

        self::$client->followRedirect();
        $this->assertStringContainsString('Product removed successfully', self::$client->getResponse()->getContent());
    }
}
