<?php

namespace Dogadamycie\Infrastructure\Persistence\Product;

use Dogadamycie\Application\Product\Query\{Product, ProductQuery};
use Illuminate\Database\Query\Builder;

/**
 * Class ProductView
 * @package Dogadamycie\Infrastructure\Persistence\Product
 */
class ProductView implements ProductQuery
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * ProductView constructor.
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param string $id
     * @return Product
     */
    public function productOfId(string $id)
    {
        $productModel = $this->builder
            ->where('id', '=', $id)
            ->select('*')
            ->first();
        if (!$productModel) {
            return null;
        }

        return new Product($productModel->id, $productModel->name, $productModel->price, $productModel->currency_iso_code);
    }

    /**
     * @return array|Product[]
     */
    public function all()
    {
        $productModels = $this->builder->select('*')->get();
        $products = $productModels->map(function ($product) {
            return new Product($product->id, $product->name, $product->price, $product->currency_iso_code);
        });

        return $products->toArray();
    }
}