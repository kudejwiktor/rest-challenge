<?php

namespace Tests\Unit\Infrastructure\Persistence\Cart;

use Dogadamycie\Application\{
    Cart\Query\Cart,
    Product\Query\Product
};
use Dogadamycie\Domain\Common\Id;
use Dogadamycie\Infrastructure\Persistence\Cart\CartView;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CartViewTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Cart\CartView::cartOfId
     */
    public function ensureCartOfIdReturnsCartWithProducts()
    {
        $rawCart = $this->rawCarts()['cart with first product'];
        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('leftJoin')->willReturn($builder);
        $builder->expects($this->once())->method('get')->willReturn(new Collection([$rawCart]));
        $query = $this->createCartView($builder);
        $products = [new Product($rawCart->product_id, $rawCart->product_name, $rawCart->product_price, $rawCart->product_currency)];
        $expected = new Cart($rawCart->id, $rawCart->currency_iso_code, $products);

        $this->assertEquals($expected, $query->cartOfId($rawCart->id));
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Cart\CartView::cartOfId
     */
    public function ensureCartOfIdReturnNullIfCartNotFoundInStorage()
    {
        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('leftJoin')->willReturn($builder);
        $builder->expects($this->once())->method('get')->willReturn(new Collection([]));
        $query = $this->createCartView($builder);

        $this->assertNull($query->cartOfId(Id::generate()));
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Cart\CartView::cartOfId
     */
    public function ensureCartOfIdReturnsEmptyCart()
    {
        $rawCart = $this->rawCarts()['cart without products'];

        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('leftJoin')->willReturn($builder);
        $builder->expects($this->once())->method('get')->willReturn(new Collection([$rawCart]));
        $query = $this->createCartView($builder);
        $expected = new Cart($rawCart->id, $rawCart->currency_iso_code, []);

        $this->assertEquals($expected, $query->cartOfId($rawCart->id));
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Cart\CartView::all
     */
    public function ensureAllReturnsCarts()
    {
        $rawCarts = $this->rawCarts();
        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('leftJoin')->willReturn($builder);
        $builder->expects($this->once())->method('get')->willReturn(new Collection($rawCarts));
        $query = $this->createCartView($builder);
        $expected = [
            new Cart(
                $rawCarts['cart with first product']->id,
                $rawCarts['cart with first product']->currency_iso_code, [
                new Product(
                    $rawCarts['cart with first product']->product_id,
                    $rawCarts['cart with first product']->product_name,
                    $rawCarts['cart with first product']->product_price,
                    $rawCarts['cart with first product']->product_currency
                ),
                new Product(
                    $rawCarts['cart with second product']->product_id,
                    $rawCarts['cart with second product']->product_name,
                    $rawCarts['cart with second product']->product_price,
                    $rawCarts['cart with second product']->product_currency
                ),
            ]),
            new Cart(
                $rawCarts['cart without products']->id,
                $rawCarts['cart without products']->currency_iso_code,
                []
            )
        ];

        $this->assertEquals($expected, $query->all());
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Cart\CartView::all
     */
    public function ensureAllReturnEmptyArrayIfCartsDoNotExistInStorage()
    {
        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('leftJoin')->willReturn($builder);
        $builder->expects($this->once())->method('get')->willReturn(new Collection());
        $query = $this->createCartView($builder);

        $this->assertEmpty($query->all());
    }

    private function rawCarts()
    {
        $rawCartFirst = new \stdClass();
        $rawCartFirst->id = "71d34d0e-2780-4928-9bf9-8f6aa66c31a8";
        $rawCartFirst->currency_iso_code = "USD";
        $rawCartFirst->product_id = "05d1f0ff-b6c8-4d34-8408-6a01ffb61841";
        $rawCartFirst->product_currency = "USD";
        $rawCartFirst->product_price = 173.78;
        $rawCartFirst->product_name = "Product ForestGreen";

        $rawCartSecond = new \stdClass();
        $rawCartSecond->id = "71d34d0e-2780-4928-9bf9-8f6aa66c31a8";
        $rawCartSecond->currency_iso_code = "USD";
        $rawCartSecond->product_id = "cf95e9ec-d420-462f-acd3-9c758d5ceab5";
        $rawCartSecond->product_currency = "USD";
        $rawCartSecond->product_price = 173.78;
        $rawCartSecond->product_name = "Product ForestGreen";

        $rawCartThird = new \stdClass();
        $rawCartThird->id = "1ab4b82a-3d48-4813-8e5f-f9ea8342be35";
        $rawCartThird->currency_iso_code = "USD";
        $rawCartThird->product_id = null;
        $rawCartThird->product_currency = null;
        $rawCartThird->product_price = null;
        $rawCartThird->product_name = null;

        return [
            'cart with first product' => $rawCartFirst,
            'cart with second product' => $rawCartSecond,
            'cart without products' => $rawCartThird
        ];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Builder
     */
    private function mockQueryBuilder()
    {
        return $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->setMethods(['get', 'leftJoin'])
            ->getMock();
    }

    /**
     * @param Builder $builder
     * @return \PHPUnit_Framework_MockObject_MockObject|CartView
     */
    private function createCartView(Builder $builder)
    {
        return new CartView($builder);
    }
}