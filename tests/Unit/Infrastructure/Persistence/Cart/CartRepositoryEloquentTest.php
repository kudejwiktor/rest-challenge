<?php

namespace Tests\Unit\Infrastructure\Persistence\Cart;

use App\Model\Cart as CartEloquent;
use App\Repositories\CartRepositoryEloquent as CartRepositoryPrettus;
use Dogadamycie\Domain\{
    Cart\Cart,
    Cart\CartFactory,
    Cart\Exceptions\CartNotFoundException,
    Cart\Exceptions\CartRepositoryException,
    Common\Currency,
    Common\Id,
    Product\ProductFactory
};
use Dogadamycie\Infrastructure\Persistence\Cart\CartRepositoryEloquent;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CartRepositoryEloquentTest extends TestCase
{
    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Cart\CartRepositoryEloquent::cartOfId
     */
    public function ensureCartOfIdThrowsCartNotFoundExceptionIfCartNotFoundInStorage()
    {
        $this->expectException(CartNotFoundException::class);
        $this->expectExceptionCode(1003);

        $cartRepositoryPrettus = $this->mockCartRepositoryPrettus();
        $cartRepositoryPrettus->expects($this->once())->method('with')->willReturn($cartRepositoryPrettus);
        $cartRepositoryPrettus->expects($this->once())->method('findWhere')->willReturn(new Collection());
        /** @var CartRepositoryEloquent $cartRepository */
        $cartRepository = $this->createCartRepositoryEloquent($cartRepositoryPrettus);

        $cartRepository->cartOfId(Id::generate());
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Cart\CartRepositoryEloquent::cartOfId
     */
    public function ensureCartOfIdReturnsCart()
    {
        $id = Id::generate();
        $cart = new CartEloquent([
            'id' => $id,
            'currency_iso_code' => 'USD',
        ]);
        $cart->setRelation('products', new Collection());
        $expected = new Cart($id, new Currency('USD'));
        $cartRepositoryPrettus = $this->mockCartRepositoryPrettus();
        $cartRepositoryPrettus->expects($this->once())->method('with')->willReturn($cartRepositoryPrettus);
        $cartRepositoryPrettus->expects($this->once())->method('findWhere')->willReturn(new Collection([$cart]));
        $cartRepositoryEloquent = $this->createCartRepositoryEloquent($cartRepositoryPrettus);

        $this->assertEquals($expected, $cartRepositoryEloquent->cartOfId($id));
    }

    /**
     * @test
     * @covers \Dogadamycie\Infrastructure\Persistence\Cart\CartRepositoryEloquent::delete
     */
    public function ensureDeleteThrowsCartRepositoryExceptionIfEloquentFails()
    {
        $this->expectException(CartRepositoryException::class);
        $cartRepositoryPrettus = $this->mockCartRepositoryPrettus();
        $cartRepositoryPrettus->expects($this->once())->method('delete')->willThrowException(new \Exception());
        $cartRepositoryEloquent = $this->createCartRepositoryEloquent($cartRepositoryPrettus);

        $cartRepositoryEloquent->delete(Id::generate());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CartRepositoryPrettus
     */
    private function mockCartRepositoryPrettus()
    {
        return $this->getMockBuilder(CartRepositoryPrettus::class)
            ->disableOriginalConstructor()
            ->setMethods(['with', 'first', 'findWhere', 'delete'])
            ->getMock();
    }

    /**
     * @param CartRepositoryPrettus $laravelRepository
     * @return \PHPUnit_Framework_MockObject_MockObject|CartRepositoryEloquent
     */
    private function createCartRepositoryEloquent(CartRepositoryPrettus $laravelRepository)
    {
        return new CartRepositoryEloquent($laravelRepository, new CartFactory(new ProductFactory()));
    }
}