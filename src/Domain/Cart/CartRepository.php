<?php

namespace Dogadamycie\Domain\Cart;

use Dogadamycie\Domain\{
    Cart\Exceptions\CartNotFoundException,
    Cart\Exceptions\CartRepositoryException,
    Common\Id
};

/**
 * Interface CartRepository
 * @package Dogadamycie\Domain\Cart
 */
interface CartRepository
{
    /**
     * @param Cart $cart
     * @return void
     */
    public function save(Cart $cart);

    /**
     * @param Id $id
     * @return Cart
     * @throws CartNotFoundException
     */
    public function cartOfId(Id $id);

    /**
     * @param Id $id
     * @return bool
     */
    public function exist(Id $id);

    /**
     * @param Id $id
     * @return void
     * @throws CartRepositoryException
     */
    public function delete(Id $id);
}