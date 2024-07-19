<?php

declare(strict_types=1);

namespace Src\Portfolio\Infrastructure\Controllers;

use Src\Portfolio\Application\Services\GetAllPortfolioService;
use Src\Portfolio\Infrastructure\Repositories\EloquentPortfolioRepository;

final class GetAllPortfolioController
{
    public function __construct(
        public readonly EloquentPortfolioRepository $eloquentPortfolioRepository,
    )
    {
    }

    public function __invoke(): array
    {
        $getAllPortfolioService = new GetAllPortfolioService(
            $this->eloquentPortfolioRepository
        );
        return $getAllPortfolioService->__invoke();
    }
}
