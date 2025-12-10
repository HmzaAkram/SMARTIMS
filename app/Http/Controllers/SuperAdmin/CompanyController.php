<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['subscription', 'users'])
            ->withCount('users');
        
        // Search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->has('status') && in_array($request->status, ['active', 'suspended', 'trialing'])) {
            $query->where('status', $request->status);
        }
        
        // Plan filter
        if ($request->has('plan') && $request->plan) {
            $query->whereHas('subscription', function($q) use ($request) {
                $q->where('plan_name', $request->plan);
            });
        }
        
        $companies = $query->latest()->paginate(15);
        
        $stats = [
            'total' => Tenant::count(),
            'active' => Tenant::where('status', 'active')->count(),
            'trialing' => Tenant::where('status', 'trialing')->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
        ];
        
        return view('super-admin.companies.index', compact('companies', 'stats'));
    }
    
    public function create()
    {
        return view('super-admin.companies.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'domain' => 'required|string|unique:tenants,domain|alpha_dash',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'admin_name' => 'required|string',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
            'plan' => 'required|in:starter,growth,premium,enterprise',
            'status' => 'required|in:active,suspended,trialing',
            'trial_ends_at' => 'nullable|date',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create tenant
            $tenant = Tenant::create([
                'name' => $request->name,
                'email' => $request->email,
                'domain' => $request->domain,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'status' => $request->status,
                'settings' => [
                    'theme' => 'light',
                    'timezone' => 'UTC',
                    'currency' => 'USD',
                    'language' => 'en',
                ],
            ]);
            
            // Create subscription
            $planPrices = [
                'starter' => 230,
                'growth' => 450,
                'premium' => 750,
                'enterprise' => 1200,
            ];
            
            $subscription = Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_name' => $request->plan,
                'price' => $planPrices[$request->plan],
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'trial_ends_at' => $request->trial_ends_at,
                'ends_at' => $request->trial_ends_at ? now()->addDays(14) : null,
            ]);
            
            // Create admin user
            $admin = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'email_verified_at' => now(),
                'role' => 'admin',
                'status' => 'active',
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.companies.show', $tenant->id)
                ->with('success', 'Company created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create company: ' . $e->getMessage());
        }
    }
    
    public function show(Tenant $company)
    {
        $company->load(['subscription', 'users', 'payments' => function($q) {
            $q->latest()->take(10);
        }]);
        
        $stats = [
            'total_users' => $company->users()->count(),
            'active_users' => $company->users()->where('status', 'active')->count(),
            'total_assets' => DB::connection('tenant')->table('assets')->count(),
            'active_work_orders' => DB::connection('tenant')->table('work_orders')
                ->where('status', '!=', 'completed')->count(),
        ];
        
        return view('super-admin.companies.show', compact('company', 'stats'));
    }
    
    public function edit(Tenant $company)
    {
        return view('super-admin.companies.edit', compact('company'));
    }
    
    public function update(Request $request, Tenant $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('tenants')->ignore($company->id)],
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'status' => 'required|in:active,suspended,trialing',
            'settings.timezone' => 'nullable|string',
            'settings.currency' => 'nullable|string',
            'settings.language' => 'nullable|string',
        ]);
        
        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'status' => $request->status,
            'settings' => array_merge((array) $company->settings, $request->settings ?? []),
        ]);
        
        return redirect()->route('admin.companies.show', $company->id)
            ->with('success', 'Company updated successfully!');
    }
    
    public function destroy(Tenant $company)
    {
        // Check if company has active subscription
        if ($company->subscription && $company->subscription->status === 'active') {
            return back()->with('error', 'Cannot delete company with active subscription');
        }
        
        $company->delete();
        
        return redirect()->route('admin.companies.index')
            ->with('success', 'Company deleted successfully!');
    }
    
    public function suspend(Tenant $company)
    {
        $company->update(['status' => 'suspended']);
        
        return back()->with('success', 'Company suspended successfully!');
    }
    
    public function activate(Tenant $company)
    {
        $company->update(['status' => 'active']);
        
        return back()->with('success', 'Company activated successfully!');
    }
    
    public function resetTrial(Tenant $company)
    {
        if ($company->subscription) {
            $company->subscription->update([
                'trial_ends_at' => now()->addDays(14),
                'status' => 'trialing'
            ]);
        }
        
        return back()->with('success', 'Trial reset successfully!');
    }
    
    public function export()
    {
        $companies = Tenant::with(['subscription', 'users'])->get();
        
        $csv = fopen('php://temp', 'w');
        
        // Add CSV headers
        fputcsv($csv, [
            'ID', 'Name', 'Email', 'Domain', 'Plan', 'Status', 
            'Users', 'Created At', 'Monthly Revenue'
        ]);
        
        // Add data rows
        foreach ($companies as $company) {
            fputcsv($csv, [
                $company->id,
                $company->name,
                $company->email,
                $company->domain,
                $company->subscription->plan_name ?? 'N/A',
                $company->status,
                $company->users->count(),
                $company->created_at->format('Y-m-d'),
                $company->subscription->price ?? 0,
            ]);
        }
        
        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="companies_' . date('Y-m-d') . '.csv"');
    }
}