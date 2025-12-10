<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('tenant');
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Date range filter
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $payments = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Payment::count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'total_amount' => Payment::where('status', 'completed')->sum('amount'),
        ];
        
        return view('super-admin.payments.index', compact('payments', 'stats'));
    }
    
    public function create()
    {
        $tenants = Tenant::where('status', 'active')->get();
        return view('super-admin.payments.create', compact('tenants'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:completed,pending,failed,refunded',
            'payment_method' => 'required|in:card,bank_transfer,cash,paypal',
            'due_date' => 'required|date',
            'paid_at' => 'nullable|date',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Payment::create([
            'tenant_id' => $request->tenant_id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'amount' => $request->amount,
            'currency' => 'USD',
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'transaction_id' => 'txn_' . uniqid(),
            'due_date' => $request->due_date,
            'paid_at' => $request->status == 'completed' ? ($request->paid_at ?? now()) : null,
            'metadata' => json_encode($request->metadata ?? []),
        ]);
        
        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment created successfully!');
    }
    
    public function show(Payment $payment)
    {
        $payment->load('tenant');
        return view('super-admin.payments.show', compact('payment'));
    }
    
    public function edit(Payment $payment)
    {
        $payment->load('tenant');
        $tenants = Tenant::where('status', 'active')->get();
        return view('super-admin.payments.edit', compact('payment', 'tenants'));
    }
    
    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:completed,pending,failed,refunded',
            'payment_method' => 'required|in:card,bank_transfer,cash,paypal',
            'due_date' => 'required|date',
            'paid_at' => 'nullable|date',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $payment->update([
            'amount' => $request->amount,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'due_date' => $request->due_date,
            'paid_at' => $request->status == 'completed' ? ($request->paid_at ?? now()) : null,
            'metadata' => json_encode($request->metadata ?? []),
        ]);
        
        return redirect()->route('admin.payments.show', $payment->id)
            ->with('success', 'Payment updated successfully!');
    }
    
    public function destroy(Payment $payment)
    {
        $payment->delete();
        
        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully!');
    }
    
    public function markAsPaid(Payment $payment)
    {
        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);
        
        return back()->with('success', 'Payment marked as completed!');
    }
    
    private function generateInvoiceNumber()
    {
        $lastInvoice = Payment::where('invoice_number', 'like', 'INV-%')
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, 4));
            $newNumber = 'INV-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNumber = 'INV-000001';
        }
        
        return $newNumber;
    }
}