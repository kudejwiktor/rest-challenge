<?php

namespace Dogadamycie\Domain\Product\Exceptions;

/**
 * Class ProductException
 * @package Dogadamycie\Domain\Product\Exceptions
 */
class ProductException extends \RuntimeException
{
    const PRODUCT_NOT_FOUND_EXCEPTION_CODE = 1004;
    const PRODUCT_CURRENCY_IMMUTABLE = 1008;
}