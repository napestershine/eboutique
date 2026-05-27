<?php

namespace App\Tests\Functional\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    private ?EntityManagerInterface $em = null;
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
        self::$client->request('GET', '/categories');
        $this->assertResponseIsSuccessful();
    }

    public function testListShowsCategories(): void
    {
        $category = new Category();
        $category->setTitle('Books');
        $this->em->persist($category);
        $this->em->flush();

        self::$client->request('GET', '/categories');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Books', self::$client->getResponse()->getContent());
    }

    public function testListEmptyShowsFlash(): void
    {
        self::$client->request('GET', '/categories');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Unable to find categories in the database', self::$client->getResponse()->getContent());
    }

    public function testAddFormIsDisplayed(): void
    {
        $crawler = self::$client->request('GET', '/category/add');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[type="submit"][value="Submit"]');
    }

    public function testAddCategorySuccessfully(): void
    {
        $crawler = self::$client->request('GET', '/category/add');

        $form = $crawler->selectButton('Submit')->form([
            'category_form[title]' => 'New Category',
        ]);
        self::$client->submit($form);

        $this->assertResponseRedirects('/categories');
        self::$client->followRedirect();
        $this->assertStringContainsString('New Category', self::$client->getResponse()->getContent());
    }

    public function testAddCategoryWithParent(): void
    {
        $parent = new Category();
        $parent->setTitle('Parent Category');
        $this->em->persist($parent);
        $this->em->flush();

        $crawler = self::$client->request('GET', '/category/add');

        $form = $crawler->selectButton('Submit')->form([
            'category_form[title]' => 'Child Category',
            'category_form[parent]' => $parent->getId(),
        ]);
        self::$client->submit($form);

        $this->assertResponseRedirects('/categories');
        self::$client->followRedirect();
        $this->assertStringContainsString('Child Category', self::$client->getResponse()->getContent());
    }

    public function testShowCategory(): void
    {
        $category = new Category();
        $category->setTitle('Show Category');
        $this->em->persist($category);
        $this->em->flush();

        self::$client->request('GET', '/category/show/' . $category->getId());
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Show Category', self::$client->getResponse()->getContent());
    }

    public function testShowNonExistentCategoryShowsFlash(): void
    {
        self::$client->request('GET', '/category/show/99999');
        $this->assertResponseRedirects('/categories');
        self::$client->followRedirect();
        $this->assertStringContainsString('Unable to find this category', self::$client->getResponse()->getContent());
    }

    public function testEditFormIsDisplayed(): void
    {
        $category = new Category();
        $category->setTitle('Editable Category');
        $this->em->persist($category);
        $this->em->flush();

        $crawler = self::$client->request('GET', '/category/edit/' . $category->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testEditCategorySuccessfully(): void
    {
        $category = new Category();
        $category->setTitle('Original Title');
        $this->em->persist($category);
        $this->em->flush();

        $crawler = self::$client->request('GET', '/category/edit/' . $category->getId());

        $form = $crawler->selectButton('Submit')->form([
            'category_form[title]' => 'Updated Title',
        ]);
        self::$client->submit($form);

        $this->assertResponseRedirects('/categories');
        self::$client->followRedirect();
        $this->assertStringContainsString('Updated Title', self::$client->getResponse()->getContent());
    }

    public function testEditNonExistentCategoryRedirects(): void
    {
        self::$client->request('GET', '/category/edit/99999');
        $this->assertResponseRedirects('/categories');
    }

    public function testRemoveCategory(): void
    {
        $category = new Category();
        $category->setTitle('To Remove');
        $this->em->persist($category);
        $this->em->flush();
        $id = $category->getId();

        self::$client->request('GET', '/category/remove/' . $id);
        $this->assertResponseRedirects('/categories');

        self::$client->followRedirect();
        $this->assertStringContainsString('Category removed successfully', self::$client->getResponse()->getContent());
    }

    public function testRemoveNonExistentCategoryRedirects(): void
    {
        self::$client->request('GET', '/category/remove/99999');
        $this->assertResponseRedirects('/categories');
    }
}
