<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\TenantService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class TenantMiddleware
{
   public function handle(Request $request, Closure $next): Response
{
    \Log::info('=== TenantMiddleware START ===');
    \Log::info('Full URL: ' . $request->fullUrl());
    \Log::info('Path: ' . $request->path());
    \Log::info('Tenant param from route: ' . $request->route('tenant'));
    
    $tenantDomain = $request->route('tenant');
    
    if (!$tenantDomain) {
        \Log::error('No tenant parameter in route');
        abort(404, 'Tenant not specified');
    }

    // Get tenant from central database
    $tenant = Tenant::where('domain', $tenantDomain)->first();
    
    if (!$tenant) {
        \Log::error('Tenant not found in DB: ' . $tenantDomain);
        abort(404, 'Tenant not found: ' . $tenantDomain);
    }

    \Log::info('Tenant found: ' . $tenant->name . ' (DB: ' . $tenant->database . ')');
    
    // Switch to tenant database
    try {
        TenantService::switchToTenant($tenant);
        \Log::info('Switched to tenant DB successfully');
    } catch (\Exception $e) {
        \Log::error('Failed to switch to tenant DB: ' . $e->getMessage());
        abort(500, 'Failed to connect to tenant database: ' . $e->getMessage());
    }

    \Log::info('=== TenantMiddleware END ===');
    
    return $next($request);
}
}