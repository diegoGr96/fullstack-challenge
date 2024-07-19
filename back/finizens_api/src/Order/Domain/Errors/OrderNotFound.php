<?php

declare(strict_types=1);

namespace Src\Order\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

final class OrderNotFound extends FinizensError
{
    public function status(): int
    {
        return 404;
    }

    public function message(): string
    {
        return "Order not found.";
    }

    public function code(): string
    {
        return "FI-ORDER-404";
    }
}
