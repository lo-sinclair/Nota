<?php

namespace App\Tests\Kernel\Repository;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ProductRepositoryTest extends KernelTestCase
{
	use ResetDatabase;
	use Factories;
    public function testFindNewest(): void
    {
	    ProductFactory::createOne(['name' => 'Product Title']);
		ProductFactory::createMany(7);

	    $productRepository = static::getContainer()->get( ProductRepository::class );

		$products = $productRepository->findNewest();

		$dates = [];
		foreach ($products as $product) {
			$dates[] = $product->getCreateAt();
		}

	    $this->assertCount(4, $products);
		$this->assertSame(max($dates), $products[0]->getCreateAt());

    }

	public function testFindPromo(): void
	{
		ProductFactory::createMany(2, ['promo' => true]);
		ProductFactory::createMany(7, ['promo' => false]);

		$productRepository = static::getContainer()->get( ProductRepository::class );

		$products = $productRepository->findPromo();

		$dates = [];
		foreach ($products as $product) {
			$dates[] = $product->getCreateAt();
		}

		$this->assertTrue($products[0]->isPromo());
		$this->assertCount(2, $products);
		$this->assertSame(max($dates), $products[0]->getCreateAt());
	}



	public function testFindByCategory(): void
	{
		$products = ProductFactory::createMany(2, [ 'category' => CategoryFactory::createOne(['name' => 'Cat1'])]);
		$category = $products[0]->getCategory();
		ProductFactory::createMany( 7 );

		$productRepository = static::getContainer()->get( ProductRepository::class );

		$products = $productRepository->findByCategory($category);

		$this->assertCount(2, $products);
		$this->assertSame($category, $products[0]->getCategory());
		$this->assertSame('Cat1', $products[0]->getCategory()->getName());
	}
}

