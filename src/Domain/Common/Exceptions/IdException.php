<?php

namespace Dogadamycie\Domain\Common\Exceptions;

class IdException extends \RuntimeException
{
    const INVALID_UUID_ERROR_CODE = 1006;
}