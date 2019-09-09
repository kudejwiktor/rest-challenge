<?php

namespace Tests\Unit\Domain\Cart;

use Dogadamycie\Domain\Cart\Cart;
use Dogadamycie\Domain\Cart\Exceptions\ProductAlreadyExistInCart;
use Dogadamycie\Domain\Cart\Exceptions\ProductNotFoundInCart;
use Dogadamycie\Domain\Product\Product;
use Dogadamycie\Domain\Common\{Currency, Price, Id};
use Dogadamycie\Domain\Cart\Exceptions\ProductDoesNotMatchCartCurrency;
use Tests\TestCase;

/**
 * Class CartTest
 * @package Tests\Unit
 */
class CartTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\Cart::addProduct
     */
    public function ensureExceptionThrownIfProductDoesNotMatchCartCurrency()
    {
        $this->expectException(ProductDoesNotMatchCartCurrency::class);
        $this->expectExceptionCode(1001);
        $product = new Product(Id::generate(), 'name', new Price(150, new Currency('EUR')));
        $cart = new Cart(
            Id::generate(),
            new Currency('USD')
        );
        $cart->addProduct($product);
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\Cart::addProduct
     */
    public function ensureProductAddedToCart()
    {
        $currency = new Currency('USD');
        $cartId = Id::generate();
        $cart = new Cart($cartId, $currency);
        $firstProduct = new Product(Id::generate(), 'first product', new Price(11.50, $currency), $cartId);
        $secondProduct = new Product(Id::generate(), 'second product', new Price(12.10, $currency), $cartId);
        $expected = [
            (string)$firstProduct->getId() => $firstProduct,
            (string)$secondProduct->getId() => $secondProduct
        ];

        $cart->addProduct($firstProduct);
        $cart->addProduct($secondProduct);

        $this->assertEquals($expected, $cart->getProducts());
        $this->assertCount(count($expected), $cart->getProducts());
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\Cart::addProduct
     */
    public function ensureSameProductCanNotBeAddedTwice()
    {
        $this->expectException(ProductAlreadyExistInCart::class);
        $this->expectExceptionCode(1005);
        $currency = new Currency('USD');
        $cart = new Cart(Id::generate(), $currency);
        $id = Id::generate();
        $firstProduct = new Product($id, 'first product', new Price(11.50, $currency));
        $secondProduct = new Product($id, 'second product', new Price(12.10, $currency));

        $cart->addProduct($firstProduct);
        $cart->addProduct($secondProduct);
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\Cart::removeProduct
     */
    public function ensureProductRemovedFromCart()
    {
        $currency = new Currency('USD');
        $cartId = Id::generate();
        $cart = new Cart($cartId, $currency);
        $firstProductId = Id::generate();
        $firstProduct = new Product($firstProductId, 'first product', new Price(11.50, $currency), $cartId);
        $secondProduct = new Product(Id::generate(), 'second product', new Price(12.10, $currency), $cartId);
        $expected = [(string)$secondProduct->getId() => $secondProduct];
        $cart->addProduct($firstProduct);
        $cart->addProduct($secondProduct);

        $removedProduct = $cart->removeProduct($firstProduct);

        $this->assertEquals($expected, $cart->getProducts());
        $this->assertEquals(new Product($firstProductId, 'first product', new Price(11.50, $currency), null), $removedProduct);
        $this->assertCount(count($expected), $cart->getProducts());
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\Cart::removeProduct
     */
    public function ensureRemovingNonExistingProductThrowsException()
    {
        $this->expectException(ProductNotFoundInCart::class);

        $currency = new Currency('USD');
        $cart = new Cart(Id::generate(), $currency);
        $firstProduct = new Product(Id::generate(), 'first product', new Price(11.50, $currency));
        $secondProduct = new Product(Id::generate(), 'second product', new Price(12.10, $currency));
        $cart->addProduct($firstProduct);
        $cart->addProduct($secondProduct);

        $cart->removeProduct(new Product(Id::generate(), 'non existing product', new Price(12.10, $currency)));
    }
}
