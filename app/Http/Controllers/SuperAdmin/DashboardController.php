<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Companies with growth percentage
        $totalCompanies = Tenant::count();
        $lastMonthCompanies = Tenant::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $previousMonthCompanies = Tenant::whereBetween('created_at', [
            Carbon::now()->subMonths(2),
            Carbon::now()->subMonth()
        ])->count();
        
        $companiesGrowthPercent = $previousMonthCompanies > 0 
            ? round((($lastMonthCompanies - $previousMonthCompanies) / $previousMonthCompanies) * 100, 1)
            : ($lastMonthCompanies > 0 ? 100 : 0);

        // Active Subscriptions
        $activeSubscriptions = Tenant::where('status', 'active')->count();
        $trialingSubscriptions = Tenant::where('status', 'trialing')->count();

        // Monthly Revenue (current month)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $monthlyRevenue = Payment::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', 'completed')
            ->sum('amount');
        
        // Calculate revenue growth
        $lastMonthRevenue = Payment::whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->where('status', 'completed')
            ->sum('amount');
        
        $revenueGrowthPercent = $lastMonthRevenue > 0 
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($monthlyRevenue > 0 ? 100 : 0);

        // Total Users
        $totalUsers = User::count();
        
        // Recent companies with more details
        $recentCompanies = Tenant::withCount('users')
            ->with(['subscription' => function($q) {
                $q->latest();
            }])
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($tenant) {
                return (object) [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'email' => $tenant->email,
                    'domain' => $tenant->domain,
                    'plan' => $tenant->subscription->plan_name ?? 'Free',
                    'status' => $tenant->status,
                    'users_count' => $tenant->users_count,
                    'created_at' => $tenant->created_at,
                    'subscription_ends_at' => $tenant->subscription->ends_at ?? null,
                ];
            });

        // Chart Data - Last 12 months
        $monthLabels = [];
        $revenueData = [];
        $companiesData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->format('M Y');
            
            // Revenue for month
            $monthRevenue = Payment::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'completed')
                ->sum('amount');
            $revenueData[] = $monthRevenue;
            
            // Companies registered in month
            $companiesInMonth = Tenant::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $companiesData[] = $companiesInMonth;
        }

        // Platform statistics
        $platformStats = [
            'total_assets' => DB::table('assets')->count(),
            'total_work_orders' => DB::table('work_orders')->count(),
            'pending_invoices' => Payment::where('status', 'pending')->count(),
            'active_sessions' => DB::table('sessions')->where('last_activity', '>=', now()->subMinutes(30))->count(),
        ];

        // Recent activities
        $recentActivities = $this->getRecentActivities();

        return view('super-admin.dashboard', compact(
            'totalCompanies',
            'companiesGrowthPercent',
            'activeSubscriptions',
            'trialingSubscriptions',
            'monthlyRevenue',
            'revenueGrowthPercent',
            'totalUsers',
            'recentCompanies',
            'monthLabels',
            'revenueData',
            'companiesData',
            'platformStats',
            'recentActivities'
        ));
    }

    private function getRecentActivities()
    {
        $activities = collect();
        
        // Get recent company registrations
        $activities = $activities->merge(
            Tenant::with('subscription')
                ->latest()
                ->take(5)
                ->get()
                ->map(function($tenant) {
                    return [
                        'type' => 'company_registered',
                        'title' => 'New Company Registered',
                        'description' => $tenant->name . ' registered on platform',
                        'time' => $tenant->created_at->diffForHumans(),
                        'icon' => 'building',
                        'color' => 'bg-blue-500',
                        'url' => route('admin.companies.show', $tenant->id)
                    ];
                })
        );
        
        // Get recent payments
        $activities = $activities->merge(
            Payment::with('tenant')
                ->where('status', 'completed')
                ->latest()
                ->take(3)
                ->get()
                ->map(function($payment) {
                    return [
                        'type' => 'payment_received',
                        'title' => 'Payment Received',
                        'description' => '$' . number_format($payment->amount) . ' from ' . $payment->tenant->name,
                        'time' => $payment->created_at->diffForHumans(),
                        'icon' => 'dollar-sign',
                        'color' => 'bg-green-500',
                        'url' => route('admin.payments.show', $payment->id)
                    ];
                })
        );

        return $activities->sortByDesc('time')->take(8);
    }

    public function analytics()
    {
        // Get analytics data for charts
        $dailyRegistrations = Tenant::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $revenueByPlan = Subscription::select(
                'plan_name',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(price) as revenue')
            )
            ->where('status', 'active')
            ->groupBy('plan_name')
            ->get();

        $userGrowth = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('super-admin.analytics', compact(
            'dailyRegistrations',
            'revenueByPlan',
            'userGrowth'
        ));
    }

    public function exportReport(Request $request)
    {
        $type = $request->type;
        $data = [];
        
        switch ($type) {
            case 'companies':
                $data = Tenant::with('subscription')->get();
                break;
            case 'payments':
                $data = Payment::with('tenant')->get();
                break;
            case 'users':
                $data = User::with('tenant')->get();
                break;
        }
        
        // Generate CSV or Excel (simplified)
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Report generated successfully'
        ]);
    }
}