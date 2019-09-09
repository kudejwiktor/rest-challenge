<?php

namespace Dogadamycie\Application\Command;

interface CommandValidator
{
    /**
     * @param array $data
     * @param array $rules
     * @throws CommandValidatorException
     */
    public function validate(array $data, array $rules);
}