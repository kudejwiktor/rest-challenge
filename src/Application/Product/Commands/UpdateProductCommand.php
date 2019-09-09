<?php

namespace Dogadamycie\Application\Product\Commands;

class UpdateProductCommand
{
    /**
     * @var string|null
     */
    private $id;

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
     * UpdateProductCommand constructor.
     * @param null $id
     * @param null $name
     * @param null $price
     * @param null $currency
     */
    public function __construct($id = null, $name = null, $price = null, $currency = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}