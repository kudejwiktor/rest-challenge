<?php

namespace App\Repositories;

use App\Model\Product;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductRepository
 * @package App\Repositories
 */
class ProductRepositoryEloquent extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Product::class;
    }
}