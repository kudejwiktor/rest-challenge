<?php

namespace Dogadamycie\Domain\Product;

use Dogadamycie\Domain\Common\{Id, Price};

/**
 * Class Product
 * @package Dogadamycie\Domain\Product
 */
class Product
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Price
     */
    private $price;
    /**
     * @var Id
     */
    private $cartId;

    /**
     * Product constructor.
     * @param Id $id
     * @param string $name
     * @param Price $price
     * @param Id $cartId
     */
    public function __construct(Id $id, string $name, Price $price, Id $cartId = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->cartId = $cartId;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @return Id|null
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * @return bool
     */
    public function isAssignedToCart()
    {
        return !is_null($this->cartId);
    }

    /**
     * @return void
     */
    public function unassignFromCart()
    {
        $this->cartId = null;
    }

    /**
     * @param Id $cartId
     * @return void
     */
    public function assignToCart(Id $cartId)
    {
        $this->cartId = $cartId;
    }
}