<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuyOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_created_buy_order_should_exists_in_database(): void
    {
        $createPortfolioParams = [
            'allocations' => [
                [
                    'id' => 1,
                    'shares' => 1,
                ],
            ],
        ];

        $createPortfolioResponse = $this->json('PUT', '/api/portfolios/1', $createPortfolioParams);
        $createPortfolioResponse->assertStatus(200);


        $requestParams = [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buy',
        ];

        $response = $this->json('POST', '/api/orders', $requestParams);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'portfolio_id' => 1,
            'allocation_id' => 1,
            'shares' => 1,
            'type' => 1,
            'status' => 1,
        ]);

        $this->assertDatabaseCount('orders', 1);
    }

    public function test_confirm_buy_order_should_add_shares_in_database(): void
    {
        $createPortfolioParams = [
            'allocations' => [
                [
                    'id' => 1,
                    'shares' => 1,
                ],
            ],
        ];

        $createPortfolioResponse = $this->json('PUT', '/api/portfolios/1', $createPortfolioParams);
        $createPortfolioResponse->assertStatus(200);
        $this->assertDatabaseHas('allocations', [
            'id' => 1,
            'portfolio_id' => 1,
            'shares' => 1,
        ]);


        $requestParams = [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buy',
        ];

        $response = $this->json('POST', '/api/orders', $requestParams);
        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'portfolio_id' => 1,
            'allocation_id' => 1,
            'shares' => 1,
            'type' => 1,
            'status' => 1,
        ]);
        $this->assertDatabaseCount('orders', 1);


        $response = $this->json('PATCH', '/api/orders/1', [
            'status' => 'completed'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'portfolio_id' => 1,
            'allocation_id' => 1,
            'shares' => 1,
            'type' => 1,
            'status' => 2,
        ]);
        $this->assertDatabaseCount('orders', 1);


        $this->assertDatabaseHas('allocations', [
            'id' => 1,
            'portfolio_id' => 1,
            'shares' => 2,
        ]);
    }

    public function test_confirm_buy_order_should_create_allocation_in_database(): void
    {
        $createPortfolioResponse = $this->json('PUT', '/api/portfolios/1', []);
        $createPortfolioResponse->assertStatus(200);
        $this->assertDatabaseCount('allocations', 0);


        $requestParams = [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'buy',
        ];

        $response = $this->json('POST', '/api/orders', $requestParams);
        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'portfolio_id' => 1,
            'allocation_id' => 1,
            'shares' => 1,
            'type' => 1,
            'status' => 1,
        ]);
        $this->assertDatabaseCount('orders', 1);


        $response = $this->json('PATCH', '/api/orders/1', [
            'status' => 'completed'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'portfolio_id' => 1,
            'allocation_id' => 1,
            'shares' => 1,
            'type' => 1,
            'status' => 2,
        ]);
        $this->assertDatabaseCount('orders', 1);


        $this->assertDatabaseHas('allocations', [
            'id' => 1,
            'portfolio_id' => 1,
            'shares' => 1,
        ]);
    }
}
