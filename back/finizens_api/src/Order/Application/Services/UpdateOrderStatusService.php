<?php

declare(strict_types=1);

namespace Src\Order\Application\Services;

use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\Errors\OrderAlreadyCompleted;
use Src\Order\Domain\Services\UpdateOrderStatus\UpdateBuyOrderStatusStrategy;
use Src\Order\Domain\Services\UpdateOrderStatus\UpdateOrderStatusStrategy;
use Src\Order\Domain\Services\UpdateOrderStatus\UpdateSellOrderStatusStrategy;
use Src\Order\Domain\ValueObjects\OrderId;
use Src\Order\Domain\ValueObjects\OrderStatus;
use Src\Order\Domain\ValueObjects\OrderType;
use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

class UpdateOrderStatusService
{

    public function __construct(
        public readonly OrderRepositoryContract $orderRepository,
        public readonly PortfolioRepositoryContract $portfolioRepository,
    )
    {
    }

    /**
     * @return Result<FinizensError, null>
     */
    public function __invoke($id, array $params)
    {
        //Check if new order status is valid
        $createOrderStatusResult = OrderStatus::create($params['status'] ?? null);
        if ($createOrderStatusResult->isError()) {
            return $createOrderStatusResult;
        }

        $createOrderIdResult = OrderId::create($id);
        if ($createOrderIdResult->isError()) {
            return $createOrderIdResult;
        }


        $findOrderResult = $this->orderRepository->find($createOrderIdResult->getData());
        if ($findOrderResult->isError()) {
            return $findOrderResult;
        }

        $order = $findOrderResult->getData();
        if($order->orderStatus->name === OrderStatus::COMPLETED_STATUS) {
            return Result::error(new OrderAlreadyCompleted);
        }

        $updateOrderStatusStrategy = $this->getUpdateOrderStatusStrategyByOrderType($order->orderType);
        $updateOrderStatusResult = $updateOrderStatusStrategy->execute($order, $createOrderStatusResult->getData());
        if ($updateOrderStatusResult->isError()) {
            return $updateOrderStatusResult;
        }

        return Result::success(null);
    }

    private function getUpdateOrderStatusStrategyByOrderType(OrderType $orderType): UpdateOrderStatusStrategy
    {
        return match ($orderType->id) {
            OrderType::ORDER_TYPES[OrderType::BUY_TYPE] => new UpdateBuyOrderStatusStrategy($this->orderRepository, $this->portfolioRepository),
            OrderType::ORDER_TYPES[OrderType::SELL_TYPE] => new UpdateSellOrderStatusStrategy($this->orderRepository, $this->portfolioRepository),
        };
    }
}
