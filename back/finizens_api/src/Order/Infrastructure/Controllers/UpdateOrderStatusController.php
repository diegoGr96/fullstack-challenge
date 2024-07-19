<?php

declare(strict_types=1);

namespace Src\Order\Infrastructure\Controllers;

use Src\Order\Application\Services\UpdateOrderStatusService;
use Src\Order\Infrastructure\Repositories\EloquentOrderRepository;
use Src\Portfolio\Infrastructure\Repositories\EloquentPortfolioRepository;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class UpdateOrderStatusController
{
    public function __construct(
        public readonly EloquentOrderRepository $eloquentOrderRepository,
        public readonly EloquentPortfolioRepository $eloquentPortfolioRepository,
    )
    {
    }

    /**
     * @return Result<FinizensError, null>
     */
    public function __invoke($id, array $params)
    {
        $updateOrderStatusService = new UpdateOrderStatusService(
            $this->eloquentOrderRepository,
            $this->eloquentPortfolioRepository,
        );
        $updateOrderStatusResult = $updateOrderStatusService->__invoke($id, $params);
        return $updateOrderStatusResult;
    }
}
