<?php

namespace Tests\Unit\Domain\Product;

use Dogadamycie\Domain\Common\Currency;
use Dogadamycie\Domain\Common\Id;
use Dogadamycie\Domain\Common\Price;
use Dogadamycie\Domain\Product\Product;
use Dogadamycie\Domain\Product\ProductFactory;
use Tests\TestCase;

class ProductFactoryTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Domain\Product\ProductFactory::fromArray
     */
    public function ensureFromArrayCreatesProduct()
    {
        $productFactory = new ProductFactory();
        $rawProduct = $this->rawData();
        $expected = new Product(
            Id::fromString($rawProduct['id']),
            $rawProduct['name'],
            new Price($rawProduct['price'], new Currency($rawProduct['currency_iso_code'])),
            Id::fromString($rawProduct['cart_id'])
        );

        $this->assertEquals($expected, $productFactory->fromArray($rawProduct));
    }

    /**
     * @return array
     */
    private function rawData()
    {
        return [
            "id" => "05d1f0ff-b6c8-4d34-8408-6a01ffb61841",
            "cart_id" => "71d34d0e-2780-4928-9bf9-8f6aa66c31a8",
            "name" => "Product ForestGreen",
            "price" => 173.78,
            "currency_iso_code" => "USD",
        ];
    }
}