<?php

declare(strict_types=1);

namespace Src\Order\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

final class InvalidOrderStatus extends FinizensError
{
    public function status(): int
    {
        return 400;
    }

    public function message(): string
    {
        return "Invalid Order Status";
    }

    public function code(): string
    {
        return "FI-ORDER_STATUS-400";
    }
}
