<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TestMigration extends Command
{
    protected $signature = 'test:migration {database}';
    protected $description = 'Test running migrations on a specific database';

    public function handle()
    {
        $database = $this->argument('database');

        $this->info("=== Testing Migration on Database: {$database} ===");

        try {
            // Step 1: Check if database exists
            $this->info("\n1. Checking if database exists...");
            $result = DB::connection('mysql')->select("SHOW DATABASES LIKE '{$database}'");
            
            if (empty($result)) {
                $this->error("Database '{$database}' does not exist!");
                
                if ($this->confirm('Create database?')) {
                    DB::connection('mysql')->statement("CREATE DATABASE `{$database}`");
                    $this->info("✓ Database created");
                } else {
                    return 1;
                }
            } else {
                $this->info("✓ Database exists");
            }

            // Step 2: Configure tenant connection
            $this->info("\n2. Configuring tenant connection...");
            config(['database.connections.tenant.database' => $database]);
            DB::purge('tenant');
            DB::reconnect('tenant');
            $this->info("✓ Connection configured");

            // Step 3: Verify connection
            $this->info("\n3. Verifying connection...");
            $currentDb = DB::connection('tenant')->select('SELECT DATABASE() as db')[0]->db;
            $this->info("Current database: {$currentDb}");
            
            if ($currentDb !== $database) {
                $this->error("Connection mismatch! Expected: {$database}, Got: {$currentDb}");
                return 1;
            }
            $this->info("✓ Connection verified");

            // Step 4: Check existing tables
            $this->info("\n4. Checking existing tables...");
            $tables = DB::connection('tenant')->select('SHOW TABLES');
            
            if (empty($tables)) {
                $this->warn("No tables found in database");
            } else {
                $key = "Tables_in_{$database}";
                $tableNames = array_map(fn($t) => $t->$key, $tables);
                $this->info("Existing tables: " . implode(', ', $tableNames));
            }

            // Step 5: Run core migrations
            if ($this->confirm("\n5. Run core migrations?", true)) {
                $this->info("Running core migrations...");
                
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path'     => 'database/migrations',
                    '--force'    => true,
                ]);
                
                $this->line(Artisan::output());
                
                // Check tables again
                $tables = DB::connection('tenant')->select('SHOW TABLES');
                $this->info("Tables after core migration: " . count($tables));
            }

            // Step 6: Run Spatie migrations
            if ($this->confirm("\n6. Run Spatie permission migrations?", true)) {
                $this->info("Running Spatie migrations...");
                
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path'     => 'vendor/spatie/laravel-permission/database/migrations',
                    '--force'    => true,
                ]);
                
                $this->line(Artisan::output());
                
                // Check tables again
                $tables = DB::connection('tenant')->select('SHOW TABLES');
                $this->info("Tables after Spatie migration: " . count($tables));
            }

            // Step 7: Run tenant migrations
            if (file_exists(database_path('migrations/tenant'))) {
                if ($this->confirm("\n7. Run tenant-specific migrations?", true)) {
                    $this->info("Running tenant migrations...");
                    
                    Artisan::call('migrate', [
                        '--database' => 'tenant',
                        '--path'     => 'database/migrations/tenant',
                        '--force'    => true,
                    ]);
                    
                    $this->line(Artisan::output());
                }
            }

            // Step 8: Final table check
            $this->info("\n8. Final table verification:");
            $tables = DB::connection('tenant')->select('SHOW TABLES');
            $key = "Tables_in_{$database}";
            $tableNames = array_map(fn($t) => $t->$key, $tables);
            
            $this->info("Total tables: " . count($tables));
            $this->info("Tables: " . implode(', ', $tableNames));

            // Check required tables
            $required = ['users', 'roles', 'permissions', 'model_has_roles'];
            $this->info("\nRequired tables status:");
            
            foreach ($required as $table) {
                if (in_array($table, $tableNames)) {
                    $this->info("✓ {$table}");
                } else {
                    $this->error("✗ {$table} - MISSING!");
                }
            }

            $this->info("\n✓ Test completed!");
            return 0;

        } catch (\Exception $e) {
            $this->error("\n✗ Error: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}