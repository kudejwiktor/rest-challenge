<?php

namespace Tests\Unit\Domain\Cart;

use Dogadamycie\Domain\Cart\Cart;
use Dogadamycie\Domain\Cart\CartFactory;
use Dogadamycie\Domain\Common\Currency;
use Dogadamycie\Domain\Common\Id;
use Dogadamycie\Domain\Common\Price;
use Dogadamycie\Domain\Product\Product;
use Dogadamycie\Domain\Product\ProductFactory;
use Tests\TestCase;

/**
 * Class CartFactoryTest
 * @package Tests\Unit\Domain\Cart
 */
class CartFactoryTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\CartFactory::fromArray
     */
    public function ensureFromArrayCreatesCartWithProducts()
    {
        $rawCart = $this->rawData();
        $cartFactory = new CartFactory(new ProductFactory());
        $expected = new Cart(Id::fromString($rawCart['id']), new Currency($rawCart['currency_iso_code']));
        $rawProducts = $rawCart['products'];
        foreach ($rawProducts as $product) {
            $expected->addProduct(
                new Product(
                    Id::fromString($product['id']),
                    $product['name'],
                    new Price($product['price'], new Currency($product['currency_iso_code'])),
                    Id::fromString($product['cart_id'])
                )
            );
        }

        $this->assertEquals($expected, $cartFactory->fromArray($rawCart));
    }

    /**
     * @return array
     */
    private function rawData()
    {
        return [
            "id" => "71d34d0e-2780-4928-9bf9-8f6aa66c31a8",
            "currency_iso_code" => "USD",
            "products" => [
                [
                    "id" => "05d1f0ff-b6c8-4d34-8408-6a01ffb61841",
                    "cart_id" => "71d34d0e-2780-4928-9bf9-8f6aa66c31a8",
                    "name" => "Product ForestGreen",
                    "price" => 173.78,
                    "currency_iso_code" => "USD",
                ],
                [
                    "id" => "201f6f90-3d62-4c10-a456-7ec17e515a6b",
                    "cart_id" => "71d34d0e-2780-4928-9bf9-8f6aa66c31a8",
                    "name" => "Product HotPink",
                    "price" => 57.27,
                    "currency_iso_code" => "USD",
                ],
            ]
        ];
    }
}