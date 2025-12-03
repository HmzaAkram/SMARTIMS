<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Console\Command;

class CleanupTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:cleanup {subdomain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a tenant and its database completely';

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

        $this->warn("⚠️  WARNING: This will permanently delete:");
        $this->warn("   - Tenant: {$tenant->name}");
        $this->warn("   - Database: {$tenant->database}");
        $this->warn("   - All users and data");

        if (!$this->confirm('Are you absolutely sure?')) {
            $this->info('Cleanup cancelled.');
            return 0;
        }

        try {
            $this->info("Dropping database: {$tenant->database}");
            TenantService::dropDatabase($tenant);
            $this->info("✓ Database dropped");

            $this->info("Deleting tenant record...");
            $tenant->delete();
            $this->info("✓ Tenant deleted");

            $this->info("\n✓ Cleanup completed successfully!");
            return 0;

        } catch (\Exception $e) {
            $this->error("✗ Error: " . $e->getMessage());
            return 1;
        }
    }
}