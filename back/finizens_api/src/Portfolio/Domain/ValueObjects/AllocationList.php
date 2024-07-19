<?php

declare(strict_types=1);

namespace Src\Portfolio\Domain\ValueObjects;

use Src\Portfolio\Domain\Entities\Allocation;

final class AllocationList
{

    /**
     * @var Allocation[]
     */
    private array $items;

    private function __construct()
    {
        $this->items = [];
    }

    public static function create(): AllocationList
    {
        return new self();
    }

    public function add(Allocation $allocation)
    {
        $this->items[] = $allocation;
    }

    /**
     * @return Allocation[]
     */
    public function items(): array
    {
        return $this->items;
    }
}
