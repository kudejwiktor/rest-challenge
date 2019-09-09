<?php

namespace Dogadamycie\Application\Cart\Commands;

use Dogadamycie\Application\Command\{CommandValidator, CommandValidatorException};

/**
 * Class RemoveProductValidator
 * @package Dogadamycie\Application\Cart\Commands
 */
class RemoveProductValidator
{
    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * @var array
     */
    private $rules = [
        'id' => 'required'
    ];

    /**
     * RemoveProductValidator constructor.
     * @param CommandValidator $validator
     */
    public function __construct(CommandValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param RemoveProductCommand $command
     * @throws CommandValidatorException
     */
    public function validate(RemoveProductCommand $command)
    {
        $this->validator->validate([
            'id' => $command->getProductId(),
        ], $this->rules);
    }
}