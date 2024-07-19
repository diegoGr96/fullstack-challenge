<?php

namespace App\Http\Controllers\Api\Portfolio;

use App\Http\Controllers\Controller;
use App\Presenters\ApiPresenter;
use Src\Portfolio\Infrastructure\Controllers\FindPortfolioController as SrcFindPortfolioController;

class FindPortfolioController extends Controller
{
    public function __construct(
        public readonly SrcFindPortfolioController $findPortfolioController,
        public readonly ApiPresenter $apiPresenter,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke($id)
    {
        $result = $this->findPortfolioController->__invoke($id);

        if ($result->isError()) {
            return $this->apiPresenter->sendErrorResponse($result->getError());
        }

        return $this->apiPresenter->sendSuccessResponse($result->getData()->toJson());
    }
}
