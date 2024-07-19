<?php

declare(strict_types=1);

namespace Src\Portfolio\Infrastructure\Controllers;

use Src\Portfolio\Application\Services\FindPortfolioService;
use Src\Portfolio\Domain\Portfolio;
use Src\Portfolio\Infrastructure\Repositories\EloquentPortfolioRepository;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class FindPortfolioController
{
    public function __construct(
        public readonly EloquentPortfolioRepository $eloquentPortfolioRepository,
    )
    {
    }

    /**
     * @return Result<FinizensError, Portfolio>
     */
    public function __invoke($id)
    {
        $findPortfolioService = new FindPortfolioService(
            $this->eloquentPortfolioRepository
        );
        $findPortfolioResult = $findPortfolioService->__invoke($id);
        if($findPortfolioResult->isError()) {
            return $findPortfolioResult;
        }

        return $findPortfolioResult;
    }
}
