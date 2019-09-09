<?php

namespace Tests\Unit\Infrastructure\Persistence\Product;

use Dogadamycie\Application\Product\Query\Product;
use Dogadamycie\Domain\Common\Id;
use Dogadamycie\Infrastructure\Persistence\Product\ProductView;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductViewTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductView::productOfId
     */
    public function ensureProductOfIdReturnNullIfProductNotFoundInStorage()
    {
        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('first')->willReturn(null);
        $query = $this->createProductView($builder);

        $this->assertNull($query->productOfId('abc'));
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductView::productOfId
     */
    public function ensureProductOfIdReturnProduct()
    {
        $id = (string)Id::generate();
        $builderResult = new \stdClass();
        $builderResult->id = $id;
        $builderResult->name = 'name';
        $builderResult->price = (float)11.11;
        $builderResult->currency_iso_code = 'USD';
        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('first')->willReturn($builderResult);
        $query = $this->createProductView($builder);
        $expected = new Product(
            $builderResult->id,
            $builderResult->name,
            $builderResult->price,
            $builderResult->currency_iso_code
        );

        $this->assertEquals($expected, $query->productOfId($id));
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductView::all
     */
    public function ensureAllReturnsEmptyArrayIfProductsNotFoundInStorage()
    {
        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('get')->willReturn(new Collection());
        $query = $this->createProductView($builder);

        $this->assertEmpty($query->all());
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductView::all
     */
    public function ensureAllReturnArrayOfProducts()
    {
        $id = (string)Id::generate();
        $productModel = new \stdClass();
        $productModel->id = $id;
        $productModel->name = 'name';
        $productModel->price = (float)11.11;
        $productModel->currency_iso_code = 'USD';
        $builderResult = new Collection([$productModel]);
        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('get')->willReturn($builderResult);
        $query = $this->createProductView($builder);

        $expected = [
            new Product($id, 'name', 11.11, 'USD')
        ];

        $this->assertEquals($expected, $query->all());
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Product\ProductView::all
     */
    public function ensureAllReturnEmptyArrayIfProductNotFoundInStorage()
    {
        $builderResult = new Collection([]);
        $builder = $this->mockQueryBuilder();
        $builder->expects($this->once())->method('get')->willReturn($builderResult);
        $query = $this->createProductView($builder);

        $this->assertEquals([], $query->all());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Builder
     */
    private function mockQueryBuilder()
    {
        return $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->setMethods(['first', 'get'])
            ->getMock();
    }

    /**
     * @param Builder $builder
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductView
     */
    private function createProductView(Builder $builder)
    {
        return new ProductView($builder);
    }
}