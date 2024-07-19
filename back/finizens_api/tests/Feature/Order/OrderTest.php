<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_create_order_with_invalid_params_should_returns_400_status(): void
    {
        $response = $this->json('POST', '/api/orders', [
            'idBad' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buy',
        ]);
        $response->assertStatus(400);

        $response = $this->json('POST', '/api/orders', [
            'id' => "bad",
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buy',
        ]);
        $response->assertStatus(400);

        $response = $this->json('POST', '/api/orders', [
            'id' => 1,
            'portfolio' => 'BAD',
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buyBad',
        ]);
        $response->assertStatus(400);

        $response = $this->json('POST', '/api/orders', [
            'id' => 1,
            'portfolio' => 1,
            'allocationBad' => 1,
            'shares' => 1,
            'type' => 'buyBad',
        ]);
        $response->assertStatus(400);

        $response = $this->json('POST', '/api/orders', [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 'BAD',
            'shares' => 1,
            'type' => 'buyBad',
        ]);
        $response->assertStatus(400);

        $response = $this->json('POST', '/api/orders', [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buyBad',
        ]);
        $response->assertStatus(400);
    }

    public function test_request_create_order_with_not_found_portfolio_should_returns_404_status(): void
    {
        $response = $this->json('POST', '/api/orders', [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buy',
        ]);
        $response->assertStatus(404);

        $response = $this->json('POST', '/api/orders', [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'sell',
        ]);
        $response->assertStatus(404);
    }
}
