<?php

namespace Dogadamycie\Application\Cart\Commands;

use Dogadamycie\Application\Command\{CommandValidator, CommandValidatorException};

/**
 * Class CreateCartValidator
 * @package Dogadamycie\Application\Cart\Commands
 */
class CreateCartValidator
{
    /**
     * @var array
     */
    private $rules = [
        'currency' => 'required|currency'
    ];

    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * CreateCartValidator constructor.
     * @param CommandValidator $validator
     */
    public function __construct(CommandValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param CreateCartCommand $command
     * @throws CommandValidatorException
     */
    public function validate(CreateCartCommand $command)
    {
        $this->validator->validate(['currency' => $command->getCurrency()], $this->rules);
    }
}