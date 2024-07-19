<?php

declare(strict_types=1);

namespace Src\Order\Domain\ValueObjects;

use Src\Order\Domain\Errors\InvalidOrderType;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class OrderType
{
    const BUY_TYPE = 'buy';
    const SELL_TYPE = 'sell';

    const ORDER_TYPES = [
        self::BUY_TYPE => 1,
        self::SELL_TYPE => 2,
    ];

    private function __construct(
        public readonly string $name,
        public readonly int $id,
    ) {
    }

    /**
     * @return Result<FinizensError, OrderType>
     */
    public static function create($type)
    {
        if (!self::validate($type)) {
            return Result::error(new InvalidOrderType);
        }

        $type = strtolower($type);
        return Result::success(new self(
            name: $type,
            id: self::ORDER_TYPES[$type],
        ));
    }

    /**
     * @return Result<FinizensError, OrderType>
     */
    public static function createFromInt($type)
    {
        if (!self::validate($type)) {
            return Result::error(new InvalidOrderType);
        }

        $type = strtolower($type);
        return Result::success(new self(
            name: $type,
            id: self::ORDER_TYPES[$type],
        ));
    }

    public static function validate($type): bool
    {
        return is_string($type) && isset(self::ORDER_TYPES[strtolower($type)]);
    }
}
