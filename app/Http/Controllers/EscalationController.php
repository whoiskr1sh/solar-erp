<?php

namespace App\Http\Controllers;

use App\Models\Escalation;
use App\Models\User;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EscalationController extends Controller
{
    public function index(Request $request)
    {
        $query = Escalation::with(['assignedTo', 'escalatedTo', 'creator', 'relatedLead', 'relatedProject', 'relatedInvoice', 'relatedQuotation', 'relatedCommission']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('escalation_number', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority') && $request->input('priority') !== 'all') {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('assigned_to') && $request->input('assigned_to') !== 'all') {
            $query->where('assigned_to', $request->input('assigned_to'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        if ($request->filled('overdue') && $request->input('overdue') === 'true') {
            $query->overdue();
        }

        if ($request->filled('urgent') && $request->input('urgent') === 'true') {
            $query->urgent();
        }

        $escalations = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get stats
        $stats = [
            'total' => Escalation::count(),
            'open' => Escalation::open()->count(),
            'in_progress' => Escalation::inProgress()->count(),
            'resolved' => Escalation::resolved()->count(),
            'closed' => Escalation::closed()->count(),
            'overdue' => Escalation::overdue()->count(),
            'urgent' => Escalation::urgent()->count(),
            'high_priority' => Escalation::highPriority()->count(),
        ];

        // Get filter options
        $users = User::select('id', 'name')->get();
        $leads = Lead::select('id', 'company')->get();
        $projects = Project::select('id', 'name')->get();
        $invoices = Invoice::select('id', 'invoice_number')->get();
        $quotations = Quotation::select('id', 'quotation_number')->get();
        $commissions = Commission::select('id', 'commission_number')->get();

        return view('escalations.index', compact('escalations', 'stats', 'users', 'leads', 'projects', 'invoices', 'quotations', 'commissions'));
    }

    public function create()
    {
        $users = User::select('id', 'name')->get();
        $leads = Lead::select('id', 'company')->get();
        $projects = Project::select('id', 'name')->get();
        $invoices = Invoice::select('id', 'invoice_number')->get();
        $quotations = Quotation::select('id', 'quotation_number')->get();
        $commissions = Commission::select('id', 'commission_number')->get();

        return view('escalations.create', compact('users', 'leads', 'projects', 'invoices', 'quotations', 'commissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:complaint,issue,request,incident,other',
            'priority' => 'required|in:low,medium,high,critical',
            'category' => 'required|in:technical,billing,service,support,general',
            'assigned_to' => 'nullable|exists:users,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'due_date' => 'nullable|date|after:now',
            'related_lead_id' => 'nullable|exists:leads,id',
            'related_project_id' => 'nullable|exists:projects,id',
            'related_invoice_id' => 'nullable|exists:invoices,id',
            'related_quotation_id' => 'nullable|exists:quotations,id',
            'related_commission_id' => 'nullable|exists:commissions,id',
            'internal_notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_urgent' => 'boolean',
            'requires_response' => 'boolean',
        ]);

        $escalation = Escalation::create([
            'escalation_number' => Escalation::generateEscalationNumber(),
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'priority' => $request->priority,
            'category' => $request->category,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'due_date' => $request->due_date,
            'related_lead_id' => $request->related_lead_id,
            'related_project_id' => $request->related_project_id,
            'related_invoice_id' => $request->related_invoice_id,
            'related_quotation_id' => $request->related_quotation_id,
            'related_commission_id' => $request->related_commission_id,
            'internal_notes' => $request->internal_notes,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'is_urgent' => $request->boolean('is_urgent'),
            'requires_response' => $request->boolean('requires_response', true),
        ]);

        return redirect()->route('escalations.show', $escalation)->with('success', 'Escalation created successfully!');
    }

    public function show(Escalation $escalation)
    {
        $escalation->load(['assignedTo', 'escalatedTo', 'creator', 'relatedLead', 'relatedProject', 'relatedInvoice', 'relatedQuotation', 'relatedCommission']);
        
        return view('escalations.show', compact('escalation'));
    }

    public function edit(Escalation $escalation)
    {
        $users = User::select('id', 'name')->get();
        $leads = Lead::select('id', 'company')->get();
        $projects = Project::select('id', 'name')->get();
        $invoices = Invoice::select('id', 'invoice_number')->get();
        $quotations = Quotation::select('id', 'quotation_number')->get();
        $commissions = Commission::select('id', 'commission_number')->get();

        return view('escalations.edit', compact('escalation', 'users', 'leads', 'projects', 'invoices', 'quotations', 'commissions'));
    }

    public function update(Request $request, Escalation $escalation)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:complaint,issue,request,incident,other',
            'priority' => 'required|in:low,medium,high,critical',
            'category' => 'required|in:technical,billing,service,support,general',
            'status' => 'required|in:open,in_progress,resolved,closed,cancelled',
            'assigned_to' => 'nullable|exists:users,id',
            'escalated_to' => 'nullable|exists:users,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'due_date' => 'nullable|date',
            'related_lead_id' => 'nullable|exists:leads,id',
            'related_project_id' => 'nullable|exists:projects,id',
            'related_invoice_id' => 'nullable|exists:invoices,id',
            'related_quotation_id' => 'nullable|exists:quotations,id',
            'related_commission_id' => 'nullable|exists:commissions,id',
            'resolution_notes' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_urgent' => 'boolean',
            'requires_response' => 'boolean',
        ]);

        $updateData = $request->except(['tags']);
        $updateData['tags'] = $request->tags ? explode(',', $request->tags) : null;
        $updateData['is_urgent'] = $request->boolean('is_urgent');
        $updateData['requires_response'] = $request->boolean('requires_response');

        // Handle status changes
        if ($request->status === 'resolved' && $escalation->status !== 'resolved') {
            $updateData['resolved_at'] = now();
        }

        if ($request->status === 'closed' && $escalation->status !== 'closed') {
            $updateData['closed_at'] = now();
        }

        $escalation->update($updateData);

        return redirect()->route('escalations.show', $escalation)->with('success', 'Escalation updated successfully!');
    }

    public function destroy(Escalation $escalation)
    {
        $escalation->delete();
        return redirect()->route('escalations.index')->with('success', 'Escalation deleted successfully!');
    }

    // Status management methods
    public function markInProgress(Escalation $escalation)
    {
        $escalation->markAsInProgress();
        return back()->with('success', 'Escalation marked as in progress!');
    }

    public function markResolved(Request $request, Escalation $escalation)
    {
        $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $escalation->markAsResolved($request->resolution_notes);
        return back()->with('success', 'Escalation marked as resolved!');
    }

    public function markClosed(Escalation $escalation)
    {
        $escalation->markAsClosed();
        return back()->with('success', 'Escalation marked as closed!');
    }

    public function escalate(Request $request, Escalation $escalation)
    {
        $request->validate([
            'escalated_to' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->escalated_to);
        $escalation->escalateTo($user);
        
        return back()->with('success', 'Escalation escalated successfully!');
    }

    public function assign(Request $request, Escalation $escalation)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->assigned_to);
        $escalation->assignTo($user);
        
        return back()->with('success', 'Escalation assigned successfully!');
    }

    public function export(Request $request)
    {
        $query = Escalation::with(['assignedTo', 'escalatedTo', 'creator']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('escalation_number', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority') && $request->input('priority') !== 'all') {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('type') && $request->input('type') !== 'all') {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('assigned_to') && $request->input('assigned_to') !== 'all') {
            $query->where('assigned_to', $request->input('assigned_to'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        if ($request->filled('overdue') && $request->input('overdue') === 'true') {
            $query->overdue();
        }

        if ($request->filled('urgent') && $request->input('urgent') === 'true') {
            $query->urgent();
        }

        $escalations = $query->orderBy('created_at', 'desc')->get();

        $filename = 'escalations_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = function() use ($escalations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Escalation Number', 'Title', 'Type', 'Priority', 'Status', 'Category',
                'Customer Name', 'Customer Email', 'Assigned To', 'Escalated To', 'Due Date', 'Created At'
            ]);

            foreach ($escalations as $escalation) {
                fputcsv($file, [
                    $escalation->id,
                    $escalation->escalation_number,
                    $escalation->title,
                    ucfirst($escalation->type),
                    ucfirst($escalation->priority),
                    ucfirst($escalation->status),
                    ucfirst($escalation->category),
                    $escalation->customer_name ?? 'N/A',
                    $escalation->customer_email ?? 'N/A',
                    $escalation->assignedTo->name ?? 'N/A',
                    $escalation->escalatedTo->name ?? 'N/A',
                    $escalation->due_date ? $escalation->due_date->format('Y-m-d H:i') : 'Not set',
                    $escalation->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}