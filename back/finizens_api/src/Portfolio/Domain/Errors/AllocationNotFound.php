<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

final class AllocationNotFound extends FinizensError
{
    const CODE = "FI-ALLOCATION-404";

    public function status(): int
    {
        return 404;
    }

    public function message(): string
    {
        return "Allocation not found.";
    }

    public function code(): string
    {
        return "FI-ALLOCATION-404";
    }
}
