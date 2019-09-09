<?php

namespace Tests\Unit\Domain\Common;

use Dogadamycie\Domain\Common\{Currency, Price};
use Tests\TestCase;

class PriceTest extends TestCase
{
    /**
     * @test
     *
     */
    public function ensureExceptionThrownIfNegativePriceGiven()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Price(-1, new Currency('USD'));
    }
}