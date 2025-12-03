<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class SetupTenant extends Command
{
    protected $signature = 'tenant:setup {slug} {name} {email}';
    protected $description = 'Setup a new tenant with database and migrations';

    public function handle()
    {
        $slug = $this->argument('slug');
        $name = $this->argument('name');
        $email = $this->argument('email');
        
        $databaseName = 'smartims_' . $slug;

        // Step 1: Create tenant record
        $this->info("Creating tenant record...");
        $tenant = Tenant::create([
            'name' => $name,
            'slug' => $slug,
            'email' => $email,
        ]);
        $this->info("✓ Tenant created: {$name}");

        // Step 2: Create database
        $this->info("Creating database: {$databaseName}...");
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
            $this->info("✓ Database created");
        } catch (\Exception $e) {
            $this->error("Failed to create database: " . $e->getMessage());
            return;
        }

        // Step 3: Run migrations
        $this->info("Running migrations...");
        Config::set('database.connections.tenant.database', $databaseName);
        DB::purge('tenant');
        DB::reconnect('tenant');

        try {
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);
            $this->info("✓ Migrations completed");
            $this->line(Artisan::output());
        } catch (\Exception $e) {
            $this->error("Migration failed: " . $e->getMessage());
        }

        $this->info("🎉 Tenant setup completed successfully!");
        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $name],
                ['Slug', $slug],
                ['Database', $databaseName],
                ['Email', $email],
            ]
        );
    }
}