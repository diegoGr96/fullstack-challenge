<?php

declare(strict_types=1);

namespace Src\Order\Domain\Services;

use Src\Order\Domain\Order;
use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Portfolio\Domain\Entities\Allocation;
use Src\Portfolio\Domain\Errors\SellExceededAllocation;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class OrderService
{
    public function __construct(
    ) {
    }

    /**
     * @return Result<FinizensError, null>
     */
    public function checkIfSellOrderIsValid(
        Order $order,
        Allocation $allocation,
    ): Result {
        if ($allocation->shares->value < $order->shares->value) {
            return Result::error(new SellExceededAllocation);
        }

        return Result::success(null);
    }
    
}
