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
            'email'          => 'required|email',
            'password'       => 'required|min:8|confirmed',
            'plan'           => 'required|in:free,standard,premium',
            'terms'          => 'required|accepted'
        ]);

        $tenant = null;

        try {
            $tenant = $this->tenantService->create(
                $request->company_name,
                $request->subdomain,
                $request->company_email,
                $request->company_phone
            );

            TenantService::switchToTenant($tenant);

            $user = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'tenant_id'  => $tenant->id,
            ]);

            Role::firstOrCreate([
                'name'       => 'company-admin',
                'guard_name' => 'web'
            ]);

            $user->assignRole('company-admin');

            auth()->login($user);
            TenantService::switchToCentral();

            return redirect("/company/{$request->subdomain}/dashboard")
                ->with('success', 'Company registered successfully!');

        } catch (\Exception $e) {
            if ($tenant) {
                TenantService::dropDatabase($tenant);
                $tenant->delete();
            }
            TenantService::switchToCentral();

            return back()->withInput()->withErrors([
                'error' => 'Registration failed: ' . $e->getMessage()
            ]);
        }
    }
}