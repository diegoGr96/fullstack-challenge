<?php

declare(strict_types=1);

namespace Src\Order\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

final class InvalidOrderId extends FinizensError
{
    public function status(): int
    {
        return 400;
    }

    public function message(): string
    {
        return "Invalid Order ID";
    }

    public function code(): string
    {
        return "FI-ORDER_ID-400";
    }
}
