<?php

namespace Dogadamycie\Domain\Cart\Exceptions;

use Dogadamycie\Domain\Common\Exceptions\CurrencyException;
use Throwable;

class CartNotFoundException extends CartException
{
    public function __construct(string $id, Throwable $previous = null)
    {
        parent::__construct("Cart (id = $id) not found", self::CART_NOT_FOUND_ERROR_CODE, $previous);
    }
}