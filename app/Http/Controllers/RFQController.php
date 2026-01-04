<?php

namespace App\Http\Controllers;

use App\Models\RFQ;
use App\Models\RFQItem;
use App\Models\Project;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RFQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = RFQ::with(['project', 'creator']);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('rfq_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('project', function($projectQuery) use ($searchTerm) {
                      $projectQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $rfqs = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::orderBy('name')->get();

        return view('rfqs.index', compact('rfqs', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $projects = Project::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('rfqs.create', compact('projects', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'rfq_date' => 'required|date',
            'quotation_due_date' => 'required|date|after:rfq_date',
            'valid_until' => 'nullable|date|after:quotation_due_date',
            'description' => 'required|string',
            'terms_conditions' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'estimated_budget' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.specifications' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit' => 'required|string',
            'items.*.estimated_price' => 'nullable|numeric|min:0',
            'items.*.remarks' => 'nullable|string',
        ]);

        // Generate RFQ Number
        $rfqNumber = 'RFQ-' . date('Y') . '-' . str_pad(RFQ::count() + 1, 4, '0', STR_PAD_LEFT);

        $rfq = RFQ::create([
            'rfq_number' => $rfqNumber,
            'project_id' => $validated['project_id'],
            'rfq_date' => $validated['rfq_date'],
            'quotation_due_date' => $validated['quotation_due_date'],
            'valid_until' => $validated['valid_until'],
            'description' => $validated['description'],
            'terms_conditions' => $validated['terms_conditions'],
            'delivery_terms' => $validated['delivery_terms'],
            'payment_terms' => $validated['payment_terms'],
            'estimated_budget' => $validated['estimated_budget'],
            'created_by' => auth()->id(),
        ]);

        // Create items
        foreach ($validated['items'] as $item) {
            RFQItem::create([
                'rfq_id' => $rfq->id,
                'item_name' => $item['item_name'],
                'description' => $item['description'],
                'specifications' => $item['specifications'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'estimated_price' => $item['estimated_price'],
                'remarks' => $item['remarks'],
            ]);
        }

        return redirect()->route('rfqs.index')
            ->with('success', 'RFQ created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RFQ $rfq): View
    {
        $rfq->load(['project', 'creator', 'approver', 'items']);
        return view('rfqs.show', compact('rfq'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RFQ $rfq): View
    {
        $projects = Project::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('rfqs.edit', compact('rfq', 'projects', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RFQ $rfq): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'rfq_date' => 'required|date',
            'quotation_due_date' => 'required|date|after:rfq_date',
            'valid_until' => 'nullable|date|after:quotation_due_date',
            'status' => 'required|in:draft,sent,received,evaluated,awarded,cancelled',
            'description' => 'required|string',
            'terms_conditions' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'estimated_budget' => 'nullable|numeric|min:0',
        ]);

        $rfq->update($validated);

        return redirect()->route('rfqs.index')
            ->with('success', 'RFQ updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RFQ $rfq): RedirectResponse
    {
        $rfq->delete();

        return redirect()->route('rfqs.index')
            ->with('success', 'RFQ deleted successfully.');
    }

    /**
     * Send the RFQ to vendors
     */
    public function send(RFQ $rfq): RedirectResponse
    {
        $rfq->update(['status' => 'sent']);

        return redirect()->route('rfqs.show', $rfq)
            ->with('success', 'RFQ sent to vendors successfully.');
    }

    /**
     * Approve the RFQ
     */
    public function approve(Request $request, RFQ $rfq): RedirectResponse
    {
        $validated = $request->validate([
            'approval_notes' => 'nullable|string',
        ]);

        $rfq->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'],
        ]);

        return redirect()->route('rfqs.show', $rfq)
            ->with('success', 'RFQ approved successfully.');
    }

    /**
     * Dashboard view
     */
    public function dashboard(): View
    {
        $stats = [
            'total_rfqs' => RFQ::count(),
            'pending_evaluation' => RFQ::where('status', 'received')->count(),
            'awarded_rfqs' => RFQ::where('status', 'awarded')->count(),
            'total_estimated_budget' => RFQ::sum('estimated_budget'),
        ];

        $recentRFQs = RFQ::with(['project', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('rfqs.dashboard', compact('stats', 'recentRFQs'));
    }
}