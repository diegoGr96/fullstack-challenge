<?php

declare(strict_types=1);

namespace Src\Order\Domain\ValueObjects;

use Src\Order\Domain\Errors\InvalidOrderId;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class OrderId
{
    private function __construct(
        public readonly int $value
    ) {
    }

    /**
     * @return Result<FinizensError, OrderId>
     */
    public static function create($id)
    {
        if (!self::validate($id)) {
            return Result::error(new InvalidOrderId);
        }

        return Result::success(new self(intval($id)));
    }

    public static function validate($id): int | false
    {
        $options = array(
            'options' => array(
                'min_range' => 1,
            )
        );

        return filter_var($id, FILTER_VALIDATE_INT, $options);
    }
}
