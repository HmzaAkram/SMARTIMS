<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Only add columns if they don't exist
            if (!Schema::hasColumn('tenants', 'email')) {
                $table->string('email')->nullable()->after('domain');
            }
            
            if (!Schema::hasColumn('tenants', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('tenants', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['email', 'phone', 'address']);
        });
    }
};