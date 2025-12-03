<?php
// database/migrations/tenant/2025_11_12_165800_create_stock_movements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer']);
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();

            // user_id → central users → NO FK
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->timestamps();

            $table->index(['item_id', 'created_at']);
            $table->index('warehouse_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};