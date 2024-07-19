<?php

declare(strict_types=1);

namespace Src\Order\Infrastructure\Controllers;

use Src\Order\Application\Services\CreateOrderService;
use Src\Order\Infrastructure\Repositories\EloquentOrderRepository;
use Src\Portfolio\Infrastructure\Repositories\EloquentPortfolioRepository;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class CreateOrderController
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
    public function __invoke(array $params)
    {
        $createOrderService = new CreateOrderService(
            $this->eloquentOrderRepository,
            $this->eloquentPortfolioRepository,
        );
        $createOrderResult = $createOrderService->__invoke($params);
        return $createOrderResult;
    }
}
