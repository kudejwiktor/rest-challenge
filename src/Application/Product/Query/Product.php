<?php

namespace Dogadamycie\Application\Product\Query;

/**
 * Class ProductView
 * @package Dogadamycie\Application\Product\Query
 */
class Product implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var float
     */
    private $price;
    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $name;

    /**
     * Product constructor.
     * @param string $id
     * @param string $name
     * @param float $price
     * @param string $currency
     */
    public function __construct(string $id, string $name, float $price, string $currency)
    {
        $this->id = $id;
        $this->price = $price;
        $this->currency = $currency;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'currency' => $this->currency
        ];
    }
}