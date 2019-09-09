<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\Cart\Query\CartQuery;
use Dogadamycie\UI\Http\Errors\NotFoundResponse;
use Illuminate\Http\{JsonResponse, Request};

/**
 * Class Cart
 * @package App\Http\Controllers\Cart
 */
class Cart extends Controller
{
    /**
     * @var CartQuery
     */
    private $cartQuery;

    /**
     * Cart constructor.
     * @param CartQuery $cartQuery
     */
    public function __construct(CartQuery $cartQuery)
    {
        $this->cartQuery = $cartQuery;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $cart = $this->cartQuery->cartOfId($request->id);
        if (!$cart) {
            $response = new NotFoundResponse('Cart could not be found', ['id' => $request->id]);
            return new JsonResponse($response, $response->getCode());
        }

        return new JsonResponse($cart, 200, $this->defaultApiResponseHeaders());
    }
}