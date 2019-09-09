<?php

namespace Dogadamycie\Application\Cart\Commands;

use Dogadamycie\Application\Command\{CommandValidator, CommandValidatorException};

/**
 * Class DeleteCartValidator
 * @package Dogadamycie\Application\Cart\Commands
 */
class DeleteCartValidator
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
     * DeleteCartValidator constructor.
     * @param CommandValidator $validator
     */
    public function __construct(CommandValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param DeleteCartCommand $command
     * @throws CommandValidatorException
     */
    public function validate(DeleteCartCommand $command)
    {
        $input = ['id' => $command->getId()];
        $this->validator->validate($input, $this->rules);
    }
}