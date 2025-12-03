<?php
// app/Http/Controllers/SuperAdmin/DashboardController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Companies
        $totalCompanies = Tenant::count();
        
        // Total Users (all users in system)
        $totalUsers = User::count();
        
        // Active Subscriptions (assuming all tenants are active for now)
        // You can add a status column later and filter by it
        $activeSubscriptions = Tenant::count();
        
        // Monthly Revenue (calculated based on $230 per company/month)
        $pricePerCompany = 230;
        $monthlyRevenue = $totalCompanies * $pricePerCompany;
        
        // Get recent companies with user counts
        $recentCompanies = Tenant::latest()
            ->take(10)
            ->get()
            ->map(function ($tenant) {
                return (object) [
                    'id' => $tenant->id,
                    'name' => $tenant->name ?? 'Company #' . $tenant->id,
                    'email' => $tenant->email ?? 'N/A',
                    'domain' => $tenant->domain ?? 'N/A',
                    'plan' => 'Standard', // Default plan, update based on your business logic
                    'status' => 'active', // Default status
                    'users_count' => User::where('tenant_id', $tenant->id)->count(),
                    'created_at' => $tenant->created_at,
                    'logo' => null,
                ];
            });
        
        // Chart Data - Companies growth by month (last 6 months)
        $companiesGrowth = [];
        $revenueData = [];
        $monthLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->format('M');
            
            // Count companies created in this month
            $companiesInMonth = Tenant::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $companiesGrowth[] = $companiesInMonth;
            
            // Calculate revenue for that month (cumulative companies * price)
            $totalCompaniesUntilMonth = Tenant::where('created_at', '<=', $month->endOfMonth())
                ->count();
            $revenueData[] = $totalCompaniesUntilMonth * $pricePerCompany;
        }
        
        // Growth percentages (for stats cards)
        $lastMonth = Tenant::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $previousMonth = Tenant::whereBetween('created_at', [
            Carbon::now()->subMonths(2),
            Carbon::now()->subMonth()
        ])->count();
        
        $companiesGrowthPercent = $previousMonth > 0 
            ? round((($lastMonth - $previousMonth) / $previousMonth) * 100) 
            : 100;
        
        return view('super-admin.dashboard', compact(
            'totalCompanies',
            'totalUsers',
            'monthlyRevenue',
            'activeSubscriptions',
            'recentCompanies',
            'companiesGrowth',
            'revenueData',
            'monthLabels',
            'companiesGrowthPercent'
        ));
    }
}