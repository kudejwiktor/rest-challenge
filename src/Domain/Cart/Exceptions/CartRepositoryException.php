<?php

namespace Dogadamycie\Domain\Cart\Exceptions;

class CartRepositoryException extends CartException
{
    public static function fromPrevious(\Exception $previous)
    {
        return new static(
            $previous->getMessage(),
            $previous->getCode(),
            $previous
        );
    }
}