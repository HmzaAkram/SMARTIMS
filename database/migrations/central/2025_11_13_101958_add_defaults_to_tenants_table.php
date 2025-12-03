<?php
// database/migrations/central/2025_11_13_XXXXXX_add_defaults_to_tenants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // 1. Domain – default to something sensible
            $table->string('domain')->default('example.smartims.test')->change();

            // 2. Database column – will store the tenant DB name
            $table->string('database')->default('')->change();   // temporary empty
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('domain')->nullable()->change();
            $table->string('database')->nullable()->change();
        });
    }
};