<?php


namespace Dogadamycie\Domain\Cart\Exceptions;


use Throwable;

class ProductNotFoundInCart extends CartException
{
    public function __construct($productId, Throwable $previous = null)
    {
        parent::__construct(
            "Product (id = $productId) not found in cart",
            self::PRODUCT_NOT_FOUND_IN_CART_ERROR_CODE,
            $previous
        );
    }
}