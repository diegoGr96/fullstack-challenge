<?php

declare(strict_types=1);

namespace Src\Order\Domain\Services\UpdateOrderStatus;

use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\Order;
use Src\Order\Domain\Services\OrderService;
use Src\Order\Domain\ValueObjects\OrderStatus;
use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Portfolio\Domain\Entities\Allocation;
use Src\Portfolio\Domain\ValueObjects\Shares;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class UpdateSellOrderStatusStrategy implements UpdateOrderStatusStrategy {
    public function __construct(
        public readonly OrderRepositoryContract $orderRepository,
        public readonly PortfolioRepositoryContract $portfolioRepository,
    )
    {
    }

    /**
     * @return Result<FinizensError, null>
     */
    public function execute(Order $order, OrderStatus $orderStatus): Result
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
        $isValidOrderResult = $orderService->checkIfSellOrderIsValid($order, $allocation);
        if ($isValidOrderResult->isError()) {
            return $isValidOrderResult;
        }

        $remainingShares = $allocation->shares->value - $order->shares->value;
        
        if($remainingShares > 0) {
            $updatedAllocation = Allocation::create(
                allocationId: $allocation->allocationId,
                shares: Shares::create($allocation->shares->value - $order->shares->value)->getData()
            );
            $this->portfolioRepository->updateOrCreateAllocation($order->portfolioId->value, $updatedAllocation);
        } else {
            $this->portfolioRepository->deleteAllocation($order->portfolioId->value, $allocation);
        }
        
        $this->orderRepository->updateOrderStatus($order, $orderStatus);
        return Result::success(null);
    }
}