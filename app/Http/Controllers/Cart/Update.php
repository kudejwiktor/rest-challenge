<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\{
    Cart\CartApplicationService,
    Cart\Commands\UpdateCartCommand,
    Cart\Query\CartQuery,
    Command\CommandValidatorException
};
use Dogadamycie\Domain\{
    Cart\Exceptions\CartCanNotBeUpdated,
    Cart\Exceptions\CartNotFoundException
};
use Dogadamycie\UI\Http\Errors\{BadRequestResponse, UnprocessableEntityResponse};
use Illuminate\Http\{JsonResponse, Request};

/**
 * Class Update
 * @package App\Http\Controllers\Cart
 */
class Update extends Controller
{
    /**
     * @var CartApplicationService
     */
    private $cartApplicationService;

    /**
     * @var CartQuery
     */
    private $cartQuery;

    /**
     * Update constructor.
     * @param CartApplicationService $cartApplicationService
     * @param CartQuery $cartQuery
     */
    public function __construct(CartApplicationService $cartApplicationService, CartQuery $cartQuery)
    {
        $this->cartApplicationService = $cartApplicationService;
        $this->cartQuery = $cartQuery;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Dogadamycie\Domain\Common\Exceptions\InvalidCurrencyException
     */
    public function __invoke(Request $request)
    {
        $command = new UpdateCartCommand(
            $request->get('id'),
            $request->get('currency')
        );

        try {
            $id = $this->cartApplicationService->update($command);
            return new JsonResponse($this->cartQuery->cartOfId($id), 201, $this->defaultApiResponseHeaders());
        } catch (CommandValidatorException $e) {
            $response = new BadRequestResponse('Bad request', $e->getErrors());
            return new JsonResponse($response, $response->getCode());
        } catch (CartCanNotBeUpdated $e) {
            $response = new UnprocessableEntityResponse($e->getMessage(), ['id' => $command->getId()]);
            return new JsonResponse($response, $response->getCode());
        } catch (CartNotFoundException $e) {
            $response = new UnprocessableEntityResponse($e->getMessage(), [
                'id' => $command->getProductId(),
            ]);
            return new JsonResponse($response, $response->getCode());
        }
    }
}