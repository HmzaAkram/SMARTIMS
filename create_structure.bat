@echo off
mkdir app\Http\Controllers\Auth
mkdir app\Http\Controllers\SuperAdmin
mkdir app\Http\Controllers\Company
mkdir app\Http\Controllers\Inventory
mkdir app\Http\Controllers\Warehouse
mkdir app\Http\Controllers\Printer
mkdir app\Http\Controllers\Report
mkdir app\Http\Controllers\Subscription
mkdir app\Http\Middleware
type nul > app\Http\Middleware\EnsureTenantIsSet.php
type nul > app\Http\Middleware\CheckRole.php
type nul > app\Http\Middleware\RedirectIfNotSuperAdmin.php
mkdir app\Http\Requests
mkdir app\Models
type nul > app\Models\User.php
type nul > app\Models\Tenant.php
type nul > app\Models\Company.php
type nul > app\Models\Item.php
type nul > app\Models\Category.php
type nul > app\Models\Warehouse.php
type nul > app\Models\Printer.php
type nul > app\Models\StockMovement.php
type nul > app\Models\Subscription.php
mkdir app\Services
type nul > app\Services\TenantService.php
type nul > app\Services\BillingService.php
type nul > app\Services\ReportService.php
mkdir app\Providers
type nul > app\Providers\TenancyServiceProvider.php
mkdir database\migrations\central
mkdir database\migrations\tenant
mkdir database\seeders
type nul > database\seeders\RolesAndPermissionsSeeder.php
type nul > database\seeders\SuperAdminSeeder.php
mkdir resources\js\Pages\SuperAdmin
mkdir resources\js\Pages\Company
mkdir resources\js\Pages\Shared
mkdir resources\js\components
mkdir resources\views\layouts
mkdir resources\views\super-admin
mkdir resources\views\company
mkdir resources\views\components
mkdir resources\css
type nul > routes\superadmin.php
type nul > routes\company.php
type nul > routes\api.php
type nul > config\tenancy.php
mkdir public\tenants
mkdir storage\backups
echo Structure created!