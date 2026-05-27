<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $product = new Product();
        $this->assertNull($product->getId());
        $this->assertNull($product->getName());
        $this->assertNull($product->getPrice());
        $this->assertNull($product->getBrand());
        $this->assertNull($product->getQuantity());
        $this->assertNull($product->getCategory());
    }

    public function testSetNameAndGetName(): void
    {
        $product = new Product();
        $result = $product->setName('Laptop');
        $this->assertSame($product, $result);
        $this->assertSame('Laptop', $product->getName());
    }

    public function testSetPriceAndGetPrice(): void
    {
        $product = new Product();
        $result = $product->setPrice(999);
        $this->assertSame($product, $result);
        $this->assertSame(999, $product->getPrice());
    }

    public function testSetBrandAndGetBrand(): void
    {
        $product = new Product();
        $result = $product->setBrand('TechCo');
        $this->assertSame($product, $result);
        $this->assertSame('TechCo', $product->getBrand());
    }

    public function testSetQuantityAndGetQuantity(): void
    {
        $product = new Product();
        $result = $product->setQuantity(10);
        $this->assertSame($product, $result);
        $this->assertSame(10, $product->getQuantity());
    }

    public function testSetCategoryAndGetCategory(): void
    {
        $product = new Product();
        $category = new Category();
        $result = $product->setCategory($category);
        $this->assertSame($product, $result);
        $this->assertSame($category, $product->getCategory());
    }

    public function testSetCategoryAcceptsNull(): void
    {
        $product = new Product();
        $category = new Category();
        $product->setCategory($category);
        $product->setCategory(null);
        $this->assertNull($product->getCategory());
    }

    public function testPriceAcceptsZero(): void
    {
        $product = new Product();
        $product->setPrice(0);
        $this->assertSame(0, $product->getPrice());
    }

    public function testQuantityAcceptsZero(): void
    {
        $product = new Product();
        $product->setQuantity(0);
        $this->assertSame(0, $product->getQuantity());
    }
}
