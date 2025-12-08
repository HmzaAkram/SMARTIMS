<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                
                // tenant_id for multi-tenancy
                $table->unsignedBigInteger('tenant_id')->index();
                $table->comment('tenant_id refers to central tenants table (no FK)');
                
                // Customer information
                $table->string('customer_code')->unique();
                $table->string('name');
                $table->string('company_name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('mobile')->nullable();
                $table->string('gst_number')->nullable();
                $table->string('pan_number')->nullable();
                $table->string('website')->nullable();
                
                // Address fields
                $table->text('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zip_code')->nullable();
                $table->string('country')->nullable();
                
                // Contact person
                $table->string('contact_person')->nullable();
                $table->string('contact_person_phone')->nullable();
                $table->string('contact_person_email')->nullable();
                
                // Customer details
                $table->enum('customer_type', ['retail', 'wholesale', 'corporate', 'government', 'walkin'])->default('retail');
                $table->decimal('credit_limit', 15, 2)->nullable();
                $table->decimal('opening_balance', 15, 2)->default(0);
                $table->date('opening_balance_date')->nullable();
                $table->decimal('current_balance', 15, 2)->default(0);
                $table->string('payment_terms')->nullable();
                
                // Bank details (for refunds/advances)
                $table->string('bank_name')->nullable();
                $table->string('bank_account_number')->nullable();
                $table->string('bank_ifsc_code')->nullable();
                
                // Status
                $table->boolean('is_active')->default(true);
                
                // Notes
                $table->text('notes')->nullable();
                
                // Timestamps & soft deletes
                $table->softDeletes();
                $table->timestamps();
                
                // Indexes
                $table->index(['tenant_id', 'is_active']);
                $table->index('name');
                $table->index('email');
                $table->index('customer_code');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};