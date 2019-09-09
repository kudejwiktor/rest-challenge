<?php

namespace Dogadamycie\Domain\Common;

use Dogadamycie\Domain\Common\Exceptions\InvalidCurrencyException;
use Money\Currencies\ISOCurrencies;

/**
 * Class Currency
 * @package Dogadamycie\Domain\Common
 */
class Currency
{
    /**
     * @var string ISO code
     */
    private $currency;

    /**
     * Currency constructor.
     * @param string $currency
     * @throws InvalidCurrencyException
     */
    public function __construct(string $currency)
    {
        if (!self::isValid($currency)) {
            throw new InvalidCurrencyException($currency);
        }
        $this->currency = $currency;
    }

    /**
     * @param string $currency
     * @return bool
     */
    public static function isValid(string $currency)
    {
        $currencies = new ISOCurrencies();
        $currency = new \Money\Currency($currency);

        return $currency->isAvailableWithin($currencies);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->currency;
    }
}