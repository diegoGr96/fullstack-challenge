<?php

declare(strict_types=1);

namespace Src\Order\Domain\Services\CreateOrder;

use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\Order;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

interface CreateOrderStrategy {
    /**
     * @return Result<FinizensError, null>
     */
    public function execute(Order $order): Result;
}