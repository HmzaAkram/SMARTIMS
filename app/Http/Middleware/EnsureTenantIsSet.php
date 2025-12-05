<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\TenantService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class EnsureTenantIsSet
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('=== EnsureTenantIsSet Middleware ===');
        
        $tenantDomain = $request->route('tenant');
        
        if (!$tenantDomain) {
            abort(404, 'Tenant not specified');
        }

        // Get tenant from central database
        $tenant = Tenant::where('domain', $tenantDomain)->first();
        
        if (!$tenant) {
            abort(404, 'Tenant not found: ' . $tenantDomain);
        }

        // Set tenant in request
        $request->merge(['current_tenant' => $tenant]);
        
        // Switch to tenant database
        TenantService::switchToTenant($tenant);

        return $next($request);
    }
}