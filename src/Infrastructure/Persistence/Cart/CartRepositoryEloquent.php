<?php

namespace Dogadamycie\Infrastructure\Persistence\Cart;

use Dogadamycie\Domain\{
    Common\Id,
    Common\Exceptions\InvalidCurrencyException,
    Cart\Cart,
    Cart\CartFactory,
    Cart\CartRepository,
    Cart\Exceptions\CartRepositoryException,
    Cart\Exceptions\CartNotFoundException
};
use App\Model\Cart as CartEloquent;
use App\Repositories\CartRepositoryEloquent as CartRepositoryPrettus;

/**
 * Class CartRepositoryEloquent
 * @package Dogadamycie\Infrastructure\Persistence\Cart
 */
class CartRepositoryEloquent implements CartRepository
{
    /**
     * @var CartFactory
     */
    private $factory;

    /**
     * @var CartRepositoryPrettus
     */
    private $repository;

    /**
     * CartRepositoryEloquent constructor.
     * @param CartRepositoryPrettus $repository
     * @param CartFactory $factory
     */
    public function __construct(CartRepositoryPrettus $repository, CartFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param Cart $aCart
     */
    public function save(Cart $aCart)
    {
        $cart = $this->repository->findWhere(['id' => $aCart->getId()])->first();
        if (!$cart) {
            $cart = new CartEloquent();
        }

        $cart->id = $aCart->getId();
        $cart->currency_iso_code = $aCart->getCurrency();
        $cart->save();
    }

    /**
     * @param Id $id
     * @return Cart
     * @throws InvalidCurrencyException
     */
    public function cartOfId(Id $id): Cart
    {
        $cart = $this->repository->with('products')->findWhere(['id' => $id->getId()])->first();
        if (!$cart) {
            throw new CartNotFoundException($id);
        }

        return $this->factory->fromArray($cart->toArray());
    }

    /**
     * @param Id $id
     * @return bool
     */
    public function exist(Id $id)
    {
        $cart = $this->repository->findWhere(['id' => $id], ['id'])->first();
        return !is_null($cart);
    }

    /**
     * @param Id $id
     * @return void
     * @throws CartRepositoryException
     */
    public function delete(Id $id)
    {
        try {
            $this->repository->delete($id);
        } catch (\Exception $e) {
            throw CartRepositoryException::fromPrevious($e);
        }
    }
}