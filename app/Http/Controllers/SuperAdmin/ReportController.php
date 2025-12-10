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

class ReportController extends Controller
{
    public function index()
    {
        return view('super-admin.reports.index');
    }
    
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:revenue,users,companies,subscriptions',
            'date_range' => 'required|in:today,yesterday,this_week,this_month,last_month,custom',
            'start_date' => 'required_if:date_range,custom|date',
            'end_date' => 'required_if:date_range,custom|date|after_or_equal:start_date',
            'format' => 'required|in:pdf,csv,excel',
        ]);
        
        $dateRange = $this->getDateRange($request);
        
        switch ($request->report_type) {
            case 'revenue':
                $data = $this->generateRevenueReport($dateRange);
                $view = 'super-admin.reports.revenue';
                $filename = 'revenue_report';
                break;
                
            case 'users':
                $data = $this->generateUserReport($dateRange);
                $view = 'super-admin.reports.users';
                $filename = 'user_report';
                break;
                
            case 'companies':
                $data = $this->generateCompanyReport($dateRange);
                $view = 'super-admin.reports.companies';
                $filename = 'company_report';
                break;
                
            case 'subscriptions':
                $data = $this->generateSubscriptionReport($dateRange);
                $view = 'super-admin.reports.subscriptions';
                $filename = 'subscription_report';
                break;
        }
        
        if ($request->format == 'csv') {
            return $this->exportCSV($data, $filename);
        } elseif ($request->format == 'excel') {
            return $this->exportExcel($data, $filename);
        }
        
        return view($view, compact('data', 'dateRange'));
    }
    
    private function getDateRange(Request $request)
    {
        switch ($request->date_range) {
            case 'today':
                return [
                    'start' => Carbon::today(),
                    'end' => Carbon::today()->endOfDay(),
                    'label' => 'Today',
                ];
                
            case 'yesterday':
                return [
                    'start' => Carbon::yesterday(),
                    'end' => Carbon::yesterday()->endOfDay(),
                    'label' => 'Yesterday',
                ];
                
            case 'this_week':
                return [
                    'start' => Carbon::now()->startOfWeek(),
                    'end' => Carbon::now()->endOfWeek(),
                    'label' => 'This Week',
                ];
                
            case 'this_month':
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth(),
                    'label' => 'This Month',
                ];
                
            case 'last_month':
                return [
                    'start' => Carbon::now()->subMonth()->startOfMonth(),
                    'end' => Carbon::now()->subMonth()->endOfMonth(),
                    'label' => 'Last Month',
                ];
                
            case 'custom':
                return [
                    'start' => Carbon::parse($request->start_date)->startOfDay(),
                    'end' => Carbon::parse($request->end_date)->endOfDay(),
                    'label' => 'Custom Range',
                ];
        }
    }
    
    private function generateRevenueReport($dateRange)
    {
        $payments = Payment::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->with('tenant')
            ->get();
        
        $summary = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->where('status', 'completed')->sum('amount'),
            'pending_amount' => $payments->where('status', 'pending')->sum('amount'),
            'failed_amount' => $payments->where('status', 'failed')->sum('amount'),
            'by_status' => $payments->groupBy('status')->map->count(),
            'by_method' => $payments->groupBy('payment_method')->map->count(),
        ];
        
        $daily = Payment::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as revenue')
            )
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'payments' => $payments,
            'summary' => $summary,
            'daily' => $daily,
        ];
    }
    
    private function generateUserReport($dateRange)
    {
        $users = User::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->with('tenant')
            ->get();
        
        $summary = [
            'total_users' => $users->count(),
            'active_users' => $users->where('status', 'active')->count(),
            'inactive_users' => $users->where('status', 'inactive')->count(),
            'by_role' => $users->groupBy('role')->map->count(),
            'by_company' => $users->groupBy('tenant_id')->map->count(),
        ];
        
        $daily = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'users' => $users,
            'summary' => $summary,
            'daily' => $daily,
        ];
    }
    
    private function generateCompanyReport($dateRange)
    {
        $companies = Tenant::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->with(['subscription', 'users'])
            ->get();
        
        $summary = [
            'total_companies' => $companies->count(),
            'active_companies' => $companies->where('status', 'active')->count(),
            'trialing_companies' => $companies->where('status', 'trialing')->count(),
            'suspended_companies' => $companies->where('status', 'suspended')->count(),
            'by_plan' => $companies->groupBy('subscription.plan_name')->map->count(),
            'total_users' => $companies->sum(function($company) {
                return $company->users->count();
            }),
        ];
        
        $daily = Tenant::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'companies' => $companies,
            'summary' => $summary,
            'daily' => $daily,
        ];
    }
    
    private function generateSubscriptionReport($dateRange)
    {
        $subscriptions = Subscription::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->with('tenant')
            ->get();
        
        $summary = [
            'total_subscriptions' => $subscriptions->count(),
            'active_subscriptions' => $subscriptions->where('status', 'active')->count(),
            'trialing_subscriptions' => $subscriptions->where('status', 'trialing')->count(),
            'cancelled_subscriptions' => $subscriptions->where('status', 'cancelled')->count(),
            'by_plan' => $subscriptions->groupBy('plan_name')->map->count(),
            'total_revenue' => $subscriptions->where('status', 'active')->sum('price'),
        ];
        
        $daily = Subscription::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return [
            'subscriptions' => $subscriptions,
            'summary' => $summary,
            'daily' => $daily,
        ];
    }
    
    private function exportCSV($data, $filename)
    {
        $csv = "Report Data\n\n";
        
        if (isset($data['payments'])) {
            $csv .= "Payments Report\n";
            $csv .= "ID,Tenant,Amount,Status,Payment Method,Date\n";
            
            foreach ($data['payments'] as $payment) {
                $csv .= implode(',', [
                    $payment->id,
                    $payment->tenant->name ?? 'N/A',
                    $payment->amount,
                    $payment->status,
                    $payment->payment_method,
                    $payment->created_at->format('Y-m-d'),
                ]) . "\n";
            }
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"');
    }
    
    private function exportExcel($data, $filename)
    {
        // Simple CSV export for now (install maatwebsite/excel for actual Excel)
        return $this->exportCSV($data, $filename);
    }
}