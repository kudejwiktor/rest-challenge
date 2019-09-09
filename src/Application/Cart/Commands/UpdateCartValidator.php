<?php

namespace Dogadamycie\Application\Cart\Commands;

use Dogadamycie\Application\Command\{CommandValidator, CommandValidatorException};

/**
 * Class UpdateCartValidator
 * @package Dogadamycie\Application\Cart\Commands
 */
class UpdateCartValidator
{
    /**
     * @var array
     */
    private $rules = [
        'id' => 'required',
        'currency' => 'required|currency'
    ];

    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * UpdateCartValidator constructor.
     * @param CommandValidator $validator
     */
    public function __construct(CommandValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param UpdateCartCommand $command
     * @throws CommandValidatorException
     */
    public function validate(UpdateCartCommand $command)
    {
        $this->validator->validate([
            'id' => $command->getId(),
            'currency' => $command->getCurrency(),
        ], $this->rules);
    }
}