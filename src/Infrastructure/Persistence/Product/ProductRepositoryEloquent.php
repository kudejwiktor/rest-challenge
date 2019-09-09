<?php

namespace Dogadamycie\Infrastructure\Persistence\Product;

use App\Repositories\ProductRepositoryEloquent as ProductRepositoryPrettus;
use Illuminate\Database\Eloquent\Model;
use App\Model\Product as ProductEloquent;
use Dogadamycie\Domain\{
    Common\Id,
    Common\Exceptions\InvalidCurrencyException,
    Product\Exceptions\ProductNotFoundException,
    Product\Exceptions\ProductRepositoryException,
    Product\Product,
    Product\ProductFactory,
    Product\ProductRepository
};

/**
 * Class ProductRepositoryEloquent
 * @package Dogadamycie\Infrastructure\Persistence\Product
 */
class ProductRepositoryEloquent implements ProductRepository
{
    /**
     * @var ProductRepositoryPrettus
     */
    private $repository;

    /**
     * @var ProductFactory
     */
    private $factory;

    /**
     * ProductRepositoryEloquent constructor.
     * @param ProductRepositoryPrettus $repository
     * @param ProductFactory $factory
     */
    public function __construct(ProductRepositoryPrettus $repository, ProductFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param Id $id
     * @return Product
     * @throws InvalidCurrencyException
     */
    public function productOfId(Id $id)
    {
        /** @var  Model $productEloquent |null */
        $productEloquent = $this->repository->findWhere(['id' => $id])->first();
        if (!$productEloquent) {
            throw new ProductNotFoundException($id);
        }

        return $this->factory->fromArray($productEloquent->toArray());
    }

    /**
     * @param Product $aProduct
     */
    public function save(Product $aProduct)
    {
        $product = $this->repository->findWhere(['id' => $aProduct->getId()])->first();

        if (!$product) {
            $product = new ProductEloquent();
        }

        $product->id = $aProduct->getId();
        $product->name  = $aProduct->getName();
        $product->price = $aProduct->getPrice()->getValue();
        $product->cart_id = $aProduct->getCartId();
        $product->currency_iso_code = $aProduct->getPrice()->getCurrency();
        $product->save();
    }

    /**
     * @param Id $id
     * @return void
     */
    public function delete(Id $id)
    {
        try {
            $this->repository->delete($id);
        } catch (\Exception $e) {
            throw ProductRepositoryException::fromPrevious($e);
        }
    }

    /**
     * @param Id $id
     * @return bool
     */
    public function exist(Id $id)
    {
        $productId = $this->repository->findWhere(['id' => $id])->first();
        return !is_null($productId);
    }
}