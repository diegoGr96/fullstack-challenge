<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\ValueObjects;

use Src\Portfolio\Domain\Errors\InvalidPortfolioId;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class PortfolioId
{
    private function __construct(
        public readonly int $value
    ) {
    }

    /**
     * @return Result<FinizensError, PortfolioId>
     */
    public static function create($id)
    {
        if (!self::validate($id)) {
            return Result::error(new InvalidPortfolioId);
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
