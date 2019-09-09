<?php

namespace Tests\Unit\Domain\Cart;

use Dogadamycie\Domain\{
    Cart\Cart,
    Cart\CartRepository,
    Cart\CartService,
    Cart\Exceptions\CartCanNotBeUpdated,
    Cart\Exceptions\CartNotFoundException,
    Cart\Exceptions\ProductNotFoundInCart,
    Common\Currency,
    Common\Id,
    Common\Price,
    Product\Exceptions\ProductNotFoundException,
    Product\Product,
    Product\ProductRepository
};
use Dogadamycie\Infrastructure\{
    Persistence\Cart\CartRepositoryEloquent,
    Persistence\Product\ProductRepositoryEloquent
};
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\CartService::addProduct
     */
    public function ensureAddProductThrowsCartNotFoundExceptionIfCartDoesNotExistInStorage()
    {
        $this->expectException(CartNotFoundException::class);
        $this->expectExceptionCode(1003);
        $productRepository = $this->mockProductRepositoryEloquent();
        $cartRepository = $this->mockCartRepositoryEloquent();
        $cartRepository
            ->expects($this->once())
            ->method('cartOfId')
            ->willThrowException(new CartNotFoundException(Id::generate()));

        $cartService = $this->createCartService($cartRepository, $productRepository);
        $cartService->addProduct(Id::generate(), Id::generate());
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\CartService::addProduct
     */
    public function ensureAddProductThrowsProductNotFoundExceptionIfProductDoesNotExistInStorage()
    {
        $this->expectException(ProductNotFoundException::class);
        $this->expectExceptionCode(1004);
        $cartRepository = $this->mockCartRepositoryEloquent();
        $productRepository = $this->mockProductRepositoryEloquent();
        $productRepository
            ->expects($this->once())
            ->method('productOfId')
            ->willThrowException(new ProductNotFoundException(Id::generate()));

        $cartService = $this->createCartService($cartRepository, $productRepository);
        $cartService->addProduct(Id::generate(), Id::generate());
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\CartService::removeProduct
     */
    public function ensureRemoveProductThrowsProductNotFoundInCartIfProductNotAssignedToCart()
    {
        $this->expectException(ProductNotFoundInCart::class);
        $this->expectExceptionCode(1002);

        $productRepository = $this->mockProductRepositoryEloquent();
        $productRepository
            ->expects($this->once())
            ->method('productOfId')
            ->willReturn(new Product(Id::generate(), 'name', new Price(11.11, new Currency('PLN'))));
        $cartRepository = $this->mockCartRepositoryEloquent();

        $cartService = $this->createCartService($cartRepository, $productRepository);
        $cartService->removeProduct(Id::generate(), Id::generate());
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\CartService::removeProduct
     */
    public function ensureRemoveProductThrowsCartNotFoundExceptionIfCartDoesNotExistInStorage()
    {
        $this->expectException(CartNotFoundException::class);
        $this->expectExceptionCode(1003);

        $productRepository = $this->mockProductRepositoryEloquent();
        $productRepository
            ->expects($this->once())
            ->method('productOfId')
            ->willReturn(new Product(Id::generate(), 'name', new Price(11.11, new Currency('PLN')), Id::generate()));
        $cartRepository = $this->mockCartRepositoryEloquent();
        $cartRepository->expects($this->once())
            ->method('cartOfId')
            ->willThrowException(new CartNotFoundException(Id::generate()));

        $cartService = $this->createCartService($cartRepository, $productRepository);
        $cartService->removeProduct(Id::generate(), Id::generate());
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\CartService::removeProduct
     */
    public function ensureRemoveProductThrowsProductNotFoundInCartIfProductDoesNotExistInStorage()
    {
        $this->expectException(ProductNotFoundInCart::class);
        $this->expectExceptionCode(1002);

        $cartRepository = $this->mockCartRepositoryEloquent();
        $productRepository = $this->mockProductRepositoryEloquent();
        $productRepository
            ->expects($this->once())
            ->method('productOfId')
            ->willThrowException(new ProductNotFoundException(Id::generate()));

        $cartService = $this->createCartService($cartRepository, $productRepository);
        $cartService->removeProduct(Id::generate(), Id::generate());
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\CartService::update
     */
    public function ensureUpdateCartThrowsCartCanNotBeUpdatedExceptionIfCartContainsProducts()
    {
        $this->expectException(CartCanNotBeUpdated::class);
        $this->expectExceptionCode(1006);

        $cart = new Cart(Id::generate(), new Currency('USD'));
        $cart->addProduct(new Product(
            Id::generate(),
            'name',
            new Price(11.11, new Currency('USD')))
        );
        $cartRepository = $this->mockCartRepositoryEloquent();
        $cartRepository
            ->expects($this->once())
            ->method('cartOfId')
            ->willReturn($cart);

        $cartService = $this->createCartService($cartRepository, $this->createMock(ProductRepository::class));
        $cartService->update($cart);
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\CartService::update
     */
    public function ensureUpdateCartThrowsCartNotFoundIfCartNotFoundInStorage()
    {
        $this->expectException(CartNotFoundException::class);
        $this->expectExceptionCode(1003);

        $cart = new Cart(Id::generate(), new Currency('USD'));
        $cartRepository = $this->mockCartRepositoryEloquent();
        $cartRepository->method('cartOfId')->willThrowException(new CartNotFoundException(Id::generate()));

        $cartService = $this->createCartService($cartRepository, $this->createMock(ProductRepository::class));
        $cartService->update($cart);
    }

    /**
     * @test
     * @covers \Dogadamycie\Domain\Cart\CartService::update
     */
    public function ensureDeleteCartThrowsCartNotFoundIfCartNotFoundInStorage()
    {
        $this->expectException(CartNotFoundException::class);
        $this->expectExceptionCode(1003);

        $cartRepository = $this->mockCartRepositoryEloquent();
        $cartRepository->method('cartOfId')->willThrowException(new CartNotFoundException(Id::generate()));

        $cartService = $this->createCartService($cartRepository, $this->createMock(ProductRepository::class));
        $cartService->delete(Id::generate());
    }

    /**
     * @param CartRepository $cartRepository
     * @param ProductRepository $productRepository
     * @return \PHPUnit_Framework_MockObject_MockObject|CartService
     */
    private function createCartService(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        return new CartService($cartRepository, $productRepository);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductRepositoryEloquent
     */
    private function mockProductRepositoryEloquent()
    {
        return $this->getMockBuilder(ProductRepositoryEloquent::class)
            ->disableOriginalConstructor()
            ->setMethods(['productOfId'])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CartRepositoryEloquent
     */
    private function mockCartRepositoryEloquent()
    {
        return $this->getMockBuilder(CartRepositoryEloquent::class)
            ->disableOriginalConstructor()
            ->setMethods(['cartOfId'])
            ->getMock();
    }
}