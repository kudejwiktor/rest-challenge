<?php

namespace Dogadamycie\Domain\Common\Exceptions;

use Throwable;

/**
 * Class InvalidCurrencyException
 * @package Dogadamycie\Domain\Common\Exceptions
 */
class InvalidCurrencyException extends CurrencyException
{
    /**
     * InvalidCurrencyException constructor.
     * @param string $currency
     * @param Throwable|null $previous
     */
    public function __construct(string $currency, Throwable $previous = null)
    {
        parent::__construct(
            "Invalid currency (ISO CODE = $currency)",
            self::INVALID_CURRENCY_ERROR_CODE,
            $previous);
    }
}