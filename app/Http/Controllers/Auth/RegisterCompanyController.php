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

        // Create user in CENTRAL database
        $centralUser = \App\Models\User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'tenant_id'  => $tenant->id,
            'email_verified_at' => now(),
        ]);

        // Now switch to tenant database
        TenantService::switchToTenant($tenant);

        // IMPORTANT: Use a different email for tenant user or use tenant-specific email
        // Option 1: Append tenant ID to email to make it unique
        $tenantEmail = $request->email; // This is the same email, which might cause issues
        
        // Better approach: Use a different email format or check if email exists in tenant DB
        $tenantEmail = $tenant->id . '_' . $request->email; // Prefix with tenant ID
        
        // Or Option 2: Use the same email but make tenant DB connection unique
        // Since tenant databases are separate, the same email should be allowed
        // The issue might be with the database connection not switching properly
        
        // Let's check if tenant database connection is working
        try {
            // Test tenant connection
            DB::connection('tenant')->getPdo();
            
            // Create user in TENANT database with tenant-specific connection
            $tenantUser = new \App\Models\User();
            $tenantUser->setConnection('tenant'); // Explicitly set connection
            
            $tenantUser->fill([
                'name'       => $request->name,
                'email'      => $request->email, // Use same email - should be OK in separate DB
                'password'   => Hash::make($request->password),
                'tenant_id'  => $tenant->id,
                'email_verified_at' => now(),
            ])->save();
            
        } catch (\Exception $e) {
            \Log::error('Tenant DB connection failed: ' . $e->getMessage());
            throw $e;
        }

        // Assign company-admin role in TENANT database
        $role = \Spatie\Permission\Models\Role::on('tenant')->firstOrCreate([
            'name' => 'company-admin',
            'guard_name' => 'web'
        ]);
        
        $tenantUser->assignRole($role);

        // Switch back to central
        TenantService::switchToCentral();

        // Login the CENTRAL user
        auth()->login($centralUser);

        return redirect()->route('company.dashboard', ['tenant' => $tenant->domain])
            ->with('success', 'Company registered successfully!');

    } catch (\Exception $e) {
        \Log::error('Registration failed: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
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