<?php

namespace Dogadamycie\Infrastructure\Persistence\Cart;

use Dogadamycie\Application\{
    Cart\Query\Cart,
    Cart\Query\CartQuery,
    Product\Query\Product
};
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class CartView
 * @package Dogadamycie\Infrastructure\Persistence\Cart
 */
class CartView implements CartQuery
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * CartView constructor.
     * @param Builder $cartBuilder
     */
    public function __construct(Builder $cartBuilder)
    {
        $this->builder = $cartBuilder;
    }

    /**
     * @param string $id
     * @return Cart|null
     */
    public function cartOfId(string $id)
    {
        $cartRaw = $this->builder
            ->leftJoin('product', 'cart.id', '=', 'product.cart_id')
            ->where('cart.id', '=', $id)
            ->select(['cart.*',
                'product.id AS product_id',
                'product.currency_iso_code AS product_currency',
                'product.price AS product_price',
                'product.name AS product_name'])
            ->get();


        if ($cartRaw->isEmpty()) {
            return null;
        }

        $cart = $cartRaw->first();
        $products = [];
        $cartRaw->each(function ($cart) use (&$products) {
            if ($cart->product_id) {
                $products[] = new Product($cart->product_id, $cart->product_name, $cart->product_price, $cart->product_currency);
            }
        });

        return new Cart(
            $cart->id,
            $cart->currency_iso_code,
            $products
        );
    }

    /**
     * @return array|null
     */
    public function all()
    {
        $cartRaw = $this->builder
            ->leftJoin('product', 'cart.id', '=', 'product.cart_id')
            ->select('cart.*',
                'product.id AS product_id',
                'product.currency_iso_code AS product_currency',
                'product.price AS product_price',
                'product.name AS product_name')
            ->get()
            ->groupBy('id');
        if ($cartRaw->isEmpty()) {
            return null;
        }

        $carts = $cartRaw->map(function ($cart, $id) {
            $products = [];
            $cart->each(function ($product) use (&$products) {
                if ($product->product_id) {
                    $products[] = new Product($product->product_id, $product->product_name, $product->product_price, $product->product_currency);
                }
            });
            return new Cart($id, $cart->first()->currency_iso_code, $products);
        });

        return $carts->values()->toArray();
    }
}