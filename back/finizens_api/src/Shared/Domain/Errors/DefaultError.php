<?php

declare(strict_types=1);

namespace Src\Shared\Domain\Errors;

use Src\Shared\Domain\Errors\FinizensError;

class DefaultError extends FinizensError
{
    public function __construct(
        public readonly ?string $message = null,
        public readonly int    $status = 422,
        public readonly ?string $code = null,
    ) {
    }

    public function status(): int
    {
        return $this->status;
    }

    public function message(): ?string
    {
        return $this->message;
    }

    public function code(): ?string
    {
        return $this->code;
    }
}
