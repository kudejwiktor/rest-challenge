<?php

namespace Dogadamycie\Application\Product\Commands;

use Dogadamycie\Application\Command\{CommandValidator, CommandValidatorException};
use Dogadamycie\Application\Product\Commands\UpdateProductCommand;

/**
 * Class UpdateCartValidator
 * @package Dogadamycie\Application\Cart\Commands
 */
class UpdateProductValidator
{
    /**
     * @var array
     */
    private $rules = [
        'id' => 'required',
        'name' => 'required|min:3|max:100',
        'price' => 'required|numeric|min:0|max:9999999999999',
        'currency' => 'required|currency'
    ];

    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * UpdateProductValidator constructor.
     * @param CommandValidator $validator
     */
    public function __construct(CommandValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param UpdateProductCommand $command
     * @throws CommandValidatorException
     */
    public function validate(UpdateProductCommand $command)
    {
        $this->validator->validate([
            'id' => $command->getId(),
            'name' => $command->getName(),
            'price' => $command->getPrice(),
            'currency' => $command->getCurrency()
        ], $this->rules);
    }
}