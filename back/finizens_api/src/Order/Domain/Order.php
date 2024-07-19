<?php

declare(strict_types=1);

namespace Src\Order\Domain;

use Src\Order\Domain\ValueObjects\OrderId;
use Src\Order\Domain\ValueObjects\OrderStatus;
use Src\Order\Domain\ValueObjects\OrderType;
use Src\Portfolio\Domain\ValueObjects\AllocationId;
use Src\Portfolio\Domain\ValueObjects\PortfolioId;
use Src\Portfolio\Domain\ValueObjects\Shares;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class Order
{
    private function __construct(
        public readonly OrderId $orderId,
        public readonly PortfolioId $portfolioId,
        public readonly AllocationId $allocationId,
        public readonly Shares $shares,
        public readonly OrderType $orderType,
        public readonly OrderStatus $orderStatus,
    ) {
    }

    /**
     * @return Result<FinizensError, Order>
     */
    public static function fromArray(array $params): Result
    {
        $orderIdResult = OrderId::create($params['id'] ?? null);
        if ($orderIdResult->isError()) {
            return $orderIdResult;
        }

        $portfolioIdResult = PortfolioId::create($params['portfolio'] ?? null);
        if ($portfolioIdResult->isError()) {
            return $portfolioIdResult;
        }

        $allocationIdResult = AllocationId::create($params['allocation'] ?? null);
        if ($allocationIdResult->isError()) {
            return $allocationIdResult;
        }

        $sharesResult = Shares::create($params['shares'] ?? null);
        if ($sharesResult->isError()) {
            return $sharesResult;
        }

        $orderTypeResult = OrderType::create($params['type'] ?? null);
        if ($orderTypeResult->isError()) {
            return $orderTypeResult;
        }

        $orderStatusResult = isset($params['status'])
            ? OrderStatus::create($params['status'] ?? null)
            : OrderStatus::create(OrderStatus::PENDING_STATUS);

        if ($orderTypeResult->isError()) {
            return $orderStatusResult;
        }

        $allocation = self::create(
            orderId: $orderIdResult->getData(),
            portfolioId: $portfolioIdResult->getData(),
            allocationId: $allocationIdResult->getData(),
            shares: $sharesResult->getData(),
            orderType: $orderTypeResult->getData(),
            orderStatus: $orderStatusResult->getData()
        );
        return Result::success($allocation);
    }

    public static function create(
        OrderId $orderId,
        PortfolioId $portfolioId,
        AllocationId $allocationId,
        Shares $shares,
        OrderType $orderType,
        OrderStatus $orderStatus,
    ): self {
        return new self(
            orderId: $orderId,
            portfolioId: $portfolioId,
            allocationId: $allocationId,
            shares: $shares,
            orderType: $orderType,
            orderStatus: $orderStatus,
        );
    }
}
