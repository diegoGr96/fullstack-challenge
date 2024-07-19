<?php

declare(strict_types=1);

namespace Src\Portfolio\Application\Services;

use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Portfolio\Domain\Portfolio;
use Src\Portfolio\Domain\ValueObjects\PortfolioId;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class FindPortfolioService
{
    public function __construct(
        public readonly PortfolioRepositoryContract $portfolioRepository,
    ) {
    }

    /**
     * @return Result<FinizensError, Portfolio>
     */
    public function __invoke($id): Result
    {
        $portfolioIdResult = PortfolioId::create($id);
        if ($portfolioIdResult->isError()) {
            return $portfolioIdResult;
        }

        $portfolioResult = $this->portfolioRepository->find($portfolioIdResult->getData());
        return $portfolioResult;
    }
}
