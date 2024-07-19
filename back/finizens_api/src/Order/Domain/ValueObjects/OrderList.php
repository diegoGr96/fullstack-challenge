<?php

declare(strict_types=1);

namespace Src\Order\Domain\ValueObjects;

use Src\Order\Domain\Order;

final class OrderList
{

    /**
     * @var Order[]
     */
    private array $items;

    private function __construct()
    {
        $this->items = [];
    }

    public static function create(): OrderList
    {
        return new self();
    }

    public function add(Order $order)
    {
        $this->items[] = $order;
    }

    /**
     * @return Order[]
     */
    public function items(): array
    {
        return $this->items;
    }
}
