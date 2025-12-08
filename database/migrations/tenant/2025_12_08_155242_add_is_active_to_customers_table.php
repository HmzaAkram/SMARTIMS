<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
public function up(): void
{
    Schema::table('customers', function (Blueprint $table) {
        if (!Schema::hasColumn('customers', 'is_active')) {
            // Add without specifying after() or use a different column
            $table->boolean('is_active')->default(true)->after('bank_ifsc_code');
        }
    });
}

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};