<?php

namespace Dogadamycie\Domain\Common;


/**
 * Class Price
 * @package Dogadamycie\Domain\Common
 */
class Price
{
    /**
     * @var float
     */
    private $value;

    /**
     * @var string
     */
    private $currency;

    /**
     * Price constructor.
     * @param float $value
     * @param Currency $currency
     */
    public function __construct(float $value, Currency $currency)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Price can not be negative');
        }

        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}