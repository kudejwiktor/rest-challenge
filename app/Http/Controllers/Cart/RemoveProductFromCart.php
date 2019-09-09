<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\{
    Cart\CartApplicationService,
    Cart\Commands\RemoveProductCommand,
    Cart\Commands\RemoveProductValidator,
    Cart\Query\CartQuery,
    Command\CommandValidator,
    Command\CommandValidatorException
};
use Dogadamycie\Domain\{
    Cart\Exceptions\CartNotFoundException,
    Cart\Exceptions\ProductNotFoundInCart,
    Common\Exceptions\InvalidIdException,
    Common\Id,
    Product\Exceptions\ProductNotFoundException,
    Product\ProductRepository
};
use Dogadamycie\UI\Http\Errors\{BadRequestResponse, UnprocessableEntityResponse};
use Illuminate\Http\{JsonResponse, Request};

/**
 * Class RemoveProductFromCart
 * @package App\Http\Controllers\Cart
 */
class RemoveProductFromCart extends Controller
{
    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CartQuery
     */
    private $cartQuery;

    /**
     * @var CommandValidator
     */
    private $validator;

    /**
     * RemoveProductFromCart constructor.
     * @param CartApplicationService $cartService
     * @param CartQuery $cartQuery
     * @param ProductRepository $productRepository
     */
    public function __construct(CartApplicationService $cartService, CartQuery $cartQuery, ProductRepository $productRepository, CommandValidator $validator)
    {
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
        $this->cartQuery = $cartQuery;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws
     */
    public function __invoke(Request $request)
    {
        try {
            $command = new RemoveProductCommand($request->id);
            $validator = new RemoveProductValidator($this->validator);
            $validator->validate($command);

            $cartId = $this->productRepository->productOfId(Id::fromString($command->getProductId()))->getCartId();
            $this->cartService->removeProduct($command);
            return new JsonResponse($this->cartQuery->cartOfId($cartId), 200, $this->defaultApiResponseHeaders());
        } catch (CommandValidatorException $e) {
            $response = new BadRequestResponse('Bad request', $e->getErrors());
            return new JsonResponse($response, $response->getCode());
        } catch (CartNotFoundException $e) {
            $response = new UnprocessableEntityResponse($e->getMessage(), [
                'cart_id' => $command->getProductId(),
            ]);
            return new JsonResponse($response, $response->getCode());
        } catch (ProductNotFoundException$e) {
            $response = new UnprocessableEntityResponse(
                "Product (id = " . $command->getProductId() . ") not found in cart", [
                'product_id' => $command->getProductId(),
            ]);
            return new JsonResponse($response, $response->getCode());
        } catch (ProductNotFoundInCart $e) {
            $response = new UnprocessableEntityResponse($e->getMessage(), [
                'product_id' => $command->getProductId(),
            ]);
            return new JsonResponse($response, $response->getCode());
        } catch (InvalidIdException $e) {
            $response = new UnprocessableEntityResponse(
                "Product (id = " . $command->getProductId() . ") not found in cart", [
                'product_id' => $command->getProductId(),
            ]);
            return new JsonResponse($response, $response->getCode());
        }
    }
}