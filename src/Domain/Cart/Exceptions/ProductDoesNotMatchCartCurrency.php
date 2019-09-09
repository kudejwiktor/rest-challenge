<?php


namespace Dogadamycie\Domain\Cart\Exceptions;


use Throwable;

/**
 * Class ProductDoesNotMatchCartCurrency
 * @package Dogadamycie\Domain\Exceptions
 */
class ProductDoesNotMatchCartCurrency extends CartException
{

    /**
     * ProductDoesNotMatchCartCurrency constructor.
     * @param $productId
     * @param Throwable|null $previous
     */
    public function __construct($productId, Throwable $previous = null)
    {
        parent::__construct(
            "Product (id = $productId) does not match cart's currency",
            self::PRODUCT_DOES_NOT_MATCH_CART_CURRENCY_ERROR_CODE,
            $previous
        );
    }
}