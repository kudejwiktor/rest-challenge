<?php

namespace Dogadamycie\Domain\Cart\Exceptions;

use Throwable;

class ProductAlreadyExistInCart extends CartException
{
    public function __construct($productId, Throwable $previous = null)
    {
        parent::__construct(
            "Product (id = $productId) already exist some in cart",
            self::PRODUCT_ALREADY_EXIST_IN_CART,
            $previous
        );
    }
}