<?php

namespace Dogadamycie\Infrastructure\Utils\Validator;

use Dogadamycie\Application\Command\{CommandValidatorException, CommandValidator};
use Illuminate\Support\Facades\Validator;

/**
 * Class CommandValidatorAdapter
 * @package Dogadamycie\Infrastructure\Utils\Validator
 */
class CommandValidatorLaravel implements CommandValidator
{
    /**
     * @var \Illuminate\Validation\Validator $validator
     */
    private $validator;

    /**
     * CommandValidatorLaravel constructor.
     */
    public function __construct()
    {
        $this->validator = Validator::make([], []);
    }

    /**
     * @param array $data
     * @param array $rules
     * @throws CommandValidatorException
     */
    public function validate(array $data, array $rules)
    {
        try {
            $this->validator->setRules($rules);
            $this->validator->setData($data);
            $this->validator->validate();
        } catch (\Exception $e) {
            throw new CommandValidatorException($this->validator->errors()->toArray());
        }
    }
}