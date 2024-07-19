<?php

declare(strict_types=1);

namespace Src\Portfolio\Application\Services;

use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;

final class GetAllPortfolioService
{
    public function __construct(
        public readonly PortfolioRepositoryContract $portfolioRepository,
    ) {
    }

    public function __invoke(): array
    {
        return $this->portfolioRepository->getAll();
    }
}
