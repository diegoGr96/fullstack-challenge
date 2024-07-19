<?php

namespace App\Http\Controllers\Api\Portfolio;

use App\Http\Controllers\Controller;
use App\Presenters\ApiPresenter;
use Illuminate\Http\Request;
use Src\Portfolio\Infrastructure\Controllers\SetPortfolioController as SrcSetPortfolioController;

class SetPortfolioController extends Controller
{
    public function __construct(
        public readonly SrcSetPortfolioController $setPortfolioController,
        public readonly ApiPresenter $apiPresenter,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(int $id, Request $request)
    {
        $result = $this->setPortfolioController->__invoke($id, $request->all());

        if ($result->isError()) {
            return $this->apiPresenter->sendErrorResponse($result->getError());
        }

        return $this->apiPresenter->sendSuccessResponse($result->getData());
    }
}
