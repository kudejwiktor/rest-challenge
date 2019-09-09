<?php

namespace Tests\Unit\Domain\Product;

use Dogadamycie\Domain\{
    Common\Currency,
    Common\Id,
    Common\Price,
    Product\Product
};
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Domain\Product\Product::getId
     * @covers \Dogadamycie\Domain\Product\Product::getName
     * @covers \Dogadamycie\Domain\Product\Product::getPrice
     * @covers \Dogadamycie\Domain\Product\Product::getCartId
     */
    public function ensureGettersReturnCorrectType()
    {
        $id = Id::generate();
        $name = 'name';
        $price = new Price(11.11, new Currency('PLN'));
        $cartId = Id::generate();

        $product = new Product($id, $name, $price, $cartId);
        $this->assertSame($product->getId(), $id);
        $this->assertSame($product->getName(), $name);
        $this->assertSame($product->getPrice(), $price);
        $this->assertSame($product->getCartId(), $cartId);

        return $product;
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Product\Product
     * @depends ensureGettersReturnCorrectType
     */
    public function ensureProductIsAssignedToCart($product)
    {
        $this->assertTrue($product->isAssignedToCart());
    }

    /**
     * @test
     * @depends ensureGettersReturnCorrectType
     */
    public function ensureProductIsUnassigned(Product $product)
    {
        $product->unassignFromCart();
        $expected = new Product($product->getId(), $product->getName(), $product->getPrice(), null);

        $this->assertEquals($expected, $product);

        return $product;
    }

    /**
     * @test
     * @depends ensureProductIsUnassigned
     */
    public function ensureProductAssignedToCart(Product $product)
    {
        $cartId = Id::generate();
        $product->assignToCart($cartId);
        $this->assertSame($cartId, $product->getCartId());
    }

}