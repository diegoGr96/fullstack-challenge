<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\ValueObjects;

use Src\Portfolio\Domain\Errors\InvalidAllocationId;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class AllocationId
{
    private function __construct(
        public readonly int $value
    ) {
    }

    /**
     * @return Result<FinizensError, AllocationId>
     */
    public static function create($id)
    {
        if (!self::validate($id)) {
            return Result::error(new InvalidAllocationId);
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
