<?php

namespace Dogadamycie\Application\Command;


use Throwable;

/**
 * Class CommandValidatorException
 * @package Dogadamycie\Application\Command
 */
class CommandValidatorException extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    /**
     * CommandValidatorException constructor.
     * @param array $errors
     * @param Throwable|null $previous
     */
    public function __construct(array $errors, Throwable $previous = null)
    {
        parent::__construct('Command could not be processed', 1003, $previous);
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}