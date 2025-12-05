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
        // Log the incoming request
        Log::info('=== TenantMiddleware Start ===');
        Log::info('URL: ' . $request->fullUrl());
        Log::info('Path: ' . $request->path());
        Log::info('Method: ' . $request->method());
        Log::info('Route Parameters: ' . json_encode($request->route()->parameters() ?? []));
        
        $tenantDomain = $request->route('tenant');
        Log::info('Tenant from route: ' . ($tenantDomain ?? 'NULL'));
        
        if (!$tenantDomain) {
            Log::error('Tenant parameter not found in route');
            abort(404, 'Tenant not specified');
        }

        // Get tenant from central database
        $tenant = Tenant::where('domain', $tenantDomain)->first();
        
        if (!$tenant) {
            Log::error('Tenant not found in database: ' . $tenantDomain);
            abort(404, 'Tenant not found: ' . $tenantDomain);
        }

        Log::info('Tenant found: ' . $tenant->name . ' (DB: ' . $tenant->database . ')');
        
        // Set tenant in request for later use
        $request->merge(['current_tenant' => $tenant]);
        
        // Switch to tenant database
        try {
            TenantService::switchToTenant($tenant);
            Log::info('Switched to tenant database successfully');
        } catch (\Exception $e) {
            Log::error('Failed to switch to tenant database: ' . $e->getMessage());
            abort(500, 'Failed to connect to tenant database');
        }

        Log::info('=== TenantMiddleware End ===');
        
        return $next($request);
    }
}