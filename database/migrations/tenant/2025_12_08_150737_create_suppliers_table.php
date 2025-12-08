<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                
                // tenant_id for multi-tenancy
                $table->unsignedBigInteger('tenant_id')->index();
                $table->comment('tenant_id refers to central tenants table (no FK)');
                
                // Supplier information
                $table->string('name');
                $table->string('company_name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('mobile')->nullable();
                $table->string('tax_number')->nullable(); // VAT/Tax ID
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
                
                // Payment information
                $table->string('payment_terms')->nullable(); // e.g., Net 30
                $table->string('bank_name')->nullable();
                $table->string('bank_account_number')->nullable();
                $table->string('bank_swift_code')->nullable();
                
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
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};