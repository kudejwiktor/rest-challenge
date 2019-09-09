<?php

namespace Tests\Unit\Application\Product;

use Dogadamycie\Application\{
    Command\CommandValidatorException,
    Product\Commands\CreateProductCommand,
    Product\Commands\DeleteProductCommand,
    Product\Commands\UpdateProductCommand,
    Product\ProductApplicationService
};
use Dogadamycie\Domain\{
    Common\Id,
    Product\Exceptions\ProductNotFoundException,
    Product\ProductRepository,
    Product\ProductService
};
use Dogadamycie\Infrastructure\Utils\Validator\CommandValidatorLaravel;
use Tests\TestCase;

class ProductApplicationServiceTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Application\Product\ProductApplicationService::create
     */
    public function ensureCreateProductThrowsCommandValidationExceptionIfIncorrectDataGiven()
    {
        $this->expectException(CommandValidatorException::class);

        $command = new CreateProductCommand();

        $this->mockProductApplicationService($this->mockProductService())->create($command);
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Product\ProductApplicationService::update
     */
    public function ensureUpdatingNonExistingProductThrowsProductNotFoundException()
    {
        $this->expectException(ProductNotFoundException::class);
        $this->expectExceptionCode(1004);

        $productRepository = $this->mockProductRepository();
        $productService = $this->mockProductService();
        $productService
            ->expects($this->once())
            ->method('update')
            ->willThrowException(new ProductNotFoundException(Id::generate()));
        $productService = $this->mockProductApplicationService($productService, $productRepository);
        $command = new UpdateProductCommand(
            Id::generate(),
            'product name',
            11.11,
            'USD'
        );

        $productService->update($command);
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Product\ProductApplicationService::update
     */
    public function ensureUpdateReturnsProductId()
    {
        $productRepository = $this->mockProductRepository();
        $productService = $this->mockProductApplicationService($this->mockProductService(), $productRepository);
        $expectedId = Id::generate();
        $command = new UpdateProductCommand(
            $expectedId,
            'product name',
            11.11,
            'USD'
        );

        $this->assertEquals($expectedId, $productService->update($command));
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Product\ProductApplicationService::delete
     */
    public function ensureDeleteProductThrowsProductNotFoundExceptionIfProductNotFound()
    {
        $this->expectException(ProductNotFoundException::class);
        $this->expectExceptionCode(1004);

        $productRepository = $this->mockProductRepository();
        $productRepository
            ->expects($this->once())
            ->method('exist')
            ->willReturn(false);
        $productService = $this->mockProductApplicationService($this->mockProductService(), $productRepository);
        $command = new DeleteProductCommand(Id::generate());

        $productService->delete($command);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductRepository
     */
    private function mockProductRepository()
    {
        return $this->getMockForAbstractClass(ProductRepository::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductService
     */
    private function mockProductService()
    {
        return $this->createMock(ProductService::class);
    }

    /**
     * @param $productService
     * @param null $productRepository
     * @return \PHPUnit_Framework_MockObject_MockObject|ProductApplicationService
     */
    private function mockProductApplicationService($productService, $productRepository = null)
    {
        if (!$productRepository) {
            $productRepository = $this->getMockForAbstractClass(ProductRepository::class);
        }

        return new ProductApplicationService($productService, $productRepository, new CommandValidatorLaravel());
    }
}