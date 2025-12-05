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
        // Create tenant
        $tenant = $this->tenantService->create(
            $request->company_name,
            $request->subdomain,
            $request->company_email,
            $request->company_phone
        );

        // Create user in CENTRAL database FIRST (important!)
        $centralUser = \App\Models\User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'tenant_id'  => $tenant->id,
            'email_verified_at' => now(),
        ]);

        // Now switch to tenant database
        TenantService::switchToTenant($tenant);

        // Create user in TENANT database
        $tenantUser = \App\Models\User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'tenant_id'  => $tenant->id,
            'email_verified_at' => now(),
        ]);

        // Assign company-admin role in TENANT database
        $role = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'company-admin',
            'guard_name' => 'web'
        ]);
        
        $tenantUser->assignRole($role);

        // Switch back to central
        TenantService::switchToCentral();

        // Login the CENTRAL user (not tenant user)
        auth()->login($centralUser);

        return redirect()->route('company.dashboard', ['tenant' => $tenant->domain])
            ->with('success', 'Company registered successfully!');

    } catch (\Exception $e) {
        \Log::error('Registration failed: ' . $e->getMessage());
        
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
}