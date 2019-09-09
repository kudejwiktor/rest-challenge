<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\Product\Query\ProductQuery;
use Dogadamycie\UI\Http\Errors\NotFoundResponse;
use Illuminate\Http\{JsonResponse, Request};

/**
 * Class Product
 * @package App\Http\Controllers\Product
 */
class Product extends Controller
{
    /**
     * @var ProductQuery
     */
    private $productQuery;

    /**
     * Product constructor.
     * @param ProductQuery $productQuery
     */
    public function __construct(ProductQuery $productQuery)
    {
        $this->productQuery = $productQuery;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $product = $this->productQuery->productOfId($request->id);
        if(!$product) {
            $response = new NotFoundResponse('Product could not be found', ['id' => $request->id]);
            return new JsonResponse($response, $response->getCode());
        }
        return new JsonResponse($product, 200, $this->defaultApiResponseHeaders());
    }

}