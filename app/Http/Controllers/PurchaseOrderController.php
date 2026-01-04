<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = PurchaseOrder::with(['vendor', 'project', 'creator']);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('po_number', 'like', '%' . $searchTerm . '%')
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

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $purchaseOrders = $query->orderBy('created_at', 'desc')->paginate(15);
        $vendors = Vendor::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('purchase-orders.index', compact('purchaseOrders', 'vendors', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $vendors = Vendor::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('purchase-orders.create', compact('vendors', 'projects', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'po_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after:po_date',
            'payment_terms' => 'required|in:net_30,net_45,net_60,advance,on_delivery',
            'delivery_address' => 'required|string',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string',
        ]);

        // Generate PO Number
        $poNumber = 'PO-' . date('Y') . '-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);

        // Calculate totals
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $totalAmount += $item['quantity'] * $item['unit_price'];
        }

        $taxAmount = $totalAmount * 0.18; // 18% GST
        $finalAmount = $totalAmount + $taxAmount;

        $purchaseOrder = PurchaseOrder::create([
            'po_number' => $poNumber,
            'vendor_id' => $validated['vendor_id'],
            'project_id' => $validated['project_id'],
            'po_date' => $validated['po_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
            'final_amount' => $finalAmount,
            'payment_terms' => $validated['payment_terms'],
            'delivery_address' => $validated['delivery_address'],
            'terms_conditions' => $validated['terms_conditions'],
            'notes' => $validated['notes'],
            'created_by' => auth()->id(),
        ]);

        // Create items
        foreach ($validated['items'] as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'item_name' => $item['item_name'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'unit' => $item['unit'],
                'pending_quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['vendor', 'project', 'creator', 'approver', 'items']);
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $vendors = Vendor::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        
        return view('purchase-orders.edit', compact('purchaseOrder', 'vendors', 'projects', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'po_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after:po_date',
            'payment_terms' => 'required|in:net_30,net_45,net_60,advance,on_delivery',
            'delivery_address' => 'required|string',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,acknowledged,partially_received,received,cancelled',
        ]);

        $purchaseOrder->update($validated);

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->delete();

        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order deleted successfully.');
    }

    /**
     * Approve the purchase order
     */
    public function approve(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $purchaseOrder->update([
            'status' => 'sent',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase Order approved and sent successfully.');
    }

    /**
     * Dashboard view
     */
    public function dashboard(): View
    {
        $stats = [
            'total_pos' => PurchaseOrder::count(),
            'pending_approval' => PurchaseOrder::where('status', 'draft')->count(),
            'overdue' => PurchaseOrder::overdue()->count(),
            'total_value' => PurchaseOrder::sum('final_amount'),
        ];

        $recentOrders = PurchaseOrder::with(['vendor', 'project'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('purchase-orders.dashboard', compact('stats', 'recentOrders'));
    }
}