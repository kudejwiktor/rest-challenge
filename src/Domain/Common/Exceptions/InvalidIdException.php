<?php

namespace Dogadamycie\Domain\Common\Exceptions;

use Throwable;

class InvalidIdException extends IdException
{
    public function __construct($id, Throwable $previous = null)
    {
        parent::__construct("Invalid id ($id)", self::INVALID_UUID_ERROR_CODE, $previous);
    }
}