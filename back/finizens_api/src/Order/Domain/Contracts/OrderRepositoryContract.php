<?php

declare(strict_types=1);

namespace Src\Order\Domain\Contracts;

use Src\Order\Domain\Order;
use Src\Order\Domain\ValueObjects\OrderId;
use Src\Order\Domain\ValueObjects\OrderStatus;
use Src\Shared\Domain\Result\Result;

interface OrderRepositoryContract
{
    /**
     * @return Result<FinizensError, Order>
     */
    public function find(OrderId $orderId): Result;
    public function createOrder(Order $order): void;
    public function updateOrderStatus(Order $order, OrderStatus $orderStatus): void;
}
