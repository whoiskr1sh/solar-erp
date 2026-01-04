<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use App\Models\Project;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PurchaseRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = PurchaseRequisition::with(['project', 'requester', 'approver']);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('pr_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('purpose', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('project', function($projectQuery) use ($searchTerm) {
                      $projectQuery->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('requester', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('requested_by')) {
            $query->where('requested_by', $request->requested_by);
        }

        $requisitions = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        // Calculate stats
        $stats = [
            'total' => PurchaseRequisition::count(),
            'pending' => PurchaseRequisition::where('status', 'submitted')->count(),
            'overdue' => PurchaseRequisition::overdue()->count(),
            'total_value' => PurchaseRequisition::sum('estimated_total'),
        ];

        return view('purchase-requisitions.index', compact('requisitions', 'projects', 'users', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $projects = Project::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('purchase-requisitions.create', compact('projects', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'requisition_date' => 'required|date',
            'required_date' => 'required|date|after:requisition_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'purpose' => 'required|string',
            'justification' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.specifications' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.estimated_unit_price' => 'nullable|numeric|min:0',
            'items.*.unit' => 'required|string',
            'items.*.remarks' => 'nullable|string',
        ]);

        // Generate PR Number
        $prNumber = 'PR-' . date('Y') . '-' . str_pad(PurchaseRequisition::count() + 1, 4, '0', STR_PAD_LEFT);

        // Calculate estimated total
        $estimatedTotal = 0;
        foreach ($validated['items'] as $item) {
            if ($item['estimated_unit_price']) {
                $estimatedTotal += $item['quantity'] * $item['estimated_unit_price'];
            }
        }

        $requisition = PurchaseRequisition::create([
            'pr_number' => $prNumber,
            'project_id' => $validated['project_id'],
            'requisition_date' => $validated['requisition_date'],
            'required_date' => $validated['required_date'],
            'priority' => $validated['priority'],
            'purpose' => $validated['purpose'],
            'justification' => $validated['justification'],
            'estimated_total' => $estimatedTotal,
            'requested_by' => auth()->id(),
        ]);

        // Create items
        foreach ($validated['items'] as $item) {
            PurchaseRequisitionItem::create([
                'purchase_requisition_id' => $requisition->id,
                'item_name' => $item['item_name'],
                'description' => $item['description'],
                'specifications' => $item['specifications'],
                'quantity' => $item['quantity'],
                'estimated_unit_price' => $item['estimated_unit_price'],
                'unit' => $item['unit'],
                'remarks' => $item['remarks'],
            ]);
        }

        return redirect()->route('purchase-requisitions.index')
            ->with('success', 'Purchase Requisition created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseRequisition $purchaseRequisition): View
    {
        $purchaseRequisition->load(['project', 'requester', 'approver', 'items']);
        return view('purchase-requisitions.show', compact('purchaseRequisition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseRequisition $purchaseRequisition): View
    {
        $projects = Project::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('purchase-requisitions.edit', compact('purchaseRequisition', 'projects', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseRequisition $purchaseRequisition): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'requisition_date' => 'required|date',
            'required_date' => 'required|date|after:requisition_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'purpose' => 'required|string',
            'justification' => 'nullable|string',
            'status' => 'required|in:draft,submitted,approved,rejected,converted_to_po,cancelled',
        ]);

        $purchaseRequisition->update($validated);

        return redirect()->route('purchase-requisitions.index')
            ->with('success', 'Purchase Requisition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseRequisition $purchaseRequisition): RedirectResponse
    {
        $purchaseRequisition->delete();

        return redirect()->route('purchase-requisitions.index')
            ->with('success', 'Purchase Requisition deleted successfully.');
    }

    /**
     * Submit the requisition for approval
     */
    public function submit(PurchaseRequisition $purchaseRequisition): RedirectResponse
    {
        $purchaseRequisition->update(['status' => 'submitted']);

        return redirect()->route('purchase-requisitions.show', $purchaseRequisition)
            ->with('success', 'Purchase Requisition submitted for approval.');
    }

    /**
     * Approve the requisition
     */
    public function approve(Request $request, PurchaseRequisition $purchaseRequisition): RedirectResponse
    {
        $validated = $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        $purchaseRequisition->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'],
        ]);

        return redirect()->route('purchase-requisitions.show', $purchaseRequisition)
            ->with('success', 'Purchase Requisition approved successfully.');
    }

    /**
     * Reject the requisition
     */
    public function reject(Request $request, PurchaseRequisition $purchaseRequisition): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $purchaseRequisition->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('purchase-requisitions.show', $purchaseRequisition)
            ->with('success', 'Purchase Requisition rejected.');
    }

    /**
     * Convert to Purchase Order
     */
    public function convertToPO(PurchaseRequisition $purchaseRequisition): RedirectResponse
    {
        $purchaseRequisition->update(['status' => 'converted_to_po']);

        return redirect()->route('purchase-orders.create', ['pr_id' => $purchaseRequisition->id])
            ->with('success', 'Purchase Requisition converted to PO. Please complete the PO details.');
    }

    /**
     * Dashboard view
     */
    public function dashboard(): View
    {
        $stats = [
            'total_prs' => PurchaseRequisition::count(),
            'pending_approval' => PurchaseRequisition::where('status', 'submitted')->count(),
            'overdue' => PurchaseRequisition::overdue()->count(),
            'total_value' => PurchaseRequisition::sum('estimated_total'),
        ];

        $recentRequisitions = PurchaseRequisition::with(['project', 'requester'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('purchase-requisitions.dashboard', compact('stats', 'recentRequisitions'));
    }
}