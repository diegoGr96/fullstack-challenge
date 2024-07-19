<?php

namespace Src\Portfolio\Infrastructure\Repositories;

use App\Models\Allocation\Allocation as EloquentAllocation;
use App\Models\Portfolio\Portfolio as EloquentPortfolio;
use Src\Order\Domain\Order;
use Src\Order\Domain\ValueObjects\OrderId;
use Src\Order\Domain\ValueObjects\OrderList;
use Src\Order\Domain\ValueObjects\OrderStatus;
use Src\Order\Domain\ValueObjects\OrderType;
use Src\Portfolio\Domain\Contracts\PortfolioRepositoryContract;
use Src\Portfolio\Domain\Entities\Allocation;
use Src\Portfolio\Domain\Errors\AllocationNotFound;
use Src\Portfolio\Domain\Errors\PortfolioNotFound;
use Src\Portfolio\Domain\Portfolio;
use Src\Portfolio\Domain\ValueObjects\AllocationId;
use Src\Portfolio\Domain\ValueObjects\AllocationList;
use Src\Portfolio\Domain\ValueObjects\PortfolioId;
use Src\Portfolio\Domain\ValueObjects\Shares;
use Src\Shared\Domain\Errors\FinizensError;
use Src\Shared\Domain\Result\Result;

final class EloquentPortfolioRepository implements PortfolioRepositoryContract
{
    public function getAll(): array
    {
        $eloquentPortfolios = EloquentPortfolio::query()
            ->with('allocations', 'orders')
            ->get();

        $portfolios = [];
        foreach ($eloquentPortfolios as $eloquentPortfolio) {
            $portfolio = $this->mapEloquentProtfolioToDomainPortfolio($eloquentPortfolio);
            $portfolios[] = $portfolio;
        }

        return $portfolios;
    }

    /**
     * @return Result<FinizensError, Portfolio>
     */
    public function find(PortfolioId $portfolioId): Result
    {
        $eloquentPortfolio = EloquentPortfolio::query()
            ->with('allocations', 'orders')
            ->find($portfolioId->value);

        if (!$eloquentPortfolio) {
            return Result::error(new PortfolioNotFound);
        }

        $portfolio = $this->mapEloquentProtfolioToDomainPortfolio($eloquentPortfolio);
        return Result::success($portfolio);
    }

    private function mapEloquentProtfolioToDomainPortfolio(EloquentPortfolio $eloquentPortfolio): Portfolio
    {
        $portfolioIdResult = PortfolioId::create($eloquentPortfolio->id);

        $allocationList = AllocationList::create();
        foreach ($eloquentPortfolio->allocations as $eloquentAllocation) {
            $allocationIdResult = AllocationId::create($eloquentAllocation->id);
            $sharesResult = Shares::create($eloquentAllocation->shares);
            $allocationList->add(Allocation::create(
                $allocationIdResult->getData(),
                $sharesResult->getData(),
            ));
        }

        $orderList = OrderList::create();
        foreach ($eloquentPortfolio->orders as $order) {
            $orderIdResult = OrderId::create($order->id);
            $orderAllocationIdResult = AllocationId::create($order->allocation_id);
            $orderSharesResult = Shares::create($order->shares);
            $orderTypeResult = OrderType::create($order->type_name);
            $orderStatusResult = OrderStatus::create($order->status_name);

            $orderList->add(Order::create(
                $orderIdResult->getData(),
                $portfolioIdResult->getData(),
                $orderAllocationIdResult->getData(),
                $orderSharesResult->getData(),
                $orderTypeResult->getData(),
                $orderStatusResult->getData(),
            ));
        }

        return Portfolio::create($portfolioIdResult->getData(), $allocationList, $orderList);
    }

    public function setPortfolio(int $id, AllocationList $allocationList): void
    {
        $eloquentPortfolio = EloquentPortfolio::query()->firstOrCreate(['id' => $id]);
        $this->removeAllEloquentPorfolioRelatedData($eloquentPortfolio);

        foreach ($allocationList->items() as $allocation) {
            $this->updateOrCreateAllocation($id, $allocation);
        }
    }

    private function removeAllEloquentPorfolioRelatedData(EloquentPortfolio $eloquentPortfolio): void
    {
        $eloquentPortfolio->orders()->delete();
        $eloquentPortfolio->allocations()->delete();
    }

    public function updateOrCreateAllocation(int $id, Allocation $allocation): void
    {
        $eloquentAllocation = EloquentAllocation::query()
            ->where('id', $allocation->allocationId->value)
            ->where('portfolio_id', $id)
            ->first();

        if ($eloquentAllocation) {
            EloquentAllocation::query()
                ->where('id', $allocation->allocationId->value)
                ->where('portfolio_id', $id)
                ->update(['shares' => $allocation->shares->value]);
            return;
        }

        EloquentAllocation::query()->create([
            'id' => $allocation->allocationId->value,
            'portfolio_id' => $id,
            'shares' => $allocation->shares->value,
        ]);
    }

    public function deleteAllocation(int $id, Allocation $allocation): void
    {
        EloquentAllocation::query()
            ->where('id', $allocation->allocationId->value)
            ->where('portfolio_id', $id)
            ->delete();
    }

    /**
     * @return Result<FinizensError, Allocation>
     */
    public function findAllocation(AllocationId $allocationId, PortfolioId $portfolioId): Result
    {
        $eloquentAllocation = EloquentAllocation::query()
            ->where('id', $allocationId->value)
            ->where('portfolio_id', $portfolioId->value)
            ->first();

        if (!$eloquentAllocation) {
            return Result::error(new AllocationNotFound);
        }

        return Allocation::fromArray([
            'id' => $eloquentAllocation->id,
            'shares' => $eloquentAllocation->shares,
        ]);
    }

    public function getNextAllocationId(): AllocationId
    {
        $highestAllocation = EloquentAllocation::query()
            ->orderBy('id', 'desc')
            ->first();

        $allocationId = AllocationId::create(($highestAllocation->id ?? 0) + 1);
        return $allocationId->getData();
    }
}
