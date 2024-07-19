<?php

namespace Src\Order\Infrastructure\Repositories;

use App\Models\Order\Order as EloquentOrder;
use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\Errors\OrderNotFound;
use Src\Order\Domain\Order;
use Src\Order\Domain\ValueObjects\OrderId;
use Src\Order\Domain\ValueObjects\OrderStatus;
use Src\Order\Domain\ValueObjects\OrderType;
use Src\Portfolio\Domain\ValueObjects\AllocationId;
use Src\Portfolio\Domain\ValueObjects\PortfolioId;
use Src\Portfolio\Domain\ValueObjects\Shares;
use Src\Shared\Domain\Result\Result;

final class EloquentOrderRepository implements OrderRepositoryContract
{
    /**
     * @return Result<FinizensError, Order>
     */
    public function find(OrderId $orderId): Result
    {
        $eloquentOrder = EloquentOrder::query()
            ->find($orderId->value);

        if (!$eloquentOrder) {
            return Result::error(new OrderNotFound);
        }

        $order = $this->mapEloquentOrderToDomainOrder($eloquentOrder);
        return Result::success($order);
    }

    private function mapEloquentOrderToDomainOrder(EloquentOrder $eloquentOrder): Order
    {
        $orderId = OrderId::create($eloquentOrder->id)->getData();
        $portfolioId = PortfolioId::create($eloquentOrder->portfolio_id)->getData();
        $allocationId = AllocationId::create($eloquentOrder->allocation_id)->getData();
        $shares = Shares::create($eloquentOrder->shares)->getData();
        $orderType = OrderType::create($eloquentOrder->type_name)->getData();
        $orderStatus = OrderStatus::create($eloquentOrder->status_name)->getData();

        $order = Order::create(
            orderId: $orderId,
            portfolioId: $portfolioId,
            allocationId: $allocationId,
            shares: $shares,
            orderType: $orderType,
            orderStatus: $orderStatus,
        );

        return $order;
    }

    public function createOrder(Order $order): void
    {
        EloquentOrder::query()
            ->updateOrCreate([
                'id' => $order->orderId->value,
            ], [
                'portfolio_id' => $order->portfolioId->value,
                'allocation_id' => $order->allocationId->value,
                'shares' => $order->shares->value,
                'type' => $order->orderType->id,
                'status' => OrderStatus::STATUSES[OrderStatus::PENDING_STATUS],
            ]);
    }

    public function updateOrderStatus(Order $order, OrderStatus $orderStatus): void
    {
        EloquentOrder::query()
            ->where('id', $order->orderId->value)
            ->update(['status' => $orderStatus->id]);
    }

    public function getNextOrderId(): OrderId
    {
        $lastEloquentOrder = EloquentOrder::query()->latest('id')->first();
        $lastId = $lastEloquentOrder->id ?? 0;
        return OrderId::create($lastId + 1)->getData();
    }
}
