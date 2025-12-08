<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index($tenant)
    {
        // Get the tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        // Get tenant settings from the central database
        $settings = $tenantModel;
        
        // Get warehouse options for default warehouse setting
        $warehouses = DB::connection('tenant')->table('warehouses')
            ->where('tenant_id', $tenantModel->id)
            ->where('is_active', true)
            ->get();
        
        return view('settings.index', compact('tenant', 'settings', 'warehouses'));
    }

    /**
     * Update settings
     */
    public function update(Request $request, $tenant)
    {
        // Get the tenant
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            
            // Business settings
            'currency' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|max:20',
            
            // Inventory settings
            'low_stock_threshold' => 'nullable|integer|min:1',
            'enable_barcode' => 'boolean',
            'enable_sku' => 'boolean',
            'default_warehouse_id' => 'nullable|exists:warehouses,id',
            
            // Sales settings
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'invoice_prefix' => 'nullable|string|max:10',
            'invoice_start_number' => 'nullable|integer|min:1',
            
            // Notification settings
            'notify_low_stock' => 'boolean',
            'notify_expiry' => 'boolean',
            'expiry_alert_days' => 'nullable|integer|min:1',
        ]);
        
        // Prepare settings data
        $settingsData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            
            // Store other settings in JSON data field
            'data' => json_encode([
                'business' => [
                    'currency' => $validated['currency'],
                    'timezone' => $validated['timezone'],
                    'date_format' => $validated['date_format'],
                    'time_format' => $validated['time_format'],
                ],
                'inventory' => [
                    'low_stock_threshold' => $validated['low_stock_threshold'] ?? 10,
                    'enable_barcode' => $request->has('enable_barcode'),
                    'enable_sku' => $request->has('enable_sku'),
                    'default_warehouse_id' => $validated['default_warehouse_id'] ?? null,
                ],
                'sales' => [
                    'tax_rate' => $validated['tax_rate'] ?? 0,
                    'invoice_prefix' => $validated['invoice_prefix'] ?? 'INV',
                    'invoice_start_number' => $validated['invoice_start_number'] ?? 1001,
                ],
                'notifications' => [
                    'notify_low_stock' => $request->has('notify_low_stock'),
                    'notify_expiry' => $request->has('notify_expiry'),
                    'expiry_alert_days' => $validated['expiry_alert_days'] ?? 30,
                ],
            ]),
        ];
        
        // Update tenant settings in central database
        $tenantModel->update($settingsData);
        
        // Also update tenant-specific settings in tenant database
        $this->updateTenantSettings($tenantModel, $validated);
        
        return redirect()
            ->route('company.settings', $tenant)
            ->with('success', 'Settings updated successfully!');
    }
    
    /**
     * Update settings in tenant database
     */
    private function updateTenantSettings($tenant, $settings)
    {
        // Store tenant settings in tenant database
        $settingsTable = 'settings';
        
        // Check if settings table exists in tenant database
        $exists = DB::connection('tenant')->select("SHOW TABLES LIKE '{$settingsTable}'");
        
        if (empty($exists)) {
            // Create settings table if it doesn't exist
            DB::connection('tenant')->statement("
                CREATE TABLE IF NOT EXISTS `settings` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `tenant_id` bigint unsigned NOT NULL,
                    `key` varchar(255) NOT NULL,
                    `value` text,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `settings_tenant_id_key_unique` (`tenant_id`,`key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }
        
        // Update or insert settings
        $tenantSettings = [
            'currency' => $settings['currency'],
            'timezone' => $settings['timezone'],
            'date_format' => $settings['date_format'],
            'time_format' => $settings['time_format'],
            'low_stock_threshold' => $settings['low_stock_threshold'] ?? 10,
            'tax_rate' => $settings['tax_rate'] ?? 0,
            'invoice_prefix' => $settings['invoice_prefix'] ?? 'INV',
            'invoice_start_number' => $settings['invoice_start_number'] ?? 1001,
            'enable_barcode' => $settings['enable_barcode'] ?? true,
            'enable_sku' => $settings['enable_sku'] ?? true,
            'default_warehouse_id' => $settings['default_warehouse_id'] ?? null,
            'notify_low_stock' => $settings['notify_low_stock'] ?? true,
            'notify_expiry' => $settings['notify_expiry'] ?? true,
            'expiry_alert_days' => $settings['expiry_alert_days'] ?? 30,
        ];
        
        foreach ($tenantSettings as $key => $value) {
            DB::connection('tenant')->table('settings')->updateOrInsert(
                ['tenant_id' => $tenant->id, 'key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : $value, 'updated_at' => now()]
            );
        }
    }

    /**
     * Get application settings
     */
    public function getSettings($tenant)
    {
        $tenantModel = Tenant::where('domain', $tenant)
            ->orWhere('slug', $tenant)
            ->firstOrFail();
        
        $settings = [];
        
        // Try to get from tenant settings table first
        try {
            $dbSettings = DB::connection('tenant')->table('settings')
                ->where('tenant_id', $tenantModel->id)
                ->pluck('value', 'key')
                ->toArray();
            
            $settings = $dbSettings;
        } catch (\Exception $e) {
            // If settings table doesn't exist, use defaults
            $settings = [
                'currency' => 'INR',
                'timezone' => 'Asia/Kolkata',
                'date_format' => 'd/m/Y',
                'time_format' => 'h:i A',
                'tax_rate' => 18,
                'invoice_prefix' => 'INV',
                'invoice_start_number' => 1001,
                'low_stock_threshold' => 10,
                'expiry_alert_days' => 30,
            ];
        }
        
        return response()->json([
            'success' => true,
            'settings' => $settings,
            'tenant' => $tenantModel->only(['name', 'email', 'phone', 'address'])
        ]);
    }
}