<?php

namespace Dogadamycie\Application\Product\Commands;

/**
 * Class CreateProductCommand
 * @package Dogadamycie\Application\Product\Commands
 */
class CreateProductCommand
{
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var float|null
     */
    private $price;
    /**
     * @var string|null
     */
    private $currency;

    /**
     * CreateProductCommand constructor.
     * @param null $name
     * @param null $price
     * @param null $currency
     */
    public function __construct($name = null, $price = null, $currency = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return null
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}