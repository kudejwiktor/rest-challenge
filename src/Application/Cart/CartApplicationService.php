<?php

namespace Dogadamycie\Application\Cart;

use Dogadamycie\Application\Cart\Commands\{
    AddProductCommand,
    AddProductValidator,
    CreateCartCommand,
    CreateCartValidator,
    DeleteCartCommand,
    DeleteCartValidator,
    RemoveProductCommand,
    RemoveProductValidator,
    UpdateCartCommand,
    UpdateCartValidator
};
use Dogadamycie\Application\Command\{
    CommandValidator,
    CommandValidatorException
};
use Dogadamycie\Domain\Cart\{Cart,
    CartRepository,
    CartService,
    Exceptions\CartCanNotBeUpdated,
    Exceptions\CartNotFoundException,
    Exceptions\ProductNotFoundInCart};
use Dogadamycie\Domain\Common\{
    Currency,
    Id,
    Exceptions\InvalidCurrencyException
};
use Dogadamycie\Domain\Product\Exceptions\ProductNotFoundException;

/**
 * Class CartApplicationService
 * @package Dogadamycie\Application\Cart
 */
class CartApplicationService
{
    /**
     * @var CartRepository
     */
    private $repository;

    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * @var CartService
     */
    private $cartService;

    /**
     * CartApplicationService constructor.
     * @param CartService $cartService
     * @param CartRepository $repository
     * @param CommandValidator $validator
     */
    public function __construct(CartService $cartService, CartRepository $repository, CommandValidator $validator)
    {
        $this->cartService = $cartService;
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @param CreateCartCommand $command
     * @return Id
     * @throws CommandValidatorException
     * @throws InvalidCurrencyException
     */
    public function create(CreateCartCommand $command): Id
    {
        $validator = new CreateCartValidator($this->validator);
        $validator->validate($command);
        $cart = new Cart(Id::generate(), new Currency($command->getCurrency()));
        $this->repository->save($cart);

        return $cart->getId();
    }

    /**
     * @param UpdateCartCommand $command
     * @return Id
     * @throws CartNotFoundException
     * @throws CartCanNotBeUpdated
     * @throws CommandValidatorException
     * @throws InvalidCurrencyException
     */
    public function update(UpdateCartCommand $command): Id
    {
        $validator = new UpdateCartValidator($this->validator);
        $validator->validate($command);
        $id = $command->getId();
        if (!Id::isValid($id)) {
            throw new CartNotFoundException($id);
        }

        $cart = new Cart(Id::fromString($id), new Currency($command->getCurrency()));
        $this->cartService->update($cart);

        return $cart->getId();
    }

    /**
     * @param DeleteCartCommand $command
     * @return Id
     * @throws CartNotFoundException
     * @throws CommandValidatorException
     */
    public function delete(DeleteCartCommand $command): Id
    {
        $validator = new DeleteCartValidator($this->validator);
        $validator->validate($command);
        $cartId = $command->getId();
        if (!Id::isValid($cartId)) {
            throw new CartNotFoundException($cartId);
        }
        $cartId = Id::fromString($cartId);

        $this->cartService->delete(Id::fromString($cartId));

        return $cartId;
    }

    /**
     * @param AddProductCommand $command
     * @throws CommandValidatorException
     * @throws InvalidCurrencyException
     * @throws CartNotFoundException
     * @throws ProductNotFoundException
     */
    public function addProduct(AddProductCommand $command)
    {
        $validator = new AddProductValidator($this->validator);
        $validator->validate($command);
        $productId = $command->getProductId();
        $cartId = $command->getCartId();
        if (!Id::isValid($cartId)) {
            throw new CartNotFoundException($cartId);
        }
        if (!Id::isValid($productId)) {
            throw new ProductNotFoundException($productId);
        }

        $this->cartService->addProduct(Id::fromString($cartId), Id::fromString($productId));
    }

    /**
     * @param RemoveProductCommand $command
     * @throws CommandValidatorException
     * @throws InvalidCurrencyException
     * @throws CartNotFoundException
     * @throws ProductNotFoundInCart
     */
    public function removeProduct(RemoveProductCommand $command)
    {
        $validator = new RemoveProductValidator($this->validator);
        $validator->validate($command);
        $productId = $command->getProductId();
        if (!Id::isValid($productId)) {
            throw new ProductNotFoundInCart($productId);
        };

        $this->cartService->removeProduct(Id::fromString($productId));
    }
}