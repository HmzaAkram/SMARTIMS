<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Only add column + unique index if they don't exist
            if (!Schema::hasColumn('tenants', 'slug')) {
                $table->string('slug')->nullable()->after('domain');
            }

            // Remove old unique index if it exists (prevents duplicate key error)
            if ($this->indexExists('tenants', 'tenants_slug_unique')) {
                $table->dropUnique('tenants_slug_unique');
            }

            // Now safely add the unique index
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    // Helper to check if index exists (Laravel doesn't have built-in for this)
    private function indexExists($table, $index)
    {
        return collect(\DB::select("SHOW INDEXES FROM `$table` WHERE Key_name = ?", [$index]))->isNotEmpty();
    }
};