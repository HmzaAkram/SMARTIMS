<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Item;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        $recentReports = Report::latest()->take(10)->get();
        $scheduledReports = Report::where('is_scheduled', true)->get();
        $warehouses = \App\Models\Warehouse::all();
        $categories = \App\Models\Category::all();

        return view('reports.index', compact('recentReports', 'scheduledReports', 'warehouses', 'categories'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required',
            'date_range' => 'required',
            'format' => 'required|in:pdf,excel,csv',
        ]);

        $items = Item::all();

        $report = Report::create([
            'name' => $request->report_type . ' - ' . now()->format('Y-m-d'),
            'type' => $request->report_type,
            'date_range' => $request->date_range,
            'format' => $request->format,
            'file_path' => 'reports/dummy.pdf',
        ]);

        if ($request->format === 'pdf') {
            $pdf = PDF::loadView('reports.pdf', compact('items'));
            return $pdf->download('report.pdf');
        }

        return redirect()->back()->with('success', 'Report generated!');
    }
}