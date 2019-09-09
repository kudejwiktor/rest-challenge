<?php

namespace Dogadamycie\Application\Cart\Commands;

use Dogadamycie\Application\Command\{CommandValidator, CommandValidatorException};

/**
 * Class AddProductValidator
 * @package Dogadamycie\Application\Cart\Commands
 */
class AddProductValidator
{
    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * @var array
     */
    private $rules = [
        'cart_id' => 'required',
        'product_id' => 'required'
    ];

    /**
     * AddProductValidator constructor.
     * @param CommandValidator $validator
     */
    public function __construct(CommandValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param AddProductCommand $command
     * @throws CommandValidatorException
     */
    public function validate(AddProductCommand $command)
    {
        $this->validator->validate([
            'cart_id' => $command->getCartId(),
            'product_id' => $command->getProductId(),
        ], $this->rules);
    }
}