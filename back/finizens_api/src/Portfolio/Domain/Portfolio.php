<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain;

use Src\Order\Domain\Order;
use Src\Order\Domain\ValueObjects\OrderList;
use Src\Portfolio\Domain\Entities\Allocation;
use Src\Portfolio\Domain\ValueObjects\AllocationList;
use Src\Portfolio\Domain\ValueObjects\PortfolioId;

final class Portfolio
{
    private function __construct(
        public readonly PortfolioId $portfolioId,
        public readonly AllocationList $allocationList,
        public readonly OrderList $orderList,
    ) {
    }

    public static function create(
        PortfolioId $portfolioId, 
        AllocationList $allocationList,
        OrderList $orderList,
        ): self
    {
        return new self(
            portfolioId: $portfolioId,
            allocationList: $allocationList,
            orderList: $orderList,
        );
    }

    public function toJson()
    {
        return [
            'id' => $this->portfolioId->value,
            'allocations' => array_map(
                fn (Allocation $allocation) => [
                    'id' => $allocation->allocationId->value,
                    'shares' => $allocation->shares->value
                ],
                $this->allocationList->items()
            ),
            'orders' => array_map(
                fn (Order $order) => [
                    'id' => $order->orderId->value,
                    'portfolio_id' => $order->portfolioId->value,
                    'allocation_id' => $order->allocationId->value,
                    'shares' => $order->shares->value,
                    'type' => $order->orderType->name,
                    'status' => $order->orderStatus->name,
                ],
                $this->orderList->items()
            ),
        ];
    }
}
