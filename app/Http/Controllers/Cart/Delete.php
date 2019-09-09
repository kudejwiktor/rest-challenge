<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Dogadamycie\Domain\Cart\Exceptions\CartNotFoundException;
use Dogadamycie\Application\{
    Cart\CartApplicationService,
    Cart\Commands\DeleteCartCommand,
    Command\CommandValidatorException
};
use Dogadamycie\UI\Http\Errors\{BadRequestResponse, UnprocessableEntityResponse};
use Illuminate\Http\{JsonResponse, Request};

/**
 * Class Delete
 * @package App\Http\Controllers\Cart
 */
class Delete extends Controller
{
    /**
     * @var CartApplicationService
     */
    private $cartApplicationService;

    /**
     * Delete constructor.
     * @param CartApplicationService $cartApplicationService
     */
    public function __construct(CartApplicationService $cartApplicationService)
    {
        $this->cartApplicationService = $cartApplicationService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $command = new DeleteCartCommand($request->id);
        try {
            $this->cartApplicationService->delete($command);
            return new JsonResponse($request->id, 200, $this->defaultApiResponseHeaders());
        } catch (CommandValidatorException $e) {
            $response = new BadRequestResponse('Bad request', $e->getErrors());
            return new JsonResponse($response, $response->getCode());
        } catch (CartNotFoundException $e) {
            $response = new UnprocessableEntityResponse($e->getMessage(), [
                'id' => $command->getId(),
            ]);
            return new JsonResponse($response, $response->getCode());
        }
    }
}