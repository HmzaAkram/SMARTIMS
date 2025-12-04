<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\TenantService;
use App\Models\Tenant;

echo "Testing Tenant Creation...\n";

try {
    $tenantService = new TenantService();
    
    echo "Creating test tenant...\n";
    
    $tenant = $tenantService->create(
        'Test Company',
        'test-' . time(),
        'test@example.com',
        '1234567890'
    );
    
    echo "✓ Tenant created successfully!\n";
    echo "Tenant ID: " . $tenant->id . "\n";
    echo "Database: " . $tenant->database . "\n";
    
    // Switch to tenant and check tables
    TenantService::switchToTenant($tenant);
    
    $tables = DB::select('SHOW TABLES');
    echo "\nTables in tenant database:\n";
    foreach ($tables as $table) {
        foreach ($table as $key => $value) {
            echo "- $value\n";
        }
    }
    
    TenantService::switchToCentral();
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}