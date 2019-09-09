<?php


namespace Dogadamycie\Domain\Cart\Exceptions;


class CartException extends \RuntimeException
{
    const PRODUCT_DOES_NOT_MATCH_CART_CURRENCY_ERROR_CODE = 1001;
    const PRODUCT_NOT_FOUND_IN_CART_ERROR_CODE = 1002;
    const PRODUCT_NOT_ASSIGNED_TO_ANY_CART = 1002;
    const CART_NOT_FOUND_ERROR_CODE = 1003;
    const PRODUCT_ALREADY_EXIST_IN_CART = 1005;
    const CART_CAN_NOT_BE_UPDATED = 1006;
}