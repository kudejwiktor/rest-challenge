<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Dogadamycie\Application\{
    Command\CommandValidatorException,
    Product\Commands\CreateProductCommand,
    Product\ProductApplicationService
};
use Dogadamycie\UI\Http\Errors\BadRequestResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Create extends Controller
{
    /**
     * @var ProductApplicationService
     */
    private $productApplicationService;

    public function __construct(ProductApplicationService $productApplicationService)
    {
        $this->productApplicationService = $productApplicationService;
    }

    public function __invoke(Request $request)
    {
        try {
            $command = new CreateProductCommand($request->get('name'), $request->get('price'), $request->get('currency'));
            $this->productApplicationService->create($command);
        } catch (CommandValidatorException $e) {
            $response = new BadRequestResponse('Bad request', $e->getErrors());
            return new JsonResponse($response, $response->getCode());
        }
    }
}