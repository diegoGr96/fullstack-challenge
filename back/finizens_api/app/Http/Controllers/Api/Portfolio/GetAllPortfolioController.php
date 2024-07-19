<?php

namespace App\Http\Controllers\Api\Portfolio;

use App\Http\Controllers\Controller;
use App\Presenters\ApiPresenter;
use Src\Portfolio\Infrastructure\Controllers\GetAllPortfolioController as SrcGetAllPortfolioController;

class GetAllPortfolioController extends Controller
{
    public function __construct(
        public readonly SrcGetAllPortfolioController $getAllPortfolioController,
        public readonly ApiPresenter $apiPresenter,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $portfolios = $this->getAllPortfolioController->__invoke();

        $portfoliosJson = [];
        foreach ($portfolios as $portfolio) {
            $portfoliosJson[] = $portfolio->toJson();
        }
        return $this->apiPresenter->sendSuccessResponse($portfoliosJson);
    }
}
