<?php

declare(strict_types=1);

namespace Src\Order\Domain\Services\CreateOrder;

use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\Order;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class CreateBuyOrderStrategy implements CreateOrderStrategy {
    public function __construct(
        public readonly OrderRepositoryContract $orderRepository,
    )
    {
    }

    /**
     * @return Result<FinizensError, null>
     */
    public function execute(Order $order): Result
    {
        $this->orderRepository->createOrder($order);
        return Result::success(null);
    }
}