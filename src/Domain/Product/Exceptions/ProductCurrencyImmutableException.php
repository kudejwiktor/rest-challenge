<?php

namespace Dogadamycie\Domain\Product\Exceptions;

use Throwable;

/**
 * Class ProductCurrencyImmutableException
 * @package Dogadamycie\Domain\Product\Exceptions
 */
class ProductCurrencyImmutableException extends ProductException
{
    public function __construct($id, Throwable $previous = null)
    {
        parent::__construct(
            "Product (id = $id) currency can not be changed because this product is assigned to cart",
            self::PRODUCT_CURRENCY_IMMUTABLE,
            $previous);
    }
}