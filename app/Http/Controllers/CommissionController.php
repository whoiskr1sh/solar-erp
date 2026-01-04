<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\ChannelPartner;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add Spatie permissions middleware here if needed
        // $this->middleware('permission:view commissions')->only(['index', 'show']);
        // $this->middleware('permission:create commissions')->only(['create', 'store']);
        // $this->middleware('permission:edit commissions')->only(['edit', 'update']);
        // $this->middleware('permission:delete commissions')->only(['destroy']);
        // $this->middleware('permission:approve commissions')->only(['approve', 'cancel', 'dispute']);
    }

    public function index(Request $request)
    {
        $query = Commission::with(['channelPartner', 'project', 'invoice', 'quotation', 'approver', 'creator']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('commission_number', 'like', "%{$search}%")
                ->orWhere('reference_number', 'like', "%{$search}%")
                ->orWhereHas('channelPartner', function ($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status') && $request->input('payment_status') !== 'all') {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('channel_partner_id') && $request->input('channel_partner_id') !== 'all') {
            $query->where('channel_partner_id', $request->input('channel_partner_id'));
        }

        if ($request->filled('reference_type') && $request->input('reference_type') !== 'all') {
            $query->where('reference_type', $request->input('reference_type'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        if ($request->filled('overdue') && $request->input('overdue') === 'true') {
            $query->overdue();
        }

        $commissions = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculate stats
        $totalCommissions = Commission::count();
        $pendingCommissions = Commission::pending()->count();
        $approvedCommissions = Commission::approved()->count();
        $paidCommissions = Commission::paid()->count();
        $totalCommissionAmount = Commission::sum('commission_amount');
        $totalPaidAmount = Commission::sum('paid_amount');
        $totalPendingAmount = Commission::sum('pending_amount');
        $overdueCommissions = Commission::overdue()->count();

        $stats = [
            'total' => $totalCommissions,
            'pending' => $pendingCommissions,
            'approved' => $approvedCommissions,
            'paid' => $paidCommissions,
            'total_amount' => $totalCommissionAmount,
            'total_paid' => $totalPaidAmount,
            'total_pending' => $totalPendingAmount,
            'overdue' => $overdueCommissions,
        ];

        $channelPartners = ChannelPartner::active()->get();
        $users = User::all();

        return view('commissions.index', compact('commissions', 'stats', 'channelPartners', 'users'));
    }

    public function create()
    {
        $channelPartners = ChannelPartner::active()->get();
        $projects = Project::all();
        $invoices = Invoice::all();
        $quotations = Quotation::all();
        $users = User::all();
        
        return view('commissions.create', compact('channelPartners', 'projects', 'invoices', 'quotations', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'channel_partner_id' => 'required|exists:channel_partners,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'quotation_id' => 'nullable|exists:quotations,id',
            'reference_type' => ['required', Rule::in(['project', 'invoice', 'quotation', 'manual'])],
            'reference_number' => 'nullable|string|max:255',
            'base_amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'documents' => 'nullable|array',
        ]);

        $commission = Commission::create($validated);

        return redirect()->route('commissions.index')->with('success', 'Commission created successfully!');
    }

    public function show(Commission $commission)
    {
        $commission->load(['channelPartner', 'project', 'invoice', 'quotation', 'approver', 'creator']);
        return view('commissions.show', compact('commission'));
    }

    public function edit(Commission $commission)
    {
        $channelPartners = ChannelPartner::active()->get();
        $projects = Project::all();
        $invoices = Invoice::all();
        $quotations = Quotation::all();
        $users = User::all();
        
        return view('commissions.edit', compact('commission', 'channelPartners', 'projects', 'invoices', 'quotations', 'users'));
    }

    public function update(Request $request, Commission $commission)
    {
        $validated = $request->validate([
            'channel_partner_id' => 'required|exists:channel_partners,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'quotation_id' => 'nullable|exists:quotations,id',
            'reference_type' => ['required', Rule::in(['project', 'invoice', 'quotation', 'manual'])],
            'reference_number' => 'nullable|string|max:255',
            'base_amount' => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'documents' => 'nullable|array',
        ]);

        $commission->update($validated);

        return redirect()->route('commissions.index')->with('success', 'Commission updated successfully!');
    }

    public function destroy(Commission $commission)
    {
        $commission->delete();
        return redirect()->route('commissions.index')->with('success', 'Commission deleted successfully!');
    }

    public function approve(Commission $commission)
    {
        if ($commission->status === 'pending') {
            $commission->approve(Auth::user());
            return back()->with('success', 'Commission approved successfully!');
        }
        return back()->with('error', 'Commission cannot be approved in its current status.');
    }

    public function cancel(Commission $commission)
    {
        if (in_array($commission->status, ['pending', 'approved'])) {
            $commission->cancel(Auth::user());
            return back()->with('success', 'Commission cancelled successfully!');
        }
        return back()->with('error', 'Commission cannot be cancelled in its current status.');
    }

    public function dispute(Commission $commission)
    {
        if (in_array($commission->status, ['pending', 'approved'])) {
            $commission->dispute(Auth::user());
            return back()->with('success', 'Commission marked as disputed successfully!');
        }
        return back()->with('error', 'Commission cannot be disputed in its current status.');
    }

    public function addPayment(Request $request, Commission $commission)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $paymentDetails = [
            'method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'notes' => $validated['notes'],
            'paid_at' => now()->toISOString(),
            'paid_by' => Auth::user()->name,
        ];

        $commission->addPayment($validated['amount'], $paymentDetails);

        return back()->with('success', 'Payment added successfully!');
    }

    public function markAsPaid(Request $request, Commission $commission)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $paymentDetails = [
            'method' => $validated['payment_method'],
            'transaction_id' => $validated['transaction_id'],
            'notes' => $validated['notes'],
            'paid_at' => now()->toISOString(),
            'paid_by' => Auth::user()->name,
        ];

        // Debug log
        \Log::info('Marking commission as paid', [
            'commission_id' => $commission->id,
            'current_status' => $commission->status,
            'current_payment_status' => $commission->payment_status,
            'payment_details' => $paymentDetails
        ]);

        $commission->markAsPaid($paymentDetails);

        // Debug log after update
        \Log::info('Commission marked as paid', [
            'commission_id' => $commission->id,
            'new_status' => $commission->fresh()->status,
            'new_payment_status' => $commission->fresh()->payment_status,
        ]);

        return back()->with('success', 'Commission marked as paid successfully!');
    }

    public function export(Request $request)
    {
        $query = Commission::with(['channelPartner', 'project', 'invoice', 'quotation', 'approver', 'creator']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('commission_number', 'like', "%{$search}%")
                ->orWhere('reference_number', 'like', "%{$search}%")
                ->orWhereHas('channelPartner', function ($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status') && $request->input('payment_status') !== 'all') {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('channel_partner_id') && $request->input('channel_partner_id') !== 'all') {
            $query->where('channel_partner_id', $request->input('channel_partner_id'));
        }

        if ($request->filled('reference_type') && $request->input('reference_type') !== 'all') {
            $query->where('reference_type', $request->input('reference_type'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        if ($request->filled('overdue') && $request->input('overdue') === 'true') {
            $query->overdue();
        }

        $commissions = $query->orderBy('created_at', 'desc')->get();

        $filename = 'commissions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($commissions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Commission Number', 'Channel Partner', 'Reference Type', 'Reference Number',
                'Base Amount', 'Commission Rate', 'Commission Amount', 'Paid Amount', 'Pending Amount',
                'Status', 'Payment Status', 'Due Date', 'Created At'
            ]);

            foreach ($commissions as $commission) {
                fputcsv($file, [
                    $commission->id,
                    $commission->commission_number,
                    $commission->channelPartner->company_name,
                    ucfirst($commission->reference_type),
                    $commission->reference_number,
                    '₹' . number_format($commission->base_amount, 2),
                    $commission->commission_rate . '%',
                    '₹' . number_format($commission->commission_amount, 2),
                    '₹' . number_format($commission->paid_amount, 2),
                    '₹' . number_format($commission->pending_amount, 2),
                    ucfirst($commission->status),
                    ucfirst($commission->payment_status),
                    $commission->due_date ? $commission->due_date->format('Y-m-d') : 'N/A',
                    $commission->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}