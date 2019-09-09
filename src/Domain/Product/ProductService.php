<?php

namespace Dogadamycie\Domain\Product;

use Dogadamycie\Domain\Product\Exceptions\{ProductCurrencyImmutableException, ProductNotFoundException};

/**
 * Class ProductService
 * @package Dogadamycie\Domain\Product
 */
class ProductService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * ProductService constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Product $aProduct
     * @throws ProductNotFoundException
     * @throws ProductCurrencyImmutableException
     */
    public function update(Product $aProduct)
    {
        $product = $this->productRepository->productOfId($aProduct->getId());
        if($product->isAssignedToCart()) {
            $aProduct->assignToCart($product->getCartId());//Product under update always is not assigned to any cart. This line will assign it to correct cart.
        }
        if ($product->isAssignedToCart() && $this->hasDifferentCurrency($product, $aProduct)) {
            throw new ProductCurrencyImmutableException($product->getId());
        }

        $this->productRepository->save($aProduct);
    }

    /**
     * @param Product $actualProduct
     * @param Product $productUnderUpdate
     * @return bool
     */
    private function hasDifferentCurrency(Product $actualProduct, Product $productUnderUpdate)
    {
        return ($actualProduct->getPrice()->getCurrency() != $productUnderUpdate->getPrice()->getCurrency());
    }
}