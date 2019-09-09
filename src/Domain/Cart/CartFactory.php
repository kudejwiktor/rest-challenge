<?php

namespace Dogadamycie\Domain\Cart;

use Dogadamycie\Domain\{
    Common\Currency,
    Common\Id,
    Common\Exceptions\InvalidCurrencyException,
    Product\ProductFactory
};

/**
 * Class CartFactory
 * @package Dogadamycie\Domain\Cart
 */
class CartFactory
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * CartFactory constructor.
     * @param ProductFactory $productFactory
     */
    public function __construct(ProductFactory $productFactory)
    {
        $this->productFactory = $productFactory;
    }

    /**
     * @param array $rawData
     * @return Cart
     * @throws InvalidCurrencyException
     */
    public function fromArray(array $rawData)
    {
        $cart = new Cart(
            Id::fromString($rawData['id']),
            new Currency($rawData['currency_iso_code'])
        );

        $products = $rawData['products'];
        if (!empty($products)) {
            foreach ($products as $product) {
                $cart->addProduct($this->productFactory->fromArray($product));
            }
        }

        return $cart;
    }
}