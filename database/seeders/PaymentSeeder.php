<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all tenants
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->command->info('No tenants found. Creating sample tenant...');
            
            // Create a sample tenant if none exists
            $tenant = Tenant::create([
                'name' => 'Sample Company',
                'email' => 'sample@example.com',
                'phone' => '1234567890',
                'status' => 'active',
            ]);
            $tenants = collect([$tenant]);
        }

        foreach ($tenants as $tenant) {
            $this->command->info("Processing tenant: {$tenant->name}");
            
            // Check if tenant already has a subscription
            if (!Subscription::where('tenant_id', $tenant->id)->exists()) {
                // Create subscription
                Subscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_name' => 'starter',
                    'price' => 230.00,
                    'status' => 'active',
                    'billing_cycle' => 'monthly',
                    'trial_ends_at' => Carbon::now()->addDays(14),
                    'ends_at' => Carbon::now()->addYear(),
                    'features' => json_encode([
                        'users' => 10,
                        'storage' => '5GB',
                        'support' => 'basic'
                    ]),
                ]);
                $this->command->info("  - Created subscription for {$tenant->name}");
            }

            // Check how many payments the tenant already has
            $existingPayments = Payment::where('tenant_id', $tenant->id)->count();
            
            if ($existingPayments < 3) {
                // Create payments (up to 3)
                $paymentsToCreate = 3 - $existingPayments;
                
                for ($i = 1; $i <= $paymentsToCreate; $i++) {
                    $dueDate = Carbon::now()->subMonths($i)->addMonth();
                    $paidDate = Carbon::now()->subMonths($i);
                    
                    // Generate unique invoice number
                    $invoiceNumber = 'INV-' . date('Ym') . '-' . str_pad(($existingPayments + $i), 5, '0', STR_PAD_LEFT);
                    
                    // Check if invoice number already exists
                    while (Payment::where('invoice_number', $invoiceNumber)->exists()) {
                        $invoiceNumber = 'INV-' . date('Ym') . '-' . str_pad(($existingPayments + $i + 1000), 5, '0', STR_PAD_LEFT);
                    }
                    
                    Payment::create([
                        'tenant_id' => $tenant->id,
                        'invoice_number' => $invoiceNumber,
                        'amount' => 230.00,
                        'currency' => 'USD',
                        'status' => 'completed',
                        'payment_method' => 'card',
                        'transaction_id' => 'txn_' . Str::random(10),
                        'due_date' => $dueDate,
                        'paid_at' => $paidDate,
                        'metadata' => json_encode([
                            'billing_period' => $paidDate->format('F Y'),
                            'plan' => 'starter',
                            'tenant_name' => $tenant->name,
                        ]),
                    ]);
                }
                $this->command->info("  - Created {$paymentsToCreate} payments for {$tenant->name}");
            } else {
                $this->command->info("  - {$tenant->name} already has {$existingPayments} payments");
            }
        }

        $this->command->info('Payment seeding completed!');
    }
}