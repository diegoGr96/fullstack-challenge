<?php

declare(strict_types=1);

namespace Src\Order\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

final class OrderAlreadyCompleted extends FinizensError
{
    public function status(): int
    {
        return 400;
    }

    public function message(): string
    {
        return "Order is already completed";
    }

    public function code(): string
    {
        return "FI-ORDER_ALREADY_COMPLETED-400";
    }
}
