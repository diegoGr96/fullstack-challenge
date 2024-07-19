<?php

declare(strict_types=1);

namespace Src\Order\Application\Services;

use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\Order;
use Src\Order\Domain\Services\CreateOrder\CreateBuyOrderStrategy;
use Src\Order\Domain\Services\CreateOrder\CreateOrderStrategy;
use Src\Order\Domain\Services\CreateOrder\CreateSellOrderStrategy;
use Src\Order\Domain\ValueObjects\OrderType;
use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Shared\Domain\Errors\DefaultError;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

class CreateOrderService
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
    public function __invoke(array $params)
    {
        $createOrderResult = Order::fromArray($params);
        if ($createOrderResult->isError()) {
            return Result::error(new DefaultError(status: 400));
        }

        $order = $createOrderResult->getData();
        $findPortfolioResult = $this->portfolioRepository->find($order->portfolioId);
        if ($findPortfolioResult->isError()) {
            return $findPortfolioResult;
        }

        $createOrderStrategy = $this->getCreateOrderStrategyByOrderType($order->orderType);
        $createOrderResult = $createOrderStrategy->execute($order);
        if ($createOrderResult->isError()) {
            return $createOrderResult;
        }

        return Result::success(null);
    }

    private function getCreateOrderStrategyByOrderType(OrderType $orderType): CreateOrderStrategy
    {
        return match ($orderType->id) {
            OrderType::ORDER_TYPES[OrderType::BUY_TYPE] => new CreateBuyOrderStrategy($this->orderRepository),
            OrderType::ORDER_TYPES[OrderType::SELL_TYPE] => new CreateSellOrderStrategy($this->orderRepository, $this->portfolioRepository),
        };
    }
}
