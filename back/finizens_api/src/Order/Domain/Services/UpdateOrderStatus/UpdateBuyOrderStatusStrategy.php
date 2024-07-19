<?php

declare(strict_types=1);

namespace Src\Order\Domain\Services\UpdateOrderStatus;

use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\Order;
use Src\Order\Domain\ValueObjects\OrderStatus;
use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Portfolio\Domain\Entities\Allocation;
use Src\Portfolio\Domain\Errors\AllocationNotFound;
use Src\Portfolio\Domain\ValueObjects\Shares;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class UpdateBuyOrderStatusStrategy implements UpdateOrderStatusStrategy {
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

        $findAllocationError = $findAllocationResult->getError();
        if ($findAllocationError && $findAllocationError->code() !== AllocationNotFound::CODE) {
            return $findAllocationResult;
        }

        if($findAllocationError) {
            $newAllocation = Allocation::create($order->allocationId, $order->shares);
        } else{
            $allocation = $findAllocationResult->getData();
            $newShares = Shares::create($allocation->shares->value + $order->shares->value)->getData();
            $newAllocation = Allocation::create($allocation->allocationId, $newShares);
        }
        $this->portfolioRepository->updateOrCreateAllocation($order->portfolioId->value, $newAllocation);

        $this->orderRepository->updateOrderStatus($order, $orderStatus);
        return Result::success(null);
    }
}