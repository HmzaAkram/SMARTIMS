<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\TenantService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantSlug = $request->route('tenant');
        
        if (!$tenantSlug) {
            abort(404, 'Tenant not specified');
        }

        // Get tenant from central database
        $tenant = Tenant::where('domain', $tenantSlug)->first();
        
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Set tenant in request for later use
        $request->merge(['current_tenant' => $tenant]);
        
        // Switch to tenant database
        TenantService::switchToTenant($tenant);

        return $next($request);
    }
}