<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('sku')->unique();
            $table->string('barcode')->unique()->nullable();

            $table->text('description')->nullable();

            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            $table->foreignId('warehouse_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('unit');                     // pcs, kg, etc.
            $table->decimal('unit_price', 12, 2)->default(0.00);

            $table->integer('quantity')->default(0);
            $table->integer('reorder_level')->default(0);

            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable();
            $table->boolean('is_active')->default(true);

            $table->string('image')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};