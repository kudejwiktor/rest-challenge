<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\{
    Command\CommandValidatorException,
    Product\Commands\UpdateProductCommand,
    Product\ProductApplicationService
};
use Dogadamycie\Domain\{
    Product\Exceptions\ProductCurrencyImmutableException,
    Product\Exceptions\ProductNotFoundException
};
use Dogadamycie\UI\Http\Errors\{BadRequestResponse, NotFoundResponse, UnprocessableEntityResponse};
use Illuminate\Http\{JsonResponse, Request};

/**
 * Class Update
 * @package App\Http\Controllers\Product
 */
class Update extends Controller
{
    /**
     * @var ProductApplicationService
     */
    private $productApplicationService;

    /**
     * Update constructor.
     * @param ProductApplicationService $productApplicationService
     */
    public function __construct(ProductApplicationService $productApplicationService)
    {
        $this->productApplicationService = $productApplicationService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Dogadamycie\Domain\Common\Exceptions\InvalidCurrencyException
     */
    public function __invoke(Request $request)
    {
        $command = new UpdateProductCommand(
            $request->get('id'),
            $request->get('name'),
            $request->get('price'),
            $request->get('currency')
        );
        try {
            $this->productApplicationService->update($command);
        } catch (CommandValidatorException $e) {
            $response = new BadRequestResponse('Bad request', $e->getErrors());
            return new JsonResponse($response, $response->getCode());
        } catch (ProductNotFoundException $e) {
            $response = new NotFoundResponse($e->getMessage(), ['id' => $request->get('id')]);
            return new JsonResponse($response, $response->getCode());
        } catch (ProductCurrencyImmutableException $e) {
            $response = new UnprocessableEntityResponse($e->getMessage(), ['id' => $request->get('id')]);
            return new JsonResponse($response, $response->getCode());
        }
    }
}