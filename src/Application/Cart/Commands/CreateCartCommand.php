<?php

namespace Dogadamycie\Application\Cart\Commands;

/**
 * Class CreateCartCommand
 * @package Dogadamycie\Application\Cart\Commands
 */
class CreateCartCommand
{
    /**
     * @var string
     */
    private $currency;

    /**
     * CreateCartCommand constructor.
     * @param string|null $currency
     */
    public function __construct(string $currency = null)
    {
        $this->currency = $currency;
    }

    /**
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}