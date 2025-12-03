<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FixTenantDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:fix {subdomain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix a tenant database by running all migrations and seeding roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subdomain = $this->argument('subdomain');

        $tenant = Tenant::where('domain', $subdomain)->first();

        if (!$tenant) {
            $this->error("Tenant with subdomain '{$subdomain}' not found!");
            return 1;
        }

        $this->info("Found tenant: {$tenant->name}");
        $this->info("Database: {$tenant->database}");

        try {
            // Switch to tenant
            TenantService::switchToTenant($tenant);
            $this->info("✓ Switched to tenant database");

            // Check which tables exist
            $this->info("\n=== Checking existing tables ===");
            $tables = DB::connection('tenant')->select('SHOW TABLES');
            $tableNames = array_map(function($table) use ($tenant) {
                $key = "Tables_in_{$tenant->database}";
                return $table->$key;
            }, $tables);

            if (empty($tableNames)) {
                $this->warn("No tables found. Running all migrations...");
            } else {
                $this->info("Existing tables: " . implode(', ', $tableNames));
            }

            // Run migrations
            $this->info("\n=== Running migrations ===");

            // Core migrations
            $this->info("1. Core migrations...");
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path'     => 'database/migrations',
                '--force'    => true,
            ]);
            $this->line(Artisan::output());

            // Spatie migrations
            $this->info("2. Spatie permission migrations...");
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path'     => 'vendor/spatie/laravel-permission/database/migrations',
                '--force'    => true,
            ]);
            $this->line(Artisan::output());

            // Tenant migrations
            if (file_exists(database_path('migrations/tenant'))) {
                $this->info("3. Tenant-specific migrations...");
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path'     => 'database/migrations/tenant',
                    '--force'    => true,
                ]);
                $this->line(Artisan::output());
            }

            // Verify required tables
            $this->info("\n=== Verifying tables ===");
            $requiredTables = ['users', 'roles', 'permissions', 'model_has_roles'];

            foreach ($requiredTables as $table) {
                if (DB::connection('tenant')->getSchemaBuilder()->hasTable($table)) {
                    $this->info("✓ {$table}");
                } else {
                    $this->error("✗ {$table} - MISSING!");
                }
            }

            // Seed roles
            $this->info("\n=== Seeding roles ===");
            $existingRole = DB::connection('tenant')
                ->table('roles')
                ->where('name', 'company-admin')
                ->first();

            if ($existingRole) {
                $this->info("✓ Role 'company-admin' already exists");
            } else {
                DB::connection('tenant')->table('roles')->insert([
                    'name'       => 'company-admin',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info("✓ Role 'company-admin' created");
            }

            // Show statistics
            $this->info("\n=== Database Statistics ===");
            $userCount = DB::connection('tenant')->table('users')->count();
            $roleCount = DB::connection('tenant')->table('roles')->count();
            
            $this->info("Users: {$userCount}");
            $this->info("Roles: {$roleCount}");

            TenantService::switchToCentral();

            $this->info("\n✓ Tenant database fixed successfully!");
            return 0;

        } catch (\Exception $e) {
            $this->error("\n✗ Error: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            
            TenantService::switchToCentral();
            return 1;
        }
    }
}