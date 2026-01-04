<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = PaymentRequest::with(['vendor', 'project', 'requester']);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('pr_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('invoice_number', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('vendor', function($vendorQuery) use ($searchTerm) {
                      $vendorQuery->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('project', function($projectQuery) use ($searchTerm) {
                      $projectQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $paymentRequests = $query->orderBy('created_at', 'desc')->paginate(15);
        $vendors = Vendor::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('payment-requests.index', compact('paymentRequests', 'vendors', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vendors = Vendor::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::where('status', 'received')->orderBy('po_date', 'desc')->get();
        
        return view('payment-requests.create', compact('vendors', 'projects', 'purchaseOrders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'request_date' => 'required|date',
            'due_date' => 'required|date|after:request_date',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|in:advance,milestone,final,retention,other',
            'description' => 'required|string',
            'justification' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'invoice_date' => 'nullable|date|before_or_equal:today',
            'invoice_amount' => 'nullable|numeric|min:0',
        ]);

        // Generate PR Number
        $prNumber = 'PR-' . date('Y') . '-' . str_pad(PaymentRequest::count() + 1, 4, '0', STR_PAD_LEFT);

        PaymentRequest::create([
            'pr_number' => $prNumber,
            'vendor_id' => $validated['vendor_id'],
            'project_id' => $validated['project_id'],
            'purchase_order_id' => $validated['purchase_order_id'],
            'request_date' => $validated['request_date'],
            'due_date' => $validated['due_date'],
            'amount' => $validated['amount'],
            'payment_type' => $validated['payment_type'],
            'description' => $validated['description'],
            'justification' => $validated['justification'],
            'invoice_number' => $validated['invoice_number'],
            'invoice_date' => $validated['invoice_date'],
            'invoice_amount' => $validated['invoice_amount'],
            'requested_by' => auth()->id(),
        ]);

        return redirect()->route('payment-requests.index')
            ->with('success', 'Payment Request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentRequest $paymentRequest): View
    {
        $paymentRequest->load(['vendor', 'project', 'purchaseOrder', 'requester', 'approver']);
        return view('payment-requests.show', compact('paymentRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentRequest $paymentRequest): View
    {
        $vendors = Vendor::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::where('status', 'received')->orderBy('po_date', 'desc')->get();
        
        return view('payment-requests.edit', compact('paymentRequest', 'vendors', 'projects', 'purchaseOrders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentRequest $paymentRequest): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'request_date' => 'required|date',
            'due_date' => 'required|date|after:request_date',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|in:advance,milestone,final,retention,other',
            'status' => 'required|in:draft,submitted,approved,rejected,paid,cancelled',
            'description' => 'required|string',
            'justification' => 'nullable|string',
            'invoice_number' => 'nullable|string',
            'invoice_date' => 'nullable|date|before_or_equal:today',
            'invoice_amount' => 'nullable|numeric|min:0',
        ]);

        $paymentRequest->update($validated);

        return redirect()->route('payment-requests.index')
            ->with('success', 'Payment Request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentRequest $paymentRequest): RedirectResponse
    {
        $paymentRequest->delete();

        return redirect()->route('payment-requests.index')
            ->with('success', 'Payment Request deleted successfully.');
    }

    /**
     * Submit the payment request for approval
     */
    public function submit(PaymentRequest $paymentRequest): RedirectResponse
    {
        $paymentRequest->update(['status' => 'submitted']);

        return redirect()->route('payment-requests.show', $paymentRequest)
            ->with('success', 'Payment Request submitted for approval.');
    }

    /**
     * Approve the payment request
     */
    public function approve(Request $request, PaymentRequest $paymentRequest): RedirectResponse
    {
        $validated = $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        $paymentRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'],
        ]);

        return redirect()->route('payment-requests.show', $paymentRequest)
            ->with('success', 'Payment Request approved successfully.');
    }

    /**
     * Reject the payment request
     */
    public function reject(Request $request, PaymentRequest $paymentRequest): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $paymentRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('payment-requests.show', $paymentRequest)
            ->with('success', 'Payment Request rejected.');
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(PaymentRequest $paymentRequest): RedirectResponse
    {
        $paymentRequest->update(['status' => 'paid']);

        return redirect()->route('payment-requests.show', $paymentRequest)
            ->with('success', 'Payment Request marked as paid.');
    }

    /**
     * Dashboard view
     */
    public function dashboard(): View
    {
        $stats = [
            'total_requests' => PaymentRequest::count(),
            'pending_approval' => PaymentRequest::where('status', 'submitted')->count(),
            'overdue' => PaymentRequest::where('status', '!=', 'paid')->where('status', '!=', 'cancelled')->where('due_date', '<', now())->count(),
            'total_amount' => PaymentRequest::sum('total_amount'),
        ];

        $recentRequests = PaymentRequest::with(['vendor', 'project'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('payment-requests.dashboard', compact('stats', 'recentRequests'));
    }
}