<?php

declare(strict_types=1);

namespace Src\Order\Domain\ValueObjects;

use Src\Order\Domain\Errors\InvalidOrderStatus;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class OrderStatus
{
    const PENDING_STATUS = 'pending';
    const COMPLETED_STATUS = 'completed';

    const STATUSES = [
        self::PENDING_STATUS => 1,
        self::COMPLETED_STATUS => 2,
    ];

    private function __construct(
        public readonly string $name,
        public readonly int $id,
    ) {
    }

    /**
     * @return Result<FinizensError, OrderStatus>
     */
    public static function create($type)
    {
        if (!self::validate($type)) {
            return Result::error(new InvalidOrderStatus);
        }

        $type = strtolower($type);
        return Result::success(new self(
            name: $type,
            id: self::STATUSES[$type],
        ));
    }

    public static function validate($type): bool
    {
        return is_string($type) && isset(self::STATUSES[strtolower($type)]);
    }
}
