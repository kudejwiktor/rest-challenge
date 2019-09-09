<?php

namespace Dogadamycie\Application\Cart\Commands;

/**
 * Class RemoveProduct
 * @package Dogadamycie\Application\Cart\Commands
 */
class RemoveProductCommand
{
    /**
     * @var null
     */
    private $productId;

    /**
     * RemoveProduct constructor.
     * @param null $productId
     */
    public function __construct($productId = null)
    {
        $this->productId = $productId;
    }

    /**
     * @return null
     */
    public function getProductId()
    {
        return $this->productId;
    }
}