<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Tenant;
use App\Services\TenantService;

echo "Testing User Roles...\n";

// Test 1: Check super admin
$superAdmin = User::where('email', 'ha8028377@gmail.com')->first();
if ($superAdmin) {
    echo "Super Admin:\n";
    echo "- Email: " . $superAdmin->email . "\n";
    echo "- Tenant ID: " . $superAdmin->tenant_id . "\n";
    echo "- Roles: " . implode(', ', $superAdmin->getRoleNames()->toArray()) . "\n\n";
}

// Test 2: Check company admin (use your test company)
$companyAdmin = User::where('email', 'admin@testcompany.com')->first();
if ($companyAdmin) {
    echo "Company Admin (Central):\n";
    echo "- Email: " . $companyAdmin->email . "\n";
    echo "- Tenant ID: " . $companyAdmin->tenant_id . "\n";
    echo "- Roles: " . implode(', ', $companyAdmin->getRoleNames()->toArray()) . "\n\n";
    
    // Check in tenant database
    $tenant = Tenant::find($companyAdmin->tenant_id);
    if ($tenant) {
        echo "Switching to tenant database: " . $tenant->database . "\n";
        TenantService::switchToTenant($tenant);
        
        $tenantUser = User::where('email', $companyAdmin->email)->first();
        if ($tenantUser) {
            echo "Company Admin (Tenant):\n";
            echo "- Email: " . $tenantUser->email . "\n";
            echo "- Tenant ID: " . $tenantUser->tenant_id . "\n";
            echo "- Roles: " . implode(', ', $tenantUser->getRoleNames()->toArray()) . "\n";
        }
        
        TenantService::switchToCentral();
    }
}