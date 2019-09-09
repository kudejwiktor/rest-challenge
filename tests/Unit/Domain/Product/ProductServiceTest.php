<?php

namespace Tests\Unit\Domain\Product;

use Dogadamycie\Domain\{
    Common\Currency,
    Common\Id,
    Common\Price,
    Product\Exceptions\ProductCurrencyImmutableException,
    Product\Exceptions\ProductNotFoundException,
    Product\Product,
    Product\ProductRepository,
    Product\ProductService
};
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Domain\Product\ProductService::update
     */
    public function ensureUpdatingNonExistingProductWillThrowProductNotFoundException()
    {
        $this->expectException(ProductNotFoundException::class);

        $productRepository = $this->mockProductRepository();
        $productRepository
            ->expects($this->once())
            ->method('productOfId')
            ->willThrowException(new ProductNotFoundException(Id::generate()));
        $productService = new ProductService($productRepository);
        $productService->update($this->createMock(Product::class));
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Product\ProductService::update
     */
    public function ensureProductCurrencyIsImmutableIfProductIsAssignedToCart()
    {
        $this->expectException(ProductCurrencyImmutableException::class);

        $productWithChangedCurrency = new Product(
            Id::generate(),
            'name',
            new Price(11.11, new Currency('PLN')),
            Id::generate()
        );
        $actualProduct = new Product(
            $productWithChangedCurrency->getId(),
            $productWithChangedCurrency->getName(),
            new Price(11.11, new Currency('USD')),
            $productWithChangedCurrency->getCartId()
        );
        $productRepository = $this->mockProductRepository();
        $productRepository
            ->expects($this->once())
            ->method('productOfId')
            ->willReturn($actualProduct);
        $productService = new ProductService($productRepository);

        $productService->update($productWithChangedCurrency);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|
     */
    private function mockProductRepository()
    {
        return $this->getMockForAbstractClass(ProductRepository::class);
    }
}