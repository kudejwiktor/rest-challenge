<?php

namespace Dogadamycie\Domain\Product;

use Dogadamycie\Domain\{
    Common\Id,
    Product\Exceptions\ProductRepositoryException
};

/**
 * Interface ProductRepository
 * @package Dogadamycie\Domain\Product
 */
interface ProductRepository
{
    /**
     * @param Id $id
     * @return Product
     */
    public function productOfId(Id $id);

    /**
     * @param Product $product
     * @return void
     */
    public function save(Product $product);

    /**
     * @param Id $id
     * @return void
     * @throws ProductRepositoryException
     */
    public function delete(Id $id);

    /**
     * @param Id $id
     * @return bool
     */
    public function exist(Id $id);
}