<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Presenters\ApiPresenter;
use Illuminate\Http\Request;
use Src\Order\Infrastructure\Controllers\UpdateOrderStatusController as SrcUpdateOrderStatusController;

class UpdateOrderStatusController extends Controller
{
    public function __construct(
        public readonly SrcUpdateOrderStatusController $updateOrderStatusController,
        public readonly ApiPresenter $apiPresenter,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke($id, Request $request)
    {
        $result = $this->updateOrderStatusController->__invoke($id, $request->all());

        if ($result->isError()) {
            return $this->apiPresenter->sendErrorResponse($result->getError());
        }

        return $this->apiPresenter->sendSuccessResponse($result->getData());
    }
}
