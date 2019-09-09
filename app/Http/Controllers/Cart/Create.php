<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Dogadamycie\UI\Http\Errors\BadRequestResponse;
use Dogadamycie\Application\{
    Cart\CartApplicationService,
    Cart\Commands\CreateCartCommand,
    Cart\Query\CartQuery,
    Command\CommandValidatorException
};
use Illuminate\Http\{JsonResponse, Request};

/**
 * Class Create
 * @package App\Http\Controllers\Cart
 */
class Create extends Controller
{
    /**
     * @var CartQuery
     */
    private $cartQuery;

    /**
     * @var CartApplicationService
     */
    private $cartApplicationService;

    /**
     * Create constructor.
     * @param CartApplicationService $cartApplicationService
     * @param CartQuery $cartQuery
     */
    public function __construct(CartApplicationService $cartApplicationService, CartQuery $cartQuery)
    {
        $this->cartQuery = $cartQuery;
        $this->cartApplicationService = $cartApplicationService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Dogadamycie\Domain\Common\Exceptions\InvalidCurrencyException
     */
    public function __invoke(Request $request)
    {
        $command = new CreateCartCommand($request->get('currency'));
        try {
            $id = $this->cartApplicationService->create($command);
            return new JsonResponse($this->cartQuery->cartOfId($id), 201, $this->defaultApiResponseHeaders());
        } catch (CommandValidatorException $e) {
            $response = new BadRequestResponse('Bad request', $e->getErrors());
            return new JsonResponse($response, $response->getCode());
        }
    }
}