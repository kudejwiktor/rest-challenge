<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\{
    Command\CommandValidatorException,
    Product\Commands\DeleteProductCommand,
    Product\ProductApplicationService
};
use Dogadamycie\Domain\{
    Product\Exceptions\ProductNotFoundException,
    Product\Exceptions\ProductRepositoryException
};
use Dogadamycie\UI\Http\Errors\{BadRequestResponse,
    InternalServerErrorResponse,
    UnprocessableEntityResponse};
use Illuminate\Http\{JsonResponse, Request};

/**
 * Class Delete
 * @package App\Http\Controllers\Product
 */
class Delete extends Controller
{
    /**
     * @var ProductApplicationService
     */
    private $productApplicationService;

    /**
     * Delete constructor.
     * @param ProductApplicationService $productApplicationService
     */
    public function __construct(ProductApplicationService $productApplicationService)
    {
        $this->productApplicationService = $productApplicationService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        try {
            $this->productApplicationService->delete(new DeleteProductCommand($request->id));
        } catch (CommandValidatorException $e) {
            $response = new BadRequestResponse('Bad Request', $e->getErrors());
            return new JsonResponse($response, $response->getCode());
        } catch (ProductNotFoundException $e) {
            $response = new UnprocessableEntityResponse($e->getMessage(), ['id' => $request->id]);
            return new JsonResponse($response, $response->getCode());
        } catch (ProductRepositoryException $e) {
            $response = new InternalServerErrorResponse();
            return new JsonResponse($response, $response->getCode());
        }
    }
}