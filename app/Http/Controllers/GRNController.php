<?php

namespace App\Http\Controllers;

use App\Models\GRN;
use App\Models\Vendor;
use App\Models\PurchaseOrder;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GRNExport;

class GRNController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = GRN::with(['vendor', 'purchaseOrder', 'project', 'receivedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('grn_number', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('vendor', function($vendorQuery) use ($searchTerm) {
                      $vendorQuery->where('company', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('purchaseOrder', function($poQuery) use ($searchTerm) {
                      $poQuery->where('po_number', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('grn_date', '>=', $request->date_from);
        }

        $grns = $query->orderBy('created_at', 'desc')->paginate(15);
        $vendors = Vendor::orderBy('company')->get();
        $projects = Project::orderBy('name')->get();

        return view('inventory.inward-grn', compact('grns', 'vendors', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vendors = Vendor::orderBy('company')->get();
        $purchaseOrders = PurchaseOrder::where('status', 'sent')->orderBy('po_number')->get();
        $projects = Project::orderBy('name')->get();
        
        return view('grns.create', compact('vendors', 'purchaseOrders', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'project_id' => 'nullable|exists:projects,id',
            'grn_date' => 'required|date',
            'received_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'final_amount' => 'required|numeric|min:0',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Generate GRN Number
        $grnNumber = 'GRN-' . date('Y') . '-' . str_pad(GRN::count() + 1, 4, '0', STR_PAD_LEFT);

        GRN::create([
            'grn_number' => $grnNumber,
            'vendor_id' => $validated['vendor_id'],
            'purchase_order_id' => $validated['purchase_order_id'],
            'project_id' => $validated['project_id'],
            'grn_date' => $validated['grn_date'],
            'received_date' => $validated['received_date'],
            'total_amount' => $validated['total_amount'],
            'tax_amount' => $validated['tax_amount'] ?? 0,
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'final_amount' => $validated['final_amount'],
            'delivery_address' => $validated['delivery_address'],
            'notes' => $validated['notes'],
            'received_by' => auth()->id(),
        ]);

        return redirect()->route('grns.index')
            ->with('success', 'GRN created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GRN $grn): View
    {
        $grn->load(['vendor', 'purchaseOrder', 'project', 'receivedBy', 'verifiedBy']);
        return view('grns.show', compact('grn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GRN $grn): View
    {
        $vendors = Vendor::orderBy('company')->get();
        $purchaseOrders = PurchaseOrder::where('status', 'sent')->orderBy('po_number')->get();
        $projects = Project::orderBy('name')->get();
        
        return view('grns.edit', compact('grn', 'vendors', 'purchaseOrders', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GRN $grn): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'project_id' => 'nullable|exists:projects,id',
            'grn_date' => 'required|date',
            'received_date' => 'required|date',
            'status' => 'required|in:pending,received,verified,rejected',
            'total_amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'final_amount' => 'required|numeric|min:0',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string',
            'rejection_reason' => 'nullable|string',
        ]);

        $grn->update($validated);

        return redirect()->route('grns.index')
            ->with('success', 'GRN updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GRN $grn): RedirectResponse
    {
        $grn->delete();

        return redirect()->route('grns.index')
            ->with('success', 'GRN deleted successfully.');
    }

    /**
     * Verify the GRN
     */
    public function verify(Request $request, GRN $grn): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $grn->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'notes' => $validated['notes'] ?? $grn->notes,
        ]);

        return redirect()->route('grns.show', $grn)
            ->with('success', 'GRN verified successfully.');
    }

    /**
     * Export GRNs to Excel
     */
    public function export(Request $request)
    {
        $query = GRN::with(['vendor', 'purchaseOrder', 'project', 'receivedBy']);

        // Apply same filters as index method
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('grn_number', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('vendor', function($vendorQuery) use ($searchTerm) {
                      $vendorQuery->where('company', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('purchaseOrder', function($poQuery) use ($searchTerm) {
                      $poQuery->where('po_number', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('grn_date', '>=', $request->date_from);
        }

        $grns = $query->orderBy('created_at', 'desc')->get();

        return \Maatwebsite\Excel\Facades\Excel::download(new GRNExport($grns), 'grns-' . now()->format('Y-m-d') . '.xlsx');
    }
}
