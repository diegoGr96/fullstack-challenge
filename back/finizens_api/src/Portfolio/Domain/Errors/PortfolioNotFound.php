<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

final class PortfolioNotFound extends FinizensError
{
    public function status(): int
    {
        return 404;
    }

    public function message(): string
    {
        return "Portfolio not found.";
    }

    public function code(): string
    {
        return "FI-PORTFOLIO-404";
    }
}
