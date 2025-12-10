<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with('tenant');
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('tenant', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Plan filter
        if ($request->has('plan') && $request->plan != 'all') {
            $query->where('plan_name', $request->plan);
        }
        
        $subscriptions = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Subscription::count(),
            'active' => Subscription::where('status', 'active')->count(),
            'trialing' => Subscription::where('status', 'trialing')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
            'revenue' => Subscription::where('status', 'active')->sum('price'),
        ];
        
        return view('super-admin.subscriptions.index', compact('subscriptions', 'stats'));
    }
    
    public function create()
    {
        $tenants = Tenant::where('status', 'active')->get();
        return view('super-admin.subscriptions.create', compact('tenants'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'plan_name' => 'required|in:starter,growth,premium,enterprise',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,trialing,cancelled',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'trial_ends_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:today',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Subscription::create([
            'tenant_id' => $request->tenant_id,
            'plan_name' => $request->plan_name,
            'price' => $request->price,
            'status' => $request->status,
            'billing_cycle' => $request->billing_cycle,
            'trial_ends_at' => $request->trial_ends_at,
            'ends_at' => $request->ends_at,
            'features' => $this->getPlanFeatures($request->plan_name),
        ]);
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription created successfully!');
    }
    
    public function show(Subscription $subscription)
    {
        $subscription->load(['tenant', 'payments']);
        return view('super-admin.subscriptions.show', compact('subscription'));
    }
    
    public function edit(Subscription $subscription)
    {
        $subscription->load('tenant');
        return view('super-admin.subscriptions.edit', compact('subscription'));
    }
    
    public function update(Request $request, Subscription $subscription)
    {
        $validator = Validator::make($request->all(), [
            'plan_name' => 'required|in:starter,growth,premium,enterprise',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,trialing,cancelled',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'trial_ends_at' => 'nullable|date',
            'ends_at' => 'nullable|date',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $subscription->update([
            'plan_name' => $request->plan_name,
            'price' => $request->price,
            'status' => $request->status,
            'billing_cycle' => $request->billing_cycle,
            'trial_ends_at' => $request->trial_ends_at,
            'ends_at' => $request->ends_at,
            'features' => $this->getPlanFeatures($request->plan_name),
        ]);
        
        return redirect()->route('admin.subscriptions.show', $subscription->id)
            ->with('success', 'Subscription updated successfully!');
    }
    
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        
        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription deleted successfully!');
    }
    
    public function cancel(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'cancelled',
            'ends_at' => now(),
        ]);
        
        return back()->with('success', 'Subscription cancelled!');
    }
    
    public function renew(Subscription $subscription)
    {
        $subscription->update([
            'status' => 'active',
            'ends_at' => Carbon::now()->addYear(),
        ]);
        
        return back()->with('success', 'Subscription renewed for 1 year!');
    }
    
    private function getPlanFeatures($plan)
    {
        $features = [
            'starter' => [
                'users' => 10,
                'storage' => '5GB',
                'support' => 'email',
                'api_access' => false,
                'reports' => 'basic',
            ],
            'growth' => [
                'users' => 50,
                'storage' => '50GB',
                'support' => 'priority',
                'api_access' => true,
                'reports' => 'advanced',
            ],
            'premium' => [
                'users' => 200,
                'storage' => '200GB',
                'support' => '24/7',
                'api_access' => true,
                'reports' => 'enterprise',
            ],
            'enterprise' => [
                'users' => 'unlimited',
                'storage' => 'unlimited',
                'support' => 'dedicated',
                'api_access' => true,
                'reports' => 'custom',
            ],
        ];
        
        return $features[$plan] ?? $features['starter'];
    }
}