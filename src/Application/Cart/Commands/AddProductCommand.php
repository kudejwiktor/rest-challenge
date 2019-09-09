<?php

namespace Dogadamycie\Application\Cart\Commands;

/**
 * Class AddProductCommand
 * @package Dogadamycie\Application\Cart\Commands
 */
class AddProductCommand
{
    /**
     * @var null
     */
    private $cartId;
    /**
     * @var null
     */
    private $productId;

    /**
     * AddProductCommand constructor.
     * @param null $cartId
     * @param null $productId
     */
    public function __construct($cartId = null, $productId = null)
    {
        $this->cartId = $cartId;
        $this->productId = $productId;
    }

    /**
     * @return null
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return null
     */
    public function getCartId()
    {
        return $this->cartId;
    }
}