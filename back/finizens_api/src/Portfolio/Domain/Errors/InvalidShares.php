<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

final class InvalidShares extends FinizensError
{
    public function status(): int
    {
        return 400;
    }

    public function message(): string
    {
        return "Invalid Shares";
    }

    public function code(): string
    {
        return "FI-SHARES-400";
    }
}
