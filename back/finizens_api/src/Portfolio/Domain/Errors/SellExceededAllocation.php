<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

final class SellExceededAllocation extends FinizensError
{
    public function status(): int
    {
        return 500;
    }

    public function message(): string
    {
        return "Sell exceeded allocation.";
    }

    public function code(): string
    {
        return "FI-SELL_EXCEEDED_ALLOCATION-500";
    }
}
