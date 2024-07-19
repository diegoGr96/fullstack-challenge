<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Presenters\ApiPresenter;
use Illuminate\Http\Request;
use Src\Order\Infrastructure\Controllers\GetNextOrderIdController as SrcGetNextOrderIdController;

class GetNextOrderIdController extends Controller
{
    public function __construct(
        public readonly SrcGetNextOrderIdController $getNextOrderIdController,
        public readonly ApiPresenter $apiPresenter,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $result = $this->getNextOrderIdController->__invoke();

        if ($result->isError()) {
            return $this->apiPresenter->sendErrorResponse($result->getError());
        }

        return $this->apiPresenter->sendSuccessResponse($result->getData());
    }
}