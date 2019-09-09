<?php

namespace Tests\Unit\Application\Product\Query;

use Dogadamycie\Application\Product\Query\Product;
use Dogadamycie\Domain\Common\Id;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Application\Product\Query\Product::jsonSerialize
     */
    public function ensureCorrectJsonReturned()
    {
        $id = Id::generate();
        $name = 'name';
        $price = 11.22;
        $currency = 'USD';
        $product = new Product($id, $name, $price, $currency);
        $expected = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'currency' => $currency
        ];

        $this->assertEquals($expected, $product->jsonSerialize());
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Product\Query\Product::jsonSerialize
     * @covers \Dogadamycie\Application\Product\Query\Product::getId
     * @covers \Dogadamycie\Application\Product\Query\Product::getName
     * @covers \Dogadamycie\Application\Product\Query\Product::getPrice
     * @covers \Dogadamycie\Application\Product\Query\Product::getCurrency
     */
    public function ensureGettersReturnsCorrectValue()
    {
        $id = Id::generate();
        $name = 'name';
        $price = 11.22;
        $currency = 'USD';
        $product = new Product($id, $name, $price, $currency);

        $this->assertSame((string)$id, $product->getId());
        $this->assertSame($name, $product->getName());
        $this->assertSame($price, $product->getPrice());
        $this->assertSame($currency, $product->getCurrency());
    }
}