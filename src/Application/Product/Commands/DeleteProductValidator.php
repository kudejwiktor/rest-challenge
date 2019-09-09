<?php

namespace Dogadamycie\Application\Product\Commands;

use Dogadamycie\Application\Command\CommandValidator;

/**
 * Class DeleteProductValidator
 * @package Dogadamycie\Application\Product\Commands
 */
class DeleteProductValidator
{
    /**
     * @var array
     */
    private $rules = [
        'id' => 'required',
    ];
    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * DeleteProductValidator constructor.
     * @param CommandValidator $validator
     */
    public function __construct(CommandValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param DeleteProductCommand $command
     * @throws \Dogadamycie\Application\Command\CommandValidatorException
     */
    public function validate(DeleteProductCommand $command)
    {
        $this->validator->validate([
            'id' => $command->getId(),
        ], $this->rules);
    }
}