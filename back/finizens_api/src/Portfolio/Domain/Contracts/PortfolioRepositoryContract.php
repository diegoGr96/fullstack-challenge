<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\Contracts;

use Src\Portfolio\Domain\Entities\Allocation;
use Src\Portfolio\Domain\Portfolio;
use Src\Portfolio\Domain\ValueObjects\AllocationId;
use Src\Portfolio\Domain\ValueObjects\AllocationList;
use Src\Portfolio\Domain\ValueObjects\PortfolioId;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

interface PortfolioRepositoryContract
{
    public function getAll(): array;
    /**
     * @return Result<FinizensError, Portfolio>
     */
    public function find(PortfolioId $portfolioId): Result;
    public function setPortfolio(int $id, AllocationList $allocations): void;
    public function updateOrCreateAllocation(int $id, Allocation $allocation): void;
    public function deleteAllocation(int $id, Allocation $allocation): void;
    /**
     * @return Result<FinizensError, Allocation>
     */
    public function findAllocation(
        AllocationId $allocationId,
        PortfolioId $portfolioId
    ): Result;

    public function getNextAllocationId(): AllocationId;
}
