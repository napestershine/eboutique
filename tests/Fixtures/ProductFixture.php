<?php

namespace App\Tests\Fixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $electronics = new Category();
        $electronics->setTitle('Electronics');
        $manager->persist($electronics);
        $this->addReference('category-electronics', $electronics);

        $clothing = new Category();
        $clothing->setTitle('Clothing');
        $manager->persist($clothing);
        $this->addReference('category-clothing', $clothing);

        $products = [
            ['name' => 'Laptop', 'price' => 999, 'brand' => 'TechCo', 'quantity' => 10, 'category' => $electronics],
            ['name' => 'T-Shirt', 'price' => 25, 'brand' => 'WearInc', 'quantity' => 50, 'category' => $clothing],
            ['name' => 'Phone', 'price' => 699, 'brand' => 'MobileCo', 'quantity' => 15, 'category' => $electronics],
        ];

        foreach ($products as $i => $data) {
            $product = new Product();
            $product->setName($data['name']);
            $product->setPrice($data['price']);
            $product->setBrand($data['brand']);
            $product->setQuantity($data['quantity']);
            $product->setCategory($data['category']);
            $manager->persist($product);
            $this->addReference('product-' . $i, $product);
        }

        $manager->flush();
    }
}
