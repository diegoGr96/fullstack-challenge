<?php

declare(strict_types=1);

namespace Src\Order\Domain\Services\CreateOrder;

use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\Order;
use Src\Order\Domain\Services\OrderService;
use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Portfolio\Domain\Errors\SellExceededAllocation;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class CreateSellOrderStrategy implements CreateOrderStrategy {
    public function __construct(
        public readonly OrderRepositoryContract $orderRepository,
        public readonly PortfolioRepositoryContract $portfolioRepository,
    )
    {
    }

    /**
     * @return Result<FinizensError, null>
     */
    public function execute(
        Order $order
    ): Result
    {
        $findAllocationResult = $this->portfolioRepository->findAllocation(
            $order->allocationId,
            $order->portfolioId
        );
        if ($findAllocationResult->isError()) {
            return $findAllocationResult;
        }

        $allocation = $findAllocationResult->getData();

        $orderService = new OrderService();
        $isValidOrderResult = $orderService->checkIfSellOrderIsValid(
            order: $order,
            allocation: $allocation,
        );
        if ($isValidOrderResult->isError()) {
            return $isValidOrderResult;
        }

        $this->orderRepository->createOrder($order);
        return Result::success(null);
    }
}