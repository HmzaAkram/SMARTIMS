<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Date range
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Revenue analytics
        $revenueData = $this->getRevenueData($startDate, $endDate);
        $revenueByPlan = $this->getRevenueByPlan();
        
        // User analytics
        $userGrowth = $this->getUserGrowth($startDate, $endDate);
        $userDistribution = $this->getUserDistribution();
        
        // Company analytics
        $companyGrowth = $this->getCompanyGrowth($startDate, $endDate);
        $companyDistribution = $this->getCompanyDistribution();
        
        // Top metrics
        $metrics = $this->getMetrics();
        
        return view('super-admin.analytics.index', compact(
            'revenueData',
            'revenueByPlan',
            'userGrowth',
            'userDistribution',
            'companyGrowth',
            'companyDistribution',
            'metrics',
            'startDate',
            'endDate'
        ));
    }
    
    private function getRevenueData($startDate, $endDate)
    {
        return Payment::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'total' => (float) $item->total,
                ];
            });
    }
    
    private function getRevenueByPlan()
    {
        return Subscription::select(
                'plan_name',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(price) as revenue')
            )
            ->where('status', 'active')
            ->groupBy('plan_name')
            ->get()
            ->map(function($item) {
                return [
                    'plan' => $item->plan_name,
                    'count' => (int) $item->count,
                    'revenue' => (float) $item->revenue,
                ];
            });
    }
    
    private function getUserGrowth($startDate, $endDate)
    {
        return User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    
    private function getUserDistribution()
    {
        return [
            'admins' => User::where('role', 'admin')->count(),
            'managers' => User::where('role', 'manager')->count(),
            'users' => User::where('role', 'user')->count(),
        ];
    }
    
    private function getCompanyGrowth($startDate, $endDate)
    {
        return Tenant::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    
    private function getCompanyDistribution()
    {
        return [
            'active' => Tenant::where('status', 'active')->count(),
            'trialing' => Tenant::where('status', 'trialing')->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
        ];
    }
    
    private function getMetrics()
    {
        return [
            'mrr' => Subscription::where('status', 'active')->sum('price'),
            'arr' => Subscription::where('status', 'active')->sum('price') * 12,
            'churn_rate' => $this->calculateChurnRate(),
            'ltv' => $this->calculateLTV(),
            'arpu' => $this->calculateARPU(),
        ];
    }
    
    private function calculateChurnRate()
    {
        $startMonth = Carbon::now()->subMonth()->startOfMonth();
        $endMonth = Carbon::now()->subMonth()->endOfMonth();
        
        $startSubscriptions = Subscription::where('created_at', '<', $startMonth)
            ->where('status', 'active')
            ->count();
            
        $churned = Subscription::whereBetween('ends_at', [$startMonth, $endMonth])
            ->where('status', 'cancelled')
            ->count();
            
        return $startSubscriptions > 0 ? ($churned / $startSubscriptions * 100) : 0;
    }
    
    private function calculateLTV()
    {
        $avgSubscriptionValue = Subscription::where('status', 'active')->avg('price');
        $avgSubscriptionLength = 12; // months (simplified)
        
        return $avgSubscriptionValue * $avgSubscriptionLength;
    }
    
    private function calculateARPU()
    {
        $totalRevenue = Subscription::where('status', 'active')->sum('price');
        $totalCompanies = Tenant::where('status', 'active')->count();
        
        return $totalCompanies > 0 ? ($totalRevenue / $totalCompanies) : 0;
    }
    
    public function export(Request $request)
    {
        $type = $request->type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        switch ($type) {
            case 'revenue':
                $data = $this->getRevenueData($startDate, $endDate);
                $filename = 'revenue_report.csv';
                $headers = ['Date', 'Revenue'];
                break;
                
            case 'users':
                $data = $this->getUserGrowth($startDate, $endDate);
                $filename = 'user_growth_report.csv';
                $headers = ['Date', 'New Users'];
                break;
                
            case 'companies':
                $data = $this->getCompanyGrowth($startDate, $endDate);
                $filename = 'company_growth_report.csv';
                $headers = ['Date', 'New Companies'];
                break;
                
            default:
                abort(404);
        }
        
        $csv = implode(',', $headers) . "\n";
        
        foreach ($data as $item) {
            $csv .= implode(',', array_values((array) $item)) . "\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}