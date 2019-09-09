<?php

namespace Tests\Unit\Application\Cart\Query;

use Dogadamycie\Application\Cart\Query\Cart;
use Dogadamycie\Application\Product\Query\Product;
use Dogadamycie\Domain\{
    Common\Currency,
    Common\Id
};
use Tests\TestCase;

class CartTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\Query\Cart::totalProductsPrice
     */
    public function ensureTotalProductsPriceCorrectlyCalculated()
    {
        $currency = 'USD';
        $firstProductPrice = 11.12;
        $secondProductPrice = 30.12;
        $expected = $firstProductPrice + $secondProductPrice;
        $firstProduct = new Product(Id::generate(), 'name', $firstProductPrice,  $currency);
        $secondProduct = new Product(Id::generate(), 'name', $secondProductPrice, $currency);

        $cartView = new Cart(Id::generate(), new Currency('USD'), [$firstProduct, $secondProduct]);

        $this->assertEquals($expected, $cartView->totalProductsPrice());
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\Query\Cart::avgProductPrice
     */
    public function ensureAvgProductsCorrectlyCalculated()
    {
        $firstProductPrice = 11.12;
        $secondProductPrice = 30.12;
        $currency = 'USD';
        $expected = ($firstProductPrice + $secondProductPrice) / 2;
        $firstProduct = new Product(Id::generate(), 'name', $firstProductPrice,  $currency);
        $secondProduct = new Product(Id::generate(), 'name', $secondProductPrice, $currency);

        $cartView = new Cart(Id::generate(), new Currency('USD'), [$firstProduct, $secondProduct]);

        $this->assertEquals($expected, $cartView->avgProductPrice());
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\Query\Cart::avgProductPrice
     */
    public function ensureAvgProductsPriceReturnsZeroIfCartIsEmpty()
    {
        $expected = 0;
        $cartView = new Cart(Id::generate(), new Currency('USD'), []);

        $this->assertEquals($expected, $cartView->avgProductPrice());
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\Query\Cart::totalProductsPrice
     */
    public function ensureTotalProductsPriceReturnsZeroIfCartIsEmpty()
    {
        $expected = 0;
        $cartView = new Cart(Id::generate(), new Currency('USD'), []);

        $this->assertEquals($expected, $cartView->totalProductsPrice());
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\Query\Cart::jsonSerialize
     */
    public function ensureCorrectJsonReturned()
    {
        $id = Id::generate();
        $currency = 'USD';
        $firstProductPrice = 11.12;
        $secondProductPrice = 30.12;
        $avgProductPrice = ($firstProductPrice + $secondProductPrice) / 2;
        $firstProduct = new Product(Id::generate(), 'name', $firstProductPrice,  $currency);
        $secondProduct = new Product(Id::generate(), 'name', $secondProductPrice, $currency);
        $expected = [
            'id' => (string)$id,
            'products' => [$firstProduct, $secondProduct],
            'currency' => $currency,
            'total_products_price' => $firstProductPrice + $secondProductPrice,
            'avg_product_price' => $avgProductPrice
        ];

        $cartView = new Cart($id, $currency, [$firstProduct, $secondProduct]);
        $this->assertSame($expected, $cartView->jsonSerialize());
    }
}