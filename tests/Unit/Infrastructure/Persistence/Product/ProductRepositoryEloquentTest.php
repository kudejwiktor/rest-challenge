<?php

namespace Tests\Unit\Infrastructure\Persistence\Product;

use App\Repositories\ProductRepositoryEloquent as ProductRepositoryPrettus;
use App\Model\Product as ProductEloquent;
use Dogadamycie\Domain\{
    Common\Currency,
    Common\Id,
    Common\Price,
    Product\Exceptions\ProductNotFoundException,
    Product\Exceptions\ProductRepositoryException,
    Product\Product,
    Product\ProductFactory
};
use Dogadamycie\Infrastructure\Persistence\Product\ProductRepositoryEloquent;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductRepositoryEloquentTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductRepositoryEloquent::productOfId
     */
    public function ensureProductNotFoundExceptionThrownIfProductDoesNotExistInStorage()
    {
        $this->expectException(ProductNotFoundException::class);
        $this->expectExceptionCode(1004);

        $productRepositoryPrettus = $this->mockProductRepositoryPrettus();
        $productRepositoryPrettus->method('findWhere')->willReturn(new Collection());
        $productRepositoryEloquent = $this->createProductRepositoryEloquent($productRepositoryPrettus);

        $productRepositoryEloquent->productOfId(Id::generate());
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductRepositoryEloquent::productOfId
     */
    public function ensureProductOfIdReturnsProduct()
    {
        $id = Id::generate();
        $expected = new Product($id, 'product name', new Price(11.20, new Currency('USD')));
        $productRepositoryPrettus = $this->mockProductRepositoryPrettus();
        $productRepositoryPrettus->expects($this->once())->method('findWhere')->willReturn(
            new Collection([
                new ProductEloquent([
                    'id' => $id,
                    'name' => 'product name',
                    'price' => 11.20,
                    'currency_iso_code' => 'USD',
                    'cart_id' => null
                ])
            ])
        );
        $productRepositoryEloquent = $this->createProductRepositoryEloquent($productRepositoryPrettus);

        $this->assertEquals($expected, $productRepositoryEloquent->productOfId($id));
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductRepositoryEloquent::exist
     */
    public function ensureProductExist()
    {
        $productId = Id::generate();
        $productRepositoryPrettus = $this->mockProductRepositoryPrettus();
        $productRepositoryPrettus
            ->expects($this->once())
            ->method('findWhere')
            ->willReturn(new Collection([new ProductEloquent([
            'id' => Id::generate(),
            'name' => 'name',
            'price' => 11.20,
            'currency_iso_code' => 'USD'
        ])]));
        $productRepositoryEloquent = $this->createProductRepositoryEloquent($productRepositoryPrettus);

        $this->assertTrue($productRepositoryEloquent->exist($productId));
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductRepositoryEloquent::exist
     */
    public function ensureProductDoesNotExist()
    {
        $productId = Id::generate();
        $productRepositoryPrettus = $this->mockProductRepositoryPrettus();
        $productRepositoryPrettus->expects($this->once())->method('findWhere')->willReturn(new Collection());
        $productRepositoryEloquent = $this->createProductRepositoryEloquent($productRepositoryPrettus);

        $this->assertFalse($productRepositoryEloquent->exist($productId));
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductRepositoryEloquent::delete
     */
    public function ensureDeleteThrowsProductRepositoryExceptionIfEloquentFails()
    {
        $this->expectException(ProductRepositoryException::class);
        $productRepositoryPrettus = $this->mockProductRepositoryPrettus();
        $productRepositoryPrettus->expects($this->once())->method('delete')->willThrowException(new \Exception());
        $productRepositoryEloquent = $this->createProductRepositoryEloquent($productRepositoryPrettus);

        $productRepositoryEloquent->delete(Id::generate());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductRepositoryPrettus
     */
    private function mockProductRepositoryPrettus()
    {
        return $this->getMockBuilder(ProductRepositoryPrettus::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param ProductRepositoryPrettus $productRepositoryPrettus
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductRepositoryEloquent
     */
    private function createProductRepositoryEloquent(ProductRepositoryPrettus $productRepositoryPrettus)
    {
        return new ProductRepositoryEloquent($productRepositoryPrettus, new ProductFactory());
    }
}