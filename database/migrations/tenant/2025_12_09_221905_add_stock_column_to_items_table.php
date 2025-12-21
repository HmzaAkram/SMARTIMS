<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $hasStock = Schema::hasColumn('items', 'stock');
            $hasQuantity = Schema::hasColumn('items', 'quantity');

            if ($hasStock) {
                if ($hasQuantity) {
                    // Both exist: prefer stock, remove old quantity to clean up
                    $table->dropColumn('quantity');
                }
                // If stock exists and quantity doesn't, we are good.
            } else {
                if ($hasQuantity) {
                    // Stock doesn't exist, but quantity does: rename it
                    $table->renameColumn('quantity', 'stock');
                } else {
                    // Neither exists: create stock
                    $table->integer('stock')->default(0)->after('category_id');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Reverse operation safely
            $hasStock = Schema::hasColumn('items', 'stock');
            $hasQuantity = Schema::hasColumn('items', 'quantity');

            if ($hasStock && !$hasQuantity) {
                $table->renameColumn('stock', 'quantity');
            }
        });
    }
};