<?php

namespace App\Models\Order;

use App\Models\Allocation\Allocation;
use App\Models\Portfolio\Portfolio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Src\Order\Domain\ValueObjects\OrderStatus;
use Src\Order\Domain\ValueObjects\OrderType;

class Order extends Model
{
    use HasFactory;

    const ORDER_TYPES = [
        1 => OrderType::BUY_TYPE,
        2 => OrderType::SELL_TYPE,
    ];

    const STATUSES = [
        1 => OrderStatus::PENDING_STATUS,
        2 => OrderStatus::COMPLETED_STATUS,
    ];

    protected $fillable = [
        'id', 
        'portfolio_id',
        'allocation_id',
        'shares',
        'type',
        'status',
    ];

    protected function getTypeNameAttribute()
    {
        return self::ORDER_TYPES[$this->type];
    }

    protected function getStatusNameAttribute()
    {
        return self::STATUSES[$this->status];
    }

    /**
     * Get the portfolio that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    /**
     * Get the allocation that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class);
    }
}
