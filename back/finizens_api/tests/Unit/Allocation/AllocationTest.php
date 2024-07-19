<?php

namespace Tests\Unit\Allocation;

use PHPUnit\Framework\TestCase;
use Src\Portfolio\Domain\Entities\Allocation;

class AllocationTest extends TestCase
{
    public function test_allocation_returns_error_when_params_are_invalid(): void
    {
        $createAllocationResult = Allocation::fromArray([
            'id' => 10,
            'sharesBad' => 10,
        ]);
        $this->assertTrue($createAllocationResult->isError());
        $this->assertEquals("FI-SHARES-400", $createAllocationResult->getError()->code());

        $createAllocationResult = Allocation::fromArray([
            'id' => 10,
            'shares' => "bad",
        ]);
        $this->assertTrue($createAllocationResult->isError());
        $this->assertEquals("FI-SHARES-400", $createAllocationResult->getError()->code());



        $createAllocationResult = Allocation::fromArray([
            'idBad' => 10,
            'shares' => 10,
        ]);
        $this->assertTrue($createAllocationResult->isError());
        $this->assertEquals("FI-ALLOCATION_ID-400", $createAllocationResult->getError()->code());

        $createAllocationResult = Allocation::fromArray([
            'id' => "bad",
            'shares' => 10,
        ]);
        $this->assertTrue($createAllocationResult->isError());
        $this->assertEquals("FI-ALLOCATION_ID-400", $createAllocationResult->getError()->code());
    }

    public function test_allocation_returns_success_when_params_are_ok(): void
    {
        $createAllocationResult = Allocation::fromArray([
            'id' => 10,
            'shares' => 10,
        ]);
        $this->assertTrue($createAllocationResult->isSuccess());
        $this->assertEquals([
            "id" => 10,
            "shares" => 10,
        ], [
            'id' => $createAllocationResult->getData()->allocationId->value,
            'shares' => $createAllocationResult->getData()->shares->value,
        ]);
    }
}
