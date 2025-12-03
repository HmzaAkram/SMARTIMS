<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SetPermissionsTeam
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($tenant = Tenant::current()) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
        }

        return $next($request);
    }
}