<?php

namespace Dogadamycie\Domain\Product;

use Dogadamycie\Domain\Common\{Currency, Id, Price};

/**
 * Class ProductFactory
 * @package Dogadamycie\Domain\Product
 */
class ProductFactory
{
    /**
     * @param array $rawData
     * @return Product
     * @throws \Dogadamycie\Domain\Common\Exceptions\InvalidCurrencyException
     */
    public function fromArray(array $rawData)
    {
        $cartId = $rawData['cart_id'];
        if($cartId) {
            $cartId =  Id::fromString($cartId);
        }

        return new Product(
            Id::fromString($rawData['id']),
            $rawData['name'],
            new Price($rawData['price'], new Currency($rawData['currency_iso_code'])),
            $cartId
        );
    }
}