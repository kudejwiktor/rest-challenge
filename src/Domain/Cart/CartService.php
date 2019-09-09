<?php

namespace Dogadamycie\Domain\Cart;

use Dogadamycie\Domain\{
    Cart\Exceptions\CartCanNotBeUpdated,
    Cart\Exceptions\CartNotFoundException,
    Cart\Exceptions\ProductAlreadyExistInCart,
    Cart\Exceptions\ProductDoesNotMatchCartCurrency,
    Cart\Exceptions\ProductNotFoundInCart,
    Common\Id,
    Common\Exceptions\InvalidCurrencyException,
    Product\Exceptions\ProductNotFoundException,
    Product\ProductRepository
};
use Dogadamycie\Infrastructure\Persistence\Product\ProductRepositoryEloquent;

/**
 * Class CartService
 * @package Dogadamycie\Domain\Cart
 */
class CartService
{
    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @var ProductRepositoryEloquent
     */
    private $productRepository;

    /**
     * CartService constructor.
     * @param CartRepository $cartRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Cart $aCart
     * @throws CartNotFoundException
     * @throws CartCanNotBeUpdated
     */
    public function update(Cart $aCart)
    {
        $cart = $this->cartRepository->cartOfId($aCart->getId());

        if ($cart->hasProducts()) {
            throw CartCanNotBeUpdated::becauseContainsProducts($aCart->getId());
        }

        $this->cartRepository->save($aCart);
    }

    /**
     * @param Id $id
     * @throws CartNotFoundException
     */
    public function delete(Id $id)
    {
        $cart = $this->cartRepository->cartOfId($id);
        foreach ($cart->getProducts() as $product) {
            $removedProduct = $cart->removeProduct($product);
            $this->productRepository->save($removedProduct);
        }

        $this->cartRepository->delete($id);
    }

    /**
     * @param Id $cartId
     * @param Id $productId
     * @throws InvalidCurrencyException
     * @throws CartNotFoundException
     * @throws ProductNotFoundException
     * @throws ProductAlreadyExistInCart
     * @throws ProductDoesNotMatchCartCurrency
     */
    public function addProduct(Id $cartId, Id $productId)
    {
        $cart = $this->cartRepository->cartOfId($cartId);
        $product = $this->productRepository->productOfId($productId);

        $addedProduct = $cart->addProduct($product);
        $this->productRepository->save($addedProduct);
    }

    /**
     * @param Id $productId
     * @throws InvalidCurrencyException
     * @throws CartNotFoundException
     * @throws ProductNotFoundInCart
     */
    public function removeProduct(Id $productId)
    {
        try {
            $product = $this->productRepository->productOfId($productId);
            if (!$product->getCartId()) {
                throw new ProductNotFoundInCart($productId);
            }
        } catch (ProductNotFoundException $e) {
            throw new ProductNotFoundInCart($productId);
        }


        $cart = $this->cartRepository->cartOfId($product->getCartId());

        $removedProduct = $cart->removeProduct($product);
        $this->productRepository->save($removedProduct);
    }
}