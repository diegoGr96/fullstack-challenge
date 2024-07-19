<?php

namespace Tests\Unit\Order;

use Mockery;
use PHPUnit\Framework\TestCase;
use Src\Order\Application\Services\CreateOrderService;
use Src\Order\Domain\Contracts\OrderRepositoryContract;
use Src\Order\Domain\ValueObjects\OrderList;
use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Portfolio\Domain\Entities\Allocation;
use Src\Portfolio\Domain\Portfolio;
use Src\Portfolio\Domain\ValueObjects\AllocationId;
use Src\Portfolio\Domain\ValueObjects\AllocationList;
use Src\Portfolio\Domain\ValueObjects\PortfolioId;
use Src\Portfolio\Domain\ValueObjects\Shares;
use Src\Shared\Domain\Result\Result;

class OrderTest extends TestCase
{
    private $orderRepositoryMock;
    private $portfolioRepositoryMock;


    protected function setUp(): void
    {
        $this->orderRepositoryMock = Mockery::mock(OrderRepositoryContract::class);
        $this->portfolioRepositoryMock = Mockery::mock(PortfolioRepositoryContract::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_CreateOrderService_with_invalid_params_returns_error()
    {
        $params = [
            'idBad' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buy',
        ];

        $searchUseCase = new CreateOrderService($this->orderRepositoryMock, $this->portfolioRepositoryMock);

        $result = $searchUseCase->__invoke($params);

        $this->assertTrue($result->isError());
        $this->assertEquals(400, $result->getError()->status());

        $params = [
            'idBad' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => -1,
            'type' => 'buy',
        ];

        $searchUseCase = new CreateOrderService($this->orderRepositoryMock, $this->portfolioRepositoryMock);

        $result = $searchUseCase->__invoke($params);

        $this->assertTrue($result->isError());
        $this->assertEquals(400, $result->getError()->status());
    }


    public function test_CreateOrderService_buy_order_returns_success()
    {
        $this->portfolioRepositoryMock->shouldReceive('find')
            ->andReturn(Result::success(Portfolio::create(
                PortfolioId::create(1)->getData(),
                AllocationList::create(),
                OrderList::create(),
            )));

        $this->orderRepositoryMock->shouldReceive('createOrder');


        $params = [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buy',
        ];

        $searchUseCase = new CreateOrderService($this->orderRepositoryMock, $this->portfolioRepositoryMock);

        $result = $searchUseCase->__invoke($params);

        $this->assertFalse($result->isError());
    }

    public function test_CreateOrderService_sell_order_returns_success()
    {
        $this->portfolioRepositoryMock->shouldReceive('find')
            ->andReturn(Result::success(Portfolio::create(
                PortfolioId::create(1)->getData(),
                AllocationList::create(),
                OrderList::create(),
            )));

        $this->portfolioRepositoryMock->shouldReceive('findAllocation')
            ->andReturn(Result::success(Allocation::create(
                AllocationId::create(1)->getData(),
                Shares::create(1)->getData(),
            )));

        $this->orderRepositoryMock->shouldReceive('createOrder');


        $params = [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'sell',
        ];

        $searchUseCase = new CreateOrderService($this->orderRepositoryMock, $this->portfolioRepositoryMock);

        $result = $searchUseCase->__invoke($params);

        $this->assertFalse($result->isError());
    }
}
