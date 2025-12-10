<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
        
        // Insert some default settings
        $this->insertDefaultSettings();
    }
    
    private function insertDefaultSettings(): void
    {
        $defaultSettings = [
            // General Settings
            ['key' => 'app_name', 'value' => 'SmartIMS', 'group' => 'general'],
            ['key' => 'app_url', 'value' => 'http://smartims.test', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'UTC', 'group' => 'general'],
            ['key' => 'currency', 'value' => 'USD', 'group' => 'general'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'group' => 'general'],
            ['key' => 'time_format', 'value' => 'H:i:s', 'group' => 'general'],
            
            // Mail Settings
            ['key' => 'mail_host', 'value' => 'smtp.mailgun.org', 'group' => 'mail'],
            ['key' => 'mail_port', 'value' => '587', 'group' => 'mail'],
            ['key' => 'mail_username', 'value' => '', 'group' => 'mail'],
            ['key' => 'mail_password', 'value' => '', 'group' => 'mail'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'group' => 'mail'],
            ['key' => 'mail_from_address', 'value' => 'hello@example.com', 'group' => 'mail'],
            ['key' => 'mail_from_name', 'value' => 'SmartIMS', 'group' => 'mail'],
            
            // Billing Settings
            ['key' => 'currency', 'value' => 'USD', 'group' => 'billing'],
            ['key' => 'tax_rate', 'value' => '0', 'group' => 'billing'],
            ['key' => 'invoice_prefix', 'value' => 'INV', 'group' => 'billing'],
            ['key' => 'due_days', 'value' => '30', 'group' => 'billing'],
            ['key' => 'late_fee', 'value' => '0', 'group' => 'billing'],
            
            // Security Settings
            ['key' => 'password_min_length', 'value' => '8', 'group' => 'security'],
            ['key' => 'password_require_numbers', 'value' => '1', 'group' => 'security'],
            ['key' => 'password_require_symbols', 'value' => '1', 'group' => 'security'],
            ['key' => 'password_require_mixed_case', 'value' => '1', 'group' => 'security'],
            ['key' => 'login_attempts', 'value' => '5', 'group' => 'security'],
            ['key' => 'lockout_time', 'value' => '15', 'group' => 'security'],
            ['key' => 'session_timeout', 'value' => '60', 'group' => 'security'],
        ];
        
        foreach ($defaultSettings as $setting) {
            DB::table('settings')->insert($setting);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};