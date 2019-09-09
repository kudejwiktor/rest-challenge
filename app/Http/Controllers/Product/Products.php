<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\Product\Query\ProductQuery;
use Illuminate\Http\JsonResponse;

class Products extends Controller
{
    /**
     * @var ProductQuery
     */
    private $productQuery;

    public function __construct(ProductQuery $productQuery)
    {
        $this->productQuery = $productQuery;
    }

    public function __invoke()
    {
        return new JsonResponse($this->productQuery->all(), 200, $this->defaultApiResponseHeaders());
    }

}