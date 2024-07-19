<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Presenters\ApiPresenter;
use Illuminate\Http\Request;
use Src\Order\Infrastructure\Controllers\CreateOrderController as SrcCreateOrderController;

class CreateOrderController extends Controller
{
    public function __construct(
        public readonly SrcCreateOrderController $createOrderController,
        public readonly ApiPresenter $apiPresenter,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $result = $this->createOrderController->__invoke($request->all());

        if ($result->isError()) {
            return $this->apiPresenter->sendErrorResponse($result->getError());
        }

        return $this->apiPresenter->sendSuccessResponse($result->getData(), 201);
    }
}
