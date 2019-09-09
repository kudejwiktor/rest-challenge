<?php

namespace Dogadamycie\Domain\Product\Exceptions;

/**
 * Class ProductRepositoryException
 * @package Dogadamycie\Domain\Product\Exceptions
 */
class ProductRepositoryException extends ProductException
{
    /**
     * @param \Exception $previous
     * @return ProductRepositoryException
     */
    public static function fromPrevious(\Exception $previous)
    {
        return new static(
            $previous->getMessage(),
            $previous->getCode(),
            $previous
        );
    }
}