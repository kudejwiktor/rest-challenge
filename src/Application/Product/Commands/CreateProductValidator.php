<?php

namespace Dogadamycie\Application\Product\Commands;

use Dogadamycie\Application\Command\{CommandValidator, CommandValidatorException};

/**
 * Class CreateProductValidator
 * @package Dogadamycie\Application\Product\Commands
 */
class CreateProductValidator
{
    /**
     * @var array
     */
    private $rules = [
        'name' => 'required|min:3|max:100',
        'price' => 'required|numeric|min:0|max:9999999999999',
        'currency' => 'required|currency'
    ];

    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * CreateProductValidator constructor.
     * @param CommandValidator $validator
     */
    public function __construct(CommandValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param CreateProductCommand $command
     * @throws CommandValidatorException
     */
    public function validate(CreateProductCommand $command)
    {
        $this->validator->validate([
            'name' => $command->getName(),
            'price' => $command->getPrice(),
            'currency' => $command->getCurrency()
        ], $this->rules);
    }
}