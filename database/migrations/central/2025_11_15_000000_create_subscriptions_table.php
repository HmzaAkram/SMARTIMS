<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This migration runs in CENTRAL database
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('plan_name')->default('starter');
            $table->decimal('price', 10, 2)->default(230.00);
            $table->string('status')->default('active'); // active, cancelled, expired
            $table->string('billing_cycle')->default('monthly'); // monthly, quarterly, yearly
            $table->date('trial_ends_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'status']);
            $table->index('plan_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
}