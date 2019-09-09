<?php

namespace Dogadamycie\Domain\Cart;

use Dogadamycie\Domain\{
    Product\Product,
    Cart\Exceptions\ProductAlreadyExistInCart,
    Cart\Exceptions\ProductNotFoundInCart,
    Cart\Exceptions\ProductDoesNotMatchCartCurrency
};
use Dogadamycie\Domain\Common\{Currency, Id};

/**
 * Class Cart
 * @package Dogadamycie\Domain
 */
class Cart
{
    /**
     * @var array
     */
    private $products = [];

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var Id
     */
    private $id;

    /**
     * Cart constructor.
     * @param Id $id
     * @param Currency $currency
     */
    public function __construct(Id $id, Currency $currency)
    {
        $this->currency = $currency;
        $this->id = $id;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function countProducts()
    {
        return count($this->products);
    }

    /**
     * @param Product $product
     * @return Product
     * @throws ProductAlreadyExistInCart
     * @throws ProductDoesNotMatchCartCurrency
     */
    public function addProduct(Product $product)
    {
        if ($product->getPrice()->getCurrency() != $this->currency) {
            throw new ProductDoesNotMatchCartCurrency($product->getId());
        }

        if (isset($this->products[(string)$product->getId()])) {
            throw new ProductAlreadyExistInCart($product->getId());
        }

        $product->assignToCart($this->getId());
        $this->products[(string)$product->getId()] = $product;

        return $product;
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function removeProduct(Product $product)
    {
        if (!$this->productExist($product->getId())) {
            throw new ProductNotFoundInCart($product->getId()->getId());
        }

        $product->unassignFromCart();
        unset($this->products[$product->getId()->getId()]);

        return $product;
    }

    /**
     * @param string $productId
     * @return bool
     */
    public function productExist(string $productId)
    {
        return isset($this->products[$productId]);
    }

    /**
     * @return bool
     */
    public function hasProducts()
    {
        return !empty($this->products);
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}