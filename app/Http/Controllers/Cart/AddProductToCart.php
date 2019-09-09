<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\{
    Cart\CartApplicationService,
    Cart\Commands\AddProductCommand,
    Cart\Query\CartQuery,
    Command\CommandValidatorException
};
use Dogadamycie\Domain\{
    Cart\Exceptions\CartNotFoundException,
    Cart\Exceptions\ProductAlreadyExistInCart,
    Cart\Exceptions\ProductDoesNotMatchCartCurrency,
    Product\Exceptions\ProductNotFoundException
};
use Dogadamycie\UI\Http\Errors\{BadRequestResponse, NotFoundResponse, UnprocessableEntityResponse};
use Illuminate\Http\{JsonResponse, Request};

/**
 * Class AddProductToCart
 * @package App\Http\Controllers\Cart
 */
class AddProductToCart extends Controller
{
    /**
     * @var CartApplicationService
     */
    private $service;
    /**
     * @var CartQuery
     */
    private $cartQuery;

    /**
     * AddProductToCart constructor.
     * @param CartApplicationService $service
     * @param CartQuery $cartQuery
     */
    public function __construct(CartApplicationService $service, CartQuery $cartQuery)
    {
        $this->service = $service;
        $this->cartQuery = $cartQuery;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Dogadamycie\Domain\Common\Exceptions\InvalidCurrencyException
     */
    public function __invoke(Request $request)
    {
        try {
            $command = new AddProductCommand($request->get('cart_id'), $request->get('product_id'));
            $this->service->addProduct($command);
            return new JsonResponse($this->cartQuery->cartOfId($command->getCartId()), 200, $this->defaultApiResponseHeaders());
        } catch (CommandValidatorException $e) {
            $response = new BadRequestResponse('Bad request', $e->getErrors());
            return new JsonResponse($response, $response->getCode());
        } catch (CartNotFoundException $e) {
            $response = new NotFoundResponse($e->getMessage(), ['id' => $command->getCartId()]);
            return new JsonResponse($response, $response->getCode());
        } catch (ProductNotFoundException $e) {
            $response = new NotFoundResponse($e->getMessage(), ['id' => $command->getProductId()]);
            return new JsonResponse($response, $response->getCode());
        } catch (ProductAlreadyExistInCart $e) {
            $response = new UnprocessableEntityResponse($e->getMessage(), [
                'product_id' => $command->getProductId(),
                'cart_id' => $command->getCartId()
            ]);
            return new JsonResponse($response, $response->getCode());
        } catch (ProductDoesNotMatchCartCurrency $e) {
            $response = new UnprocessableEntityResponse($e->getMessage(), [
                'product_id' => $command->getProductId(),
                'cart_id' => $command->getCartId()
            ]);
            return new JsonResponse($response, $response->getCode());
        }
    }
}