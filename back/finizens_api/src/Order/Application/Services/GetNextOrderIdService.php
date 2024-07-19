<?php

declare(strict_types=1);

namespace Src\Order\Application\Services;

use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\ValueObjects\OrderId;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

class GetNextOrderIdService
{

    public function __construct(
        public readonly OrderRepositoryContract $orderRepository,
    )
    {
    }

    /**
     * @return Result<FinizensError, OrderId>
     */
    public function __invoke()
    {
        $nextOrderId = $this->orderRepository->getNextOrderId();
        return Result::success($nextOrderId);
    }
}
