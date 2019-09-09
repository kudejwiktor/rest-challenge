<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\Cart\Query\CartQuery;
use Dogadamycie\UI\Http\Errors\NotFoundResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Carts extends Controller
{
    /**
     * @var CartQuery
     */
    private $cartQuery;

    public function __construct(CartQuery $cartQuery)
    {
        $this->cartQuery = $cartQuery;
    }

    public function __invoke(Request $request)
    {
        $carts = $this->cartQuery->all();
        if (!$carts) {
            $response = new NotFoundResponse('Carts could not be found', []);
            return new JsonResponse($response, $response->getCode());
        }

        return new JsonResponse($carts, 200, $this->defaultApiResponseHeaders());
    }
}