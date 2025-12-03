<?php
// database/migrations/tenant/2025_11_12_165637_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();

                // tenant_id → central table → NO FK!
                $table->unsignedBigInteger('tenant_id')->index();
                $table->comment('tenant_id refers to central tenants table (no FK)');

                // customer_id & warehouse_id → tenant tables → use index, NOT FK!
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->unsignedBigInteger('warehouse_id')->nullable()->index();

                $table->enum('order_type', ['purchase', 'sales'])->default('sales');
                $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
                $table->date('order_date');
                $table->date('delivery_date')->nullable();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('tax', 12, 2)->default(0);
                $table->decimal('discount', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->decimal('shipping_cost', 12, 2)->default(0);
                $table->text('notes')->nullable();

                $table->timestamps();

                $table->index(['tenant_id', 'order_date']);
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};