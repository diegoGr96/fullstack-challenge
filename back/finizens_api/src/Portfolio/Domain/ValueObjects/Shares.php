<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\ValueObjects;

use Src\Portfolio\Domain\Errors\InvalidAllocationId;
use Src\Portfolio\Domain\Errors\InvalidShares;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class Shares
{
    private function __construct(
        public readonly int $value
    ) {
    }

    /**
     * @return Result<FinizensError, Shares>
     */
    public static function create($shares)
    {
        if (!self::validate($shares)) {
            return Result::error(new InvalidShares);
        }

        return Result::success(new self($shares));
    }

    public static function validate($shares): int | false
    {
        $options = array(
            'options' => array(
                'min_range' => 1,
            )
        );

        return filter_var($shares, FILTER_VALIDATE_INT, $options);
    }
}
