<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Order\Domain\ValueObjects\OrderStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned();
            $table->primary(['id']);
            $table->foreignId('portfolio_id')->constrained();
            // $table->primary(['id', 'portfolio_id']);

            $table->bigInteger('allocation_id')->unsigned();
            // $table->foreignId('allocation_id')->constrained();

            $table->unsignedInteger('shares');

            $table->unsignedTinyInteger('type');

            $table->unsignedTinyInteger('status')->default(1);
            // $table->string('status')->default(OrderStatus::PENDING_STATUS);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
