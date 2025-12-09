<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Check if quantity column exists
            if (Schema::hasColumn('items', 'quantity')) {
                // If quantity exists, rename it to stock
                $table->renameColumn('quantity', 'stock');
            } else {
                // If quantity doesn't exist, add stock column
                $table->integer('stock')->default(0)->after('category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'stock')) {
                // Rename back to quantity
                $table->renameColumn('stock', 'quantity');
            }
        });
    }
};