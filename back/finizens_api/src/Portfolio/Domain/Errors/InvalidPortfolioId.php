<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

final class InvalidPortfolioId extends FinizensError
{
    public function status(): int
    {
        return 400;
    }

    public function message(): string
    {
        return "Invalid Portfolio ID";
    }

    public function code(): string
    {
        return "FI-PORTFOLIO_ID-400";
    }
}
