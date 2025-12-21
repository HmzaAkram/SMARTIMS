<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegisterCompanyController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function create()
    {
        return view('auth.register');
    }

public function store(Request $request)
{
    $request->validate([
        'company_name'   => 'required|string|max:255',
        'company_email'  => 'required|email|max:255',
        'company_phone'  => 'required|string|max:20',
        'subdomain'      => 'required|alpha_dash|unique:tenants,domain',
        'name'           => 'required|string|max:255',
        'email'          => 'required|email|unique:users,email',
        'password'       => 'required|min:8|confirmed',
        'plan'           => 'required|in:free,standard,premium',
        'terms'          => 'required|accepted'
    ]);

    $tenant = null;

    try {
        // Create tenant (this will create DB and run migrations)
        $tenant = $this->tenantService->create(
            $request->company_name,
            $request->subdomain,
            $request->company_email,
            $request->company_phone
        );

        // Create user in CENTRAL database
        $centralUser = \App\Models\User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'tenant_id'  => $tenant->id,
            'email_verified_at' => now(),
        ]);

        // Switch to tenant database
        TenantService::switchToTenant($tenant);

        // Create same user in TENANT database
        $tenantUser = \App\Models\User::on('tenant')->create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'tenant_id'  => $tenant->id,
            'email_verified_at' => now(),
        ]);

        // Role 'company-admin' is already created by TenantService::seedDefaultRoles()
        
        $tenantUser->assignRole('company-admin');

        // Create subscription in central DB
        \App\Models\Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_name' => $request->plan,
            'price' => $this->getPlanPrice($request->plan),
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'trial_ends_at' => now()->addDays(30),
            'features' => $this->getPlanFeatures($request->plan),
        ]);

        // Switch back to central
        TenantService::switchToCentral();

        // Login the user
        auth()->login($centralUser);

        return redirect()->route('company.dashboard', ['tenant' => $tenant->domain])
            ->with('success', 'Company registered successfully!');

    } catch (\Exception $e) {
        \Log::error('Registration failed: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        if ($tenant) {
            try {
                TenantService::dropDatabase($tenant);
                $tenant->delete();
            } catch (\Exception $cleanupError) {
                \Log::error('Cleanup error: ' . $cleanupError->getMessage());
            }
        }
        
        TenantService::switchToCentral();

        return back()->withInput()->withErrors([
            'error' => 'Registration failed: ' . $e->getMessage()
        ]);
    }
}

private function getPlanPrice($plan)
{
    return match($plan) {
        'free' => 0.00,
        'standard' => 29.00,
        'premium' => 79.00,
        default => 0.00
    };
}

private function getPlanFeatures($plan)
{
    return match($plan) {
        'free' => ['users' => 1, 'storage' => '1GB', 'support' => 'email'],
        'standard' => ['users' => 5, 'storage' => '10GB', 'support' => 'priority'],
        'premium' => ['users' => 'unlimited', 'storage' => '100GB', 'support' => '24/7'],
    };
}
}