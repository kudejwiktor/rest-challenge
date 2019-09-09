<?php

namespace Dogadamycie\Application\Product;

use Dogadamycie\Application\{
    Product\Commands\DeleteProductCommand,
    Product\Commands\DeleteProductValidator,
    Product\Commands\UpdateProductValidator,
    Command\CommandValidator,
    Product\Commands\CreateProductCommand,
    Product\Commands\CreateProductValidator,
    Product\Commands\UpdateProductCommand,
    Command\CommandValidatorException
};
use Dogadamycie\Domain\{
    Common\Currency,
    Common\Id,
    Common\Price,
    Product\Exceptions\ProductNotFoundException,
    Product\Product,
    Product\ProductRepository,
    Common\Exceptions\InvalidCurrencyException,
    Product\ProductService
};

/**
 * Class ProductApplicationService
 * @package Dogadamycie\Application\Product
 */
class ProductApplicationService
{
    /**
     * @var ProductRepository
     */
    private $repository;

    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * ProductApplicationService constructor.
     * @param ProductService $productService
     * @param ProductRepository $repository
     * @param CommandValidator $validator
     */
    public function __construct(ProductService $productService , ProductRepository $repository, CommandValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->productService = $productService;
    }

    /**
     * @param CreateProductCommand $command
     * @return Id
     * @throws CommandValidatorException
     * @throws InvalidCurrencyException
     */
    public function create(CreateProductCommand $command): Id
    {
        $validator = new CreateProductValidator($this->validator);
        $validator->validate($command);

        $product = new Product(
            Id::generate(),
            $command->getName(),
            new Price($command->getPrice(), new Currency($command->getCurrency()))
        );
        $this->repository->save($product);

        return $product->getId();
    }

    /**
     * @param UpdateProductCommand $command
     * @return Id
     * @throws CommandValidatorException
     * @throws InvalidCurrencyException
     * @throws ProductNotFoundException
     */
    public function update(UpdateProductCommand $command)
    {
        $validator = new UpdateProductValidator($this->validator);
        $validator->validate($command);

        if (!Id::isValid($command->getId())) {
            throw new ProductNotFoundException($command->getId());
        }

        $product = new Product(
            Id::fromString($command->getId()),
            $command->getName(),
            new Price($command->getPrice(), new Currency($command->getCurrency()))
        );

        $this->productService->update($product);

        return $product->getId();
    }

    /**
     * @param DeleteProductCommand $command
     * @throws CommandValidatorException
     * @throws ProductNotFoundException
     */
    public function delete(DeleteProductCommand $command)
    {
        $validator = new DeleteProductValidator($this->validator);
        $validator->validate($command);
        if (!Id::isValid($command->getId())) {
            throw new ProductNotFoundException($command->getId());
        }
        $id = Id::fromString($command->getId());

        if (!$this->repository->exist($id)) {
            throw new ProductNotFoundException($id);
        }

        $this->repository->delete($id);
    }
}