<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\Entities;

use Src\Portfolio\Domain\ValueObjects\AllocationId;
use Src\Portfolio\Domain\ValueObjects\Shares;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class Allocation
{
    private function __construct(
        public readonly AllocationId $allocationId,
        public readonly Shares $shares,
    ) {
    }


    /**
     * @return Result<FinizensError, Allocation>
     */
    public static function fromArray(array $params): Result
    {
        $allocationIdResult = AllocationId::create($params['id'] ?? null);
        if($allocationIdResult->isError()) {
            return $allocationIdResult;
        }

        $sharesResult = Shares::create($params['shares'] ?? null);
        if ($sharesResult->isError()) {
            return $sharesResult;
        }

        $allocation = self::create(
            allocationId: $allocationIdResult->getData(),
            shares: $sharesResult->getData(),
        );  
        return Result::success($allocation);
    }

    public static function create(
        AllocationId $allocationId,
        Shares $shares,
    ): self {
        return new self(
            allocationId: $allocationId,
            shares: $shares,
        );
    }
}
