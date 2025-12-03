<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'barcode')) {
                $table->string('barcode')->unique()->nullable()->after('sku');
            }
            if (!Schema::hasColumn('items', 'unit')) {
                $table->string('unit')->after('description');
            }
            if (!Schema::hasColumn('items', 'unit_price')) {
                $table->decimal('unit_price', 12, 2)->default(0)->after('unit');
            }
            if (!Schema::hasColumn('items', 'quantity')) {
                $table->integer('quantity')->default(0)->after('unit_price');
            }
            if (!Schema::hasColumn('items', 'reorder_level')) {
                $table->integer('reorder_level')->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('items', 'warehouse_id')) {
                $table->foreignId('warehouse_id')->after('category_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('items', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('warehouse_id');
            }
            if (!Schema::hasColumn('items', 'batch_number')) {
                $table->string('batch_number')->nullable()->after('expiry_date');
            }
            if (!Schema::hasColumn('items', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('batch_number');
            }
        });
    }

    public function down()
    {
        // optional rollback
    }
};