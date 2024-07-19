<?php

declare(strict_types=1);

namespace Src\Order\Domain\Services\UpdateOrderStatus;

use Src\Order\Domain\Order;
use Src\Order\Domain\ValueObjects\OrderStatus;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

interface UpdateOrderStatusStrategy {
    /**
     * @return Result<FinizensError, null>
     */
    public function execute(Order $order, OrderStatus $orderStatus): Result;
}