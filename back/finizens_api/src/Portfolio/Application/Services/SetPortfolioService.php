<?php

declare(strict_types=1);

namespace Src\Portfolio\Application\Services;

use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Portfolio\Domain\Entities\Allocation;
use Src\Portfolio\Domain\ValueObjects\AllocationList;
use Src\Shared\Domain\Errors\DefaultError;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class SetPortfolioService
{
    public function __construct(
        public readonly PortfolioRepositoryContract $portfolioRepository,
    ) {
    }

    /**
     * @return Result<FinizensError, array>
     */
    public function __invoke($id, array $params): Result
    {
        $allocations = AllocationList::create();
        foreach ($params['allocations'] ?? [] as $allocationParams) {
            $updateOrCreateAllocationResult = Allocation::fromArray($allocationParams);
            if($updateOrCreateAllocationResult->isError()) {
                return Result::error(new DefaultError(status: 400));
            }

            $allocation = $updateOrCreateAllocationResult->getData();
            $allocations->add($allocation);
        }

        $this->portfolioRepository->setPortfolio($id, $allocations);
        return Result::success(null);
    }
}
