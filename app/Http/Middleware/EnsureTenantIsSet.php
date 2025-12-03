<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class EnsureTenantIsSet
{
    public function handle(Request $request, Closure $next)
    {
        $tenantSlug = $request->route('tenant');

        if (!$tenantSlug) {
            abort(404, 'Tenant not specified');
        }

        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Set tenant database
        $databaseName = 'smartims_' . $tenant->slug;
        Config::set('database.connections.tenant.database', $databaseName);
        
        // Reconnect with new database
        DB::purge('tenant');
        DB::reconnect('tenant');

        // Store tenant in request
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}