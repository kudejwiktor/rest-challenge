<?php

namespace Tests\Unit\Application\Cart;

use Dogadamycie\Application\{
    Cart\CartApplicationService,
    Cart\Commands\CreateCartCommand,
    Cart\Commands\DeleteCartCommand,
    Cart\Commands\UpdateCartCommand,
    Command\CommandValidatorException
};
use Dogadamycie\Domain\{
    Cart\CartRepository,
    Cart\CartService,
    Cart\Exceptions\CartNotFoundException,
    Common\Id
};
use Dogadamycie\Infrastructure\Utils\Validator\CommandValidatorLaravel;
use Tests\TestCase;

class CartServiceTest extends TestCase
{

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\CartApplicationService::create
     */
    public function ensureCreateCartThrowsCommandValidationExceptionIfNoCurrencyGiven()
    {
        $this->expectException(CommandValidatorException::class);

        $command = new CreateCartCommand(); //no currency given

        $this->mockCartApplicationService()->create($command);
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\CartApplicationService::update
     */
    public function ensureUpdateCartThrowsCommandValidationExceptionIfInvalidDataGiven()
    {
        $this->expectException(CommandValidatorException::class);

        $command = new UpdateCartCommand(); //empty command

        $this->mockCartApplicationService()->update($command);
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\CartApplicationService::update
     */
    public function ensureCartNotFoundExceptionThrownIfInvalidIdGiven()
    {
        $this->expectException(CartNotFoundException::class);

        $cartRepository = $this->mockCartRepository();
        $cartService = $this->mockCartApplicationService($cartRepository);
        $command = new UpdateCartCommand('invalid id', 'USD');

        $cartService->update($command);
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\CartApplicationService::update
     */
    public function ensureUpdateCartReturnsUpdatedCartId()
    {
        $cartRepository = $this->mockCartRepository();
//        $cartRepository->expects($this->once())->method('exist')->willReturn(true); //Cart found
        $cartService = $this->mockCartApplicationService($cartRepository);
        $expectedId = Id::generate();
        $command = new UpdateCartCommand($expectedId, 'USD');

        $this->assertEquals($expectedId, $cartService->update($command));
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\CartApplicationService::delete
     */
    public function ensureDeleteCartThrowsCommandValidationExceptionIfInvalidDataGiven()
    {
        $this->expectException(CommandValidatorException::class);

        $cartService = $this->mockCartApplicationService();
        $command = new DeleteCartCommand();
        $cartService->delete($command);
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\CartApplicationService::delete
     */
    public function ensureDeletingCartWithInvalidIdThrowsCartNotFoundException()
    {
        $this->expectException(CartNotFoundException::class);

        $cartRepository = $this->mockCartRepository();
        $cartService = $this->mockCartApplicationService($cartRepository);
        $command = new DeleteCartCommand('invalid id');

        $cartService->delete($command);
    }

    /**
     * @test
     * @covers \Dogadamycie\Application\Cart\CartApplicationService::delete
     */
    public function ensureDeleteCartReturnsCartId()
    {
        $cartRepository = $this->mockCartRepository();
        $cartService = $this->mockCartApplicationService($cartRepository);
        $expectedId = Id::generate();
        $command = new DeleteCartCommand($expectedId);

        $this->assertEquals($expectedId, $cartService->delete($command));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CartRepository
     */
    private function mockCartRepository()
    {
        return $this->getMockForAbstractClass(CartRepository::class);
    }

    /**
     * @param null $cartRepository
     * @return \PHPUnit_Framework_MockObject_MockObject|CartApplicationService
     */
    private function mockCartApplicationService($cartRepository = null)
    {
        if (!$cartRepository) {
            $cartRepository = $this->getMockForAbstractClass(CartRepository::class);
        }

        return new CartApplicationService($this->createMock(CartService::class), $cartRepository, new CommandValidatorLaravel());
    }
}