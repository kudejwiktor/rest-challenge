<?php

namespace Dogadamycie\Application\Cart\Commands;

/**
 * Class UpdateCartCommand
 * @package Dogadamycie\Application\Cart\Commands
 */
class UpdateCartCommand
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $currency;

    /**
     * UpdateCartCommand constructor.
     * @param $id
     * @param $currency
     */
    public function __construct($id = null, $currency = null)
    {
        $this->id = $id;
        $this->currency = $currency;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}