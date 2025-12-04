<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantService
{
    public function create(string $name, string $subdomain, string $email, string $phone): Tenant
    {
        $databaseName = 'smartims_' . Str::slug($subdomain, '_');

        $tenant = Tenant::create([
            'name'     => $name,
            'domain'   => $subdomain,
            'email'    => $email,
            'phone'    => $phone,
            'slug'     => Str::slug($name),
            'database' => $databaseName,
            'status'   => 'active',
        ]);

        \Log::info("=== Starting Tenant Creation: {$subdomain} ===");
        \Log::info("Database name: {$databaseName}");

        try {
            // Step 1: Create database
            $this->createDatabase($tenant);

            // Step 2: Switch to tenant connection
            $this->switchToTenant($tenant);

            // CRITICAL: Verify connection is actually working
            $this->verifyConnection($tenant);

            // Step 3: Run migrations with PROPER connection verification
            $this->runMigrationsWithVerification($tenant);

            // Step 4: Verify all tables exist
            $this->verifyTablesExist();

            // Step 5: Seed default roles
            $this->seedDefaultRoles();

            \Log::info("=== Tenant {$subdomain} created successfully ===");

            return $tenant;

        } catch (\Exception $e) {
            \Log::error("=== Tenant creation failed: " . $e->getMessage() . " ===");
            \Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    private function createDatabase(Tenant $tenant): void
    {
        try {
            // Use central connection explicitly
            $connection = DB::connection('mysql');
            
            $connection->statement(
                "CREATE DATABASE IF NOT EXISTS `{$tenant->database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
            );
            
            \Log::info("✓ Database created: {$tenant->database}");
            
            // Verify database was created
            $result = $connection->select("SHOW DATABASES LIKE '{$tenant->database}'");
            if (empty($result)) {
                throw new \Exception("Database {$tenant->database} was not created!");
            }
            
            \Log::info("✓ Database existence verified");
            
        } catch (\Exception $e) {
            \Log::error("✗ Failed to create database: " . $e->getMessage());
            throw $e;
        }
    }

    public static function switchToTenant(Tenant $tenant): void
    {
        // Clear any existing tenant instance
        app()->forgetInstance('tenant');
        
        // Set new tenant instance
        app()->instance('tenant', $tenant);
        
        // Configure tenant connection
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $tenant->database,
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
        
        // Purge and reconnect
        DB::purge('tenant');
        DB::reconnect('tenant');

        // Set tenant as the default connection
        DB::setDefaultConnection('tenant');
        
        \Log::info("✓ Switched to tenant database: {$tenant->database}");
    }

    private function verifyConnection(Tenant $tenant): void
    {
        try {
            // Try to select from the database
            $result = DB::connection('tenant')->select('SELECT DATABASE() as db');
            $currentDb = $result[0]->db ?? null;
            
            \Log::info("Current tenant connection database: {$currentDb}");
            
            if ($currentDb !== $tenant->database) {
                throw new \Exception("Connection verification failed! Expected: {$tenant->database}, Got: {$currentDb}");
            }
            
            \Log::info("✓ Tenant connection verified");
            
        } catch (\Exception $e) {
            \Log::error("✗ Connection verification failed: " . $e->getMessage());
            throw $e;
        }
    }

    public static function switchToCentral(): void
    {
        app()->forgetInstance('tenant');
        DB::purge('tenant');
        DB::setDefaultConnection('mysql');
        \Log::info("✓ Switched back to central database");
    }

    private function runMigrationsWithVerification(Tenant $tenant): void
{
    \Log::info("--- Creating tenant database tables ---");

    // 1. Create users table (CRITICAL)
    $this->createTenantUsersTable();
    
    // 2. Create other required tables with SQL
    $this->createRequiredTablesWithSQL();
    
    \Log::info("--- Tenant database tables created ---");
}

private function createRequiredTablesWithSQL(): void
{
    $tables = [
        'password_reset_tokens' => "
            CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
                `email` varchar(255) NOT NULL,
                `token` varchar(255) NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'failed_jobs' => "
            CREATE TABLE IF NOT EXISTS `failed_jobs` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `uuid` varchar(255) NOT NULL,
                `connection` text NOT NULL,
                `queue` text NOT NULL,
                `payload` longtext NOT NULL,
                `exception` longtext NOT NULL,
                `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'permissions' => "
            CREATE TABLE IF NOT EXISTS `permissions` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `guard_name` varchar(255) NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'roles' => "
            CREATE TABLE IF NOT EXISTS `roles` (
                `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `guard_name` varchar(255) NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'model_has_permissions' => "
            CREATE TABLE IF NOT EXISTS `model_has_permissions` (
                `permission_id` bigint unsigned NOT NULL,
                `model_type` varchar(255) NOT NULL,
                `model_id` bigint unsigned NOT NULL,
                PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
                KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
                CONSTRAINT `model_has_permissions_permission_id_foreign` 
                FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'model_has_roles' => "
            CREATE TABLE IF NOT EXISTS `model_has_roles` (
                `role_id` bigint unsigned NOT NULL,
                `model_type` varchar(255) NOT NULL,
                `model_id` bigint unsigned NOT NULL,
                PRIMARY KEY (`role_id`,`model_id`,`model_type`),
                KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
                CONSTRAINT `model_has_roles_role_id_foreign` 
                FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        
        'role_has_permissions' => "
            CREATE TABLE IF NOT EXISTS `role_has_permissions` (
                `permission_id` bigint unsigned NOT NULL,
                `role_id` bigint unsigned NOT NULL,
                PRIMARY KEY (`permission_id`,`role_id`),
                KEY `role_has_permissions_role_id_foreign` (`role_id`),
                CONSTRAINT `role_has_permissions_permission_id_foreign` 
                FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
                CONSTRAINT `role_has_permissions_role_id_foreign` 
                FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        "
    ];
    
    foreach ($tables as $tableName => $sql) {
        try {
            DB::connection('tenant')->statement($sql);
            \Log::info("✓ Table created: {$tableName}");
        } catch (\Exception $e) {
            \Log::error("✗ Failed to create table {$tableName}: " . $e->getMessage());
            // Continue with other tables
        }
    }
}

    private function runMigration(string $path, string $label, Tenant $tenant): void
    {
        try {
            // Verify we're still connected to the right database BEFORE running migration
            $currentDb = DB::connection('tenant')->select('SELECT DATABASE() as db')[0]->db;
            
            if ($currentDb !== $tenant->database) {
                throw new \Exception("Lost connection to tenant database! Current: {$currentDb}, Expected: {$tenant->database}");
            }
            
            \Log::info("  → Running {$label} migrations on database: {$currentDb}");
            
            // Run migration
            $exitCode = Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path'     => $path,
                '--force'    => true,
            ]);

            $output = trim(Artisan::output());
            \Log::info("  → {$label} output:\n{$output}");

            if ($exitCode !== 0) {
                throw new \Exception("{$label} migrations failed with exit code: {$exitCode}");
            }

            // Verify tables were actually created
            $tables = DB::connection('tenant')->select('SHOW TABLES');
            \Log::info("  → Tables after {$label}: " . count($tables));

            \Log::info("✓ {$label} migrations completed successfully");

        } catch (\Exception $e) {
            \Log::error("✗ {$label} migrations failed: " . $e->getMessage());
            throw $e;
        }
    }

    private function verifyTablesExist(): void
    {
        \Log::info("--- Verifying required tables ---");

        $requiredTables = [
            'users',
            'roles',
            'permissions',
            'model_has_roles',
            'model_has_permissions',
            'role_has_permissions'
        ];

        // Get all tables
        $tablesResult = DB::connection('tenant')->select('SHOW TABLES');
        $dbName = DB::connection('tenant')->getDatabaseName();
        $key = "Tables_in_{$dbName}";
        
        $existingTables = array_map(function($table) use ($key) {
            return $table->$key;
        }, $tablesResult);

        \Log::info("Existing tables in database: " . implode(', ', $existingTables));

        $missingTables = [];

        foreach ($requiredTables as $table) {
            if (in_array($table, $existingTables)) {
                \Log::info("✓ Table exists: {$table}");
            } else {
                \Log::error("✗ Table missing: {$table}");
                $missingTables[] = $table;
            }
        }

        if (!empty($missingTables)) {
            throw new \Exception('Missing required tables: ' . implode(', ', $missingTables));
        }

        \Log::info("✓ All required tables verified");
    }

    private function seedDefaultRoles(): void
    {
        \Log::info("--- Seeding default roles ---");

        try {
            // Clear permission cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Check if role already exists
            $existingRole = DB::connection('tenant')
                ->table('roles')
                ->where('name', 'company-admin')
                ->where('guard_name', 'web')
                ->first();

            if ($existingRole) {
                \Log::info("✓ Role 'company-admin' already exists");
                return;
            }

            // Insert role
            DB::connection('tenant')->table('roles')->insert([
                'name'       => 'company-admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \Log::info("✓ Default role 'company-admin' created successfully");

        } catch (\Exception $e) {
            \Log::error("✗ Failed to seed roles: " . $e->getMessage());
            throw $e;
        }
    }

    public static function dropDatabase(Tenant $tenant): void
    {
        try {
            DB::connection('mysql')->statement("DROP DATABASE IF EXISTS `{$tenant->database}`");
            \Log::info("✓ Database dropped: {$tenant->database}");
        } catch (\Exception $e) {
            \Log::error("✗ Failed to drop database: " . $e->getMessage());
            throw $e;
        }
    }
    // Add this method to your TenantService class
private function createTenantUsersTable(): void
{
    \Log::info("Creating users table for tenant...");
    
    // Check if users table already exists
    $tables = DB::connection('tenant')->select('SHOW TABLES');
    $dbName = DB::connection('tenant')->getDatabaseName();
    $key = "Tables_in_{$dbName}";
    
    $existingTables = array_map(function($table) use ($key) {
        return $table->$key;
    }, $tables);
    
    if (in_array('users', $existingTables)) {
        \Log::info("Users table already exists");
        return;
    }
    
    // Create users table with SQL
    DB::connection('tenant')->statement("
        CREATE TABLE `users` (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `email_verified_at` timestamp NULL DEFAULT NULL,
            `password` varchar(255) NOT NULL,
            `remember_token` varchar(100) DEFAULT NULL,
            `tenant_id` bigint unsigned DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `users_email_unique` (`email`),
            KEY `users_tenant_id_index` (`tenant_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    
    \Log::info("✓ Users table created successfully");
}
}