<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Tenant;
use App\Services\TenantService;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
{
    // Add debug before authentication
    \Log::info('Login attempt for email: ' . $request->email);
    
    $request->authenticate();
    $request->session()->regenerate();

    $user = auth()->user();
    
    // DEBUG: Log which user actually logged in
    \Log::info('=== ACTUAL LOGGED IN USER ===');
    \Log::info('User ID: ' . $user->id);
    \Log::info('User Email: ' . $user->email);
    \Log::info('User Tenant ID: ' . $user->tenant_id);
    
    if ($user->tenant) {
        \Log::info('Tenant Domain: ' . $user->tenant->domain);
        \Log::info('Tenant Name: ' . $user->tenant->name);
    }
    
    // If user has tenant_id = company user, else = super admin
    if ($user->tenant_id) {
        // Get tenant
        $tenant = \App\Models\Tenant::find($user->tenant_id);
        
        if (!$tenant) {
            \Log::error('Tenant not found for ID: ' . $user->tenant_id);
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Tenant not found for your account.',
            ]);
        }
        
        \Log::info('Redirecting to: /company/' . $tenant->domain . '/dashboard');
        
        return redirect()->route('company.dashboard', ['tenant' => $tenant->domain])
            ->with('success', 'Welcome to ' . $tenant->name . '!');
        
    } else {
        // Super admin - check role to be sure
        if ($user->hasRole('super-admin')) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Welcome Super Admin!');
        }
        
        // If somehow a user has no tenant_id and no super-admin role
        Auth::logout();
        return redirect()->route('login')->withErrors([
            'email' => 'Your account is not properly configured.',
        ]);
    }
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}