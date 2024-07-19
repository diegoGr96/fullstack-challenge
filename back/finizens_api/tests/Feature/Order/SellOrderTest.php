<?php

namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_created_sell_order_should_exists_in_database(): void
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
            'type' => 'sell',
        ];

        $response = $this->json('POST', '/api/orders', $requestParams);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'portfolio_id' => 1,
            'allocation_id' => 1,
            'shares' => 1,
            'type' => 2,
            'status' => 1,
        ]);

        $this->assertDatabaseCount('orders', 1);
    }

    public function test_confirm_sell_order_should_substract_shares_in_database(): void
    {
        $createPortfolioParams = [
            'allocations' => [
                [
                    'id' => 1,
                    'shares' => 2,
                ],
            ],
        ];

        $createPortfolioResponse = $this->json('PUT', '/api/portfolios/1', $createPortfolioParams);
        $createPortfolioResponse->assertStatus(200);
        $this->assertDatabaseHas('allocations', [
            'id' => 1,
            'portfolio_id' => 1,
            'shares' => 2,
        ]);


        $requestParams = [
            'id' => 1,
            'portfolio' => 1,
            'allocation' => 1,
            'shares' => 1,
            'type' => 'sell',
        ];

        $response = $this->json('POST', '/api/orders', $requestParams);
        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'portfolio_id' => 1,
            'allocation_id' => 1,
            'shares' => 1,
            'type' => 2,
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
            'type' => 2,
            'status' => 2,
        ]);
        $this->assertDatabaseCount('orders', 1);


        $this->assertDatabaseHas('allocations', [
            'id' => 1,
            'portfolio_id' => 1,
            'shares' => 1,
        ]);
        $this->assertDatabaseCount('allocations', 1);
    }

    public function test_confirm_sell_order_should_delete_allocation_in_database(): void
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
            'type' => 'sell',
        ];

        $response = $this->json('POST', '/api/orders', $requestParams);
        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'portfolio_id' => 1,
            'allocation_id' => 1,
            'shares' => 1,
            'type' => 2,
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
            'type' => 2,
            'status' => 2,
        ]);
        $this->assertDatabaseCount('orders', 1);


        $this->assertDatabaseCount('allocations', 0);
    }
}
