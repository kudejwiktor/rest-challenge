<?php

namespace Dogadamycie\Domain\Cart\Exceptions;

use Dogadamycie\Domain\Common\Id;
use Throwable;

class CartCanNotBeUpdated extends CartException
{
    public static function becauseContainsProducts(Id $id)
    {
        return new static(
            "Cart (id = $id) can not be updated because contains products",
            self::CART_CAN_NOT_BE_UPDATED
        );
    }
}