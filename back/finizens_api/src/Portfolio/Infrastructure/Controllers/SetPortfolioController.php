<?php

declare(strict_types=1);

namespace Src\Portfolio\Infrastructure\Controllers;

use Src\Portfolio\Application\Services\SetPortfolioService;
use Src\Portfolio\Infrastructure\Repositories\EloquentPortfolioRepository;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class SetPortfolioController
{
    public function __construct(
        public readonly EloquentPortfolioRepository $eloquentPortfolioRepository,
    )
    {
    }

    /**
     * @return Result<FinizensError, string[]>
     */
    public function __invoke($id, array $params)
    {
        $setPortfolioService = new SetPortfolioService(
            $this->eloquentPortfolioRepository
        );
        $setPortfolioResult = $setPortfolioService->__invoke($id, $params);
        return $setPortfolioResult;
    }
}
