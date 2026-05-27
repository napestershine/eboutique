<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $category = new Category();
        $this->assertNull($category->getId());
        $this->assertNull($category->getTitle());
        $this->assertNull($category->getParent());
        $this->assertInstanceOf(ArrayCollection::class, $category->getChildren());
        $this->assertCount(0, $category->getChildren());
        $this->assertInstanceOf(ArrayCollection::class, $category->getProducts());
        $this->assertCount(0, $category->getProducts());
    }

    public function testSetTitleAndGetTitle(): void
    {
        $category = new Category();
        $result = $category->setTitle('Electronics');
        $this->assertSame($category, $result);
        $this->assertSame('Electronics', $category->getTitle());
    }

    public function testSetParentAndGetParent(): void
    {
        $parent = new Category();
        $parent->setTitle('Parent');
        $child = new Category();
        $result = $child->setParent($parent);
        $this->assertSame($child, $result);
        $this->assertSame($parent, $child->getParent());
    }

    public function testSetParentAcceptsNull(): void
    {
        $parent = new Category();
        $child = new Category();
        $child->setParent($parent);
        $child->setParent(null);
        $this->assertNull($child->getParent());
    }

    public function testAddChild(): void
    {
        $parent = new Category();
        $child = new Category();
        $result = $parent->addChild($child);
        $this->assertSame($parent, $result);
        $this->assertCount(1, $parent->getChildren());
        $this->assertTrue($parent->getChildren()->contains($child));
    }

    public function testRemoveChild(): void
    {
        $parent = new Category();
        $child = new Category();
        $parent->addChild($child);
        $parent->removeChild($child);
        $this->assertCount(0, $parent->getChildren());
    }

    public function testRemoveChildThatDoesNotExistDoesNotError(): void
    {
        $parent = new Category();
        $child = new Category();
        $parent->removeChild($child);
        $this->assertCount(0, $parent->getChildren());
    }

    public function testAddProduct(): void
    {
        $category = new Category();
        $product = new Product();
        $result = $category->addProduct($product);
        $this->assertSame($category, $result);
        $this->assertCount(1, $category->getProducts());
        $this->assertTrue($category->getProducts()->contains($product));
    }

    public function testRemoveProduct(): void
    {
        $category = new Category();
        $product = new Product();
        $category->addProduct($product);
        $category->removeProduct($product);
        $this->assertCount(0, $category->getProducts());
    }

    public function testRemoveProductThatDoesNotExistDoesNotError(): void
    {
        $category = new Category();
        $product = new Product();
        $category->removeProduct($product);
        $this->assertCount(0, $category->getProducts());
    }

    public function testToStringReturnsTitle(): void
    {
        $category = new Category();
        $category->setTitle('Books');
        $this->assertSame('Books', (string) $category);
    }
}
