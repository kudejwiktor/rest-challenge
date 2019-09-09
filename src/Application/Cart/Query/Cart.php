<?php

namespace Dogadamycie\Application\Cart\Query;

use Dogadamycie\Application\Product\Query\Product;

/**
 * Class CartView
 * @package Dogadamycie\Application\Cart\Query
 */
class Cart implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var array
     */
    private $products;

    /**
     * CartView constructor.
     * @param string $id
     * @param string $currency
     * @param Product[] $products
     */
    public function __construct(string $id, string $currency, array $products = [])
    {
        $this->id = $id;
        $this->currency = $currency;
        $this->products = $products;
    }

    /**
     * @return float|int
     */
    public function totalProductsPrice()
    {
        $total = 0;
        if (empty($this->products)) {
            return $total;
        }

        foreach ($this->products as $product) {
            $total += $product->getPrice();
        }

        return $total;
    }

    /**
     * @return float|int
     */
    public function avgProductPrice()
    {
        $total = $this->totalProductsPrice();

        if ($total == 0) {
            return $total;
        }

        return $total / count($this->products);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'products' => $this->products,
            'currency' => $this->currency,
            'total_products_price' => $this->totalProductsPrice(),
            'avg_product_price' => $this->avgProductPrice()
        ];
    }
}