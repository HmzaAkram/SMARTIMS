<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::query();
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $companies = $query->withCount('users')->paginate(20);
        
        $stats = [
            'total' => Tenant::count(),
            'active' => Tenant::where('status', 'active')->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
            'trialing' => Tenant::where('status', 'trialing')->count(),
        ];
        
        return view('super-admin.companies.index', compact('companies', 'stats'));
    }
    
    public function create()
    {
        return view('super-admin.companies.create');
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'phone' => 'nullable|string',
            'domain' => 'required|string|unique:tenants,domain|regex:/^[a-zA-Z0-9]+$/',
            'plan' => 'required|in:starter,growth,premium,enterprise',
            'status' => 'required|in:active,suspended,trialing',
            'admin_name' => 'required|string',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Create tenant
            $tenant = Tenant::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'domain' => $request->domain,
                'status' => $request->status,
                'settings' => [
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
            
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_name' => $request->plan,
                'price' => $planPrices[$request->plan],
                'status' => $request->status == 'trialing' ? 'trialing' : 'active',
                'billing_cycle' => 'monthly',
                'trial_ends_at' => $request->status == 'trialing' ? now()->addDays(14) : null,
                'ends_at' => $request->status == 'trialing' ? now()->addDays(14) : now()->addYear(),
            ]);
            
            // Create admin user
            User::create([
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
            return redirect()->back()
                ->with('error', 'Failed to create company: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function show(Tenant $company)
    {
        $company->load(['subscription', 'users', 'payments' => function($q) {
            $q->latest()->take(10);
        }]);
        
        // Get tenant database stats
        $tenantStats = [];
        try {
            DB::connection('tenant')->getPdo();
            $tenantStats = [
                'users' => DB::connection('tenant')->table('users')->count(),
                'items' => DB::connection('tenant')->table('items')->count(),
                'orders' => DB::connection('tenant')->table('orders')->count(),
                'warehouses' => DB::connection('tenant')->table('warehouses')->count(),
            ];
        } catch (\Exception $e) {
            $tenantStats = [
                'users' => 0,
                'items' => 0,
                'orders' => 0,
                'warehouses' => 0,
            ];
        }
        
        return view('super-admin.companies.show', compact('company', 'tenantStats'));
    }
    
    public function edit(Tenant $company)
    {
        $company->load('subscription');
        return view('super-admin.companies.edit', compact('company'));
    }
    
    public function update(Request $request, Tenant $company)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('tenants')->ignore($company->id)],
            'phone' => 'nullable|string',
            'domain' => ['required', 'string', 'regex:/^[a-zA-Z0-9]+$/', Rule::unique('tenants')->ignore($company->id)],
            'status' => 'required|in:active,suspended,trialing',
            'plan' => 'required|in:starter,growth,premium,enterprise',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Update tenant
            $company->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'domain' => $request->domain,
                'status' => $request->status,
            ]);
            
            // Update subscription
            $planPrices = [
                'starter' => 230,
                'growth' => 450,
                'premium' => 750,
                'enterprise' => 1200,
            ];
            
            if ($company->subscription) {
                $company->subscription->update([
                    'plan_name' => $request->plan,
                    'price' => $planPrices[$request->plan],
                    'status' => $request->status == 'trialing' ? 'trialing' : 'active',
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.companies.show', $company->id)
                ->with('success', 'Company updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update company: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function destroy(Tenant $company)
    {
        // Check if company has active subscription
        if ($company->subscription && $company->subscription->status == 'active') {
            return back()->with('error', 'Cannot delete company with active subscription');
        }
        
        $company->delete();
        
        return redirect()->route('admin.companies.index')
            ->with('success', 'Company deleted successfully!');
    }
    
    public function suspend(Tenant $company)
    {
        $company->update(['status' => 'suspended']);
        
        if ($company->subscription) {
            $company->subscription->update(['status' => 'suspended']);
        }
        
        return back()->with('success', 'Company suspended successfully!');
    }
    
    public function activate(Tenant $company)
    {
        $company->update(['status' => 'active']);
        
        if ($company->subscription) {
            $company->subscription->update(['status' => 'active']);
        }
        
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
        
        return back()->with('success', 'Trial reset to 14 days!');
    }
    
    public function export()
    {
        $companies = Tenant::with(['subscription', 'users'])->get();
        
        $csv = "ID,Name,Email,Domain,Plan,Status,Users,Created At,Monthly Revenue\n";
        
        foreach ($companies as $company) {
            $csv .= implode(',', [
                $company->id,
                '"' . $company->name . '"',
                $company->email,
                $company->domain,
                $company->subscription->plan_name ?? 'N/A',
                $company->status,
                $company->users->count(),
                $company->created_at->format('Y-m-d'),
                $company->subscription->price ?? 0,
            ]) . "\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="companies_' . date('Y-m-d') . '.csv"');
    }
}