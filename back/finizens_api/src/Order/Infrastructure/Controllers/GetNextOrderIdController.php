<?php

declare(strict_types=1);

namespace Src\Order\Infrastructure\Controllers;

use Src\Order\Application\Services\GetNextOrderIdService;
use Src\Order\Infrastructure\Repositories\EloquentOrderRepository;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class GetNextOrderIdController
{
    public function __construct(
        public readonly EloquentOrderRepository $eloquentOrderRepository,
    )
    {
    }

    /**
     * @return Result<FinizensError, null>
     */
    public function __invoke()
    {
        $nextOrderIdService = new GetNextOrderIdService(
            $this->eloquentOrderRepository,
        );
        $nextOrderIdResult = $nextOrderIdService->__invoke();
        return $nextOrderIdResult;
    }
}
