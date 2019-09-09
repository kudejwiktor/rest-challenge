<?php


namespace Tests\Unit\Domain\Common;

use Dogadamycie\Domain\Common\Currency;
use Dogadamycie\Domain\Common\Exceptions\InvalidCurrencyException;
use \Tests\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * @test
     */
    public function ensureExceptionThrownIfInvalidCurrencyISOCodeGiven()
    {
        $this->expectException(InvalidCurrencyException::class);
        $this->expectExceptionCode(1100);

        new Currency('ABC');
    }
}