<?php

namespace Dogadamycie\Domain\Product\Exceptions;

use Throwable;

class ProductNotFoundException extends ProductException
{
    public function __construct($id, Throwable $previous = null)
    {
        parent::__construct(
            "Product (id = $id) not found",
            self::PRODUCT_NOT_FOUND_EXCEPTION_CODE,
            $previous
        );
    }
}