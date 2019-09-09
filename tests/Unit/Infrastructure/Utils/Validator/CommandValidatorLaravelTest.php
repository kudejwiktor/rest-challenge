<?php

namespace Tests\Unit\Infrastructure\Utils\Validator;

use Dogadamycie\Application\Command\CommandValidatorException;
use Dogadamycie\Infrastructure\Utils\Validator\CommandValidatorLaravel;
use Tests\TestCase;

class CommandValidatorLaravelTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Utils\Validator\CommandValidatorLaravel::validate
     */
    public function ensureCommandValidatorExceptionThrownIfIncorrectDataGiven()
    {
        $this->expectException(CommandValidatorException::class);

        $validator = new CommandValidatorLaravel();
        $rules = ['input' => 'required'];
        $input = ['input' => null];
        $validator->validate($input, $rules);
    }
}