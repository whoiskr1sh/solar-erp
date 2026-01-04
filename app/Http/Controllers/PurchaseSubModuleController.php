<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\RFQ;
use App\Models\RFQItem;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use App\Models\VendorRegistration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PurchaseSubModuleController extends Controller
{
    /**
     * Purchase Manager Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_purchase_orders' => PurchaseOrder::count(),
            'pending_purchase_orders' => PurchaseOrder::where('status', 'pending')->count(),
            'approved_purchase_orders' => PurchaseOrder::where('status', 'approved')->count(),
            'total_vendors' => Vendor::count(),
            'active_vendors' => Vendor::where('status', 'active')->count(),
            'total_products' => Product::count(),
            'total_rfqs' => RFQ::count(),
            'pending_rfqs' => RFQ::where('status', 'pending')->count(),
            'total_requisitions' => PurchaseRequisition::count(),
            'pending_requisitions' => PurchaseRequisition::where('status', 'pending')->count(),
            'total_vendor_registrations' => VendorRegistration::count(),
            'pending_vendor_registrations' => VendorRegistration::where('status', 'pending')->count(),
        ];

        $recentPurchaseOrders = PurchaseOrder::with('vendor')->latest()->take(5)->get();
        $recentVendors = Vendor::latest()->take(5)->get();
        $recentRFQs = RFQ::latest()->take(5)->get();

        return view('purchase-manager.dashboard', compact('stats', 'recentPurchaseOrders', 'recentVendors', 'recentRFQs'));
    }

    // ==================== PURCHASE ORDERS ====================

    public function purchaseOrders()
    {
        $purchaseOrders = PurchaseOrder::with(['vendor', 'items.product'])->latest()->paginate(15);
        return view('purchase-manager.purchase-orders.index', compact('purchaseOrders'));
    }

    public function createPurchaseOrder()
    {
        $vendors = Vendor::where('status', 'active')->get();
        $products = Product::where('is_active', true)->get();
        return view('purchase-manager.purchase-orders.create', compact('vendors', 'products'));
    }

    public function storePurchaseOrder(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after:order_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'vendor_id' => $request->vendor_id,
            'order_date' => $request->order_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'status' => 'pending',
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        foreach ($request->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('purchase-manager.purchase-orders.index')->with('success', 'Purchase Order created successfully.');
    }

    public function showPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['vendor', 'items.product', 'createdBy']);
        return view('purchase-manager.purchase-orders.show', compact('purchaseOrder'));
    }

    public function editPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $vendors = Vendor::where('status', 'active')->get();
        $products = Product::where('is_active', true)->get();
        $purchaseOrder->load('items');
        return view('purchase-manager.purchase-orders.edit', compact('purchaseOrder', 'vendors', 'products'));
    }

    public function updatePurchaseOrder(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after:order_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $purchaseOrder->update([
            'vendor_id' => $request->vendor_id,
            'order_date' => $request->order_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'notes' => $request->notes,
        ]);

        // Delete existing items
        $purchaseOrder->items()->delete();

        // Create new items
        foreach ($request->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('purchase-manager.purchase-orders.index')->with('success', 'Purchase Order updated successfully.');
    }

    public function deletePurchaseOrder(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($request->has('approve_delete')) {
            $purchaseOrder->delete();
            return redirect()->route('purchase-manager.purchase-orders.index')->with('success', 'Purchase Order deleted successfully.');
        }

        return view('purchase-manager.purchase-orders.delete', compact('purchaseOrder'));
    }

    // ==================== VENDORS ====================

    public function vendors()
    {
        $vendors = Vendor::latest()->paginate(15);
        return view('purchase-manager.vendors.index', compact('vendors'));
    }

    public function createVendor()
    {
        return view('purchase-manager.vendors.create');
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
        ]);

        Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'contact_person' => $request->contact_person,
            'gst_number' => $request->gst_number,
            'pan_number' => $request->pan_number,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('purchase-manager.vendors.index')->with('success', 'Vendor created successfully.');
    }

    public function showVendor(Vendor $vendor)
    {
        $vendor->load('createdBy');
        return view('purchase-manager.vendors.show', compact('vendor'));
    }

    public function editVendor(Vendor $vendor)
    {
        return view('purchase-manager.vendors.edit', compact('vendor'));
    }

    public function updateVendor(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'contact_person' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $vendor->update($request->all());

        return redirect()->route('purchase-manager.vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function deleteVendor(Request $request, Vendor $vendor)
    {
        if ($request->has('approve_delete')) {
            $vendor->delete();
            return redirect()->route('purchase-manager.vendors.index')->with('success', 'Vendor deleted successfully.');
        }

        return view('purchase-manager.vendors.delete', compact('vendor'));
    }

    // ==================== PRODUCTS ====================

    public function products()
    {
        $products = Product::latest()->paginate(15);
        return view('purchase-manager.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('purchase-manager.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'status' => 'required|in:active,inactive',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'unit' => $request->unit,
            'unit_price' => $request->unit_price,
            'sku' => $request->sku,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('purchase-manager.products.index')->with('success', 'Product created successfully.');
    }

    public function showProduct(Product $product)
    {
        $product->load('createdBy');
        return view('purchase-manager.products.show', compact('product'));
    }

    public function editProduct(Product $product)
    {
        return view('purchase-manager.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($request->all());

        return redirect()->route('purchase-manager.products.index')->with('success', 'Product updated successfully.');
    }

    public function deleteProduct(Request $request, Product $product)
    {
        if ($request->has('approve_delete')) {
            $product->delete();
            return redirect()->route('purchase-manager.products.index')->with('success', 'Product deleted successfully.');
        }

        return view('purchase-manager.products.delete', compact('product'));
    }

    // ==================== RFQs ====================

    public function rfqs()
    {
        $rfqs = RFQ::with(['vendor', 'project', 'items.product', 'createdBy'])->latest()->paginate(15);
        $vendors = Vendor::where('status', 'active')->get();
        return view('purchase-manager.rfqs.index', compact('rfqs', 'vendors'));
    }

    public function createRFQ()
    {
        $vendors = Vendor::where('status', 'active')->get();
        $products = Product::where('is_active', true)->get();
        $projects = \App\Models\Project::active()->get();
        return view('purchase-manager.rfqs.create', compact('vendors', 'products', 'projects'));
    }

    public function storeRFQ(Request $request)
    {
        try {
            $request->validate([
                'vendor_id' => 'required|exists:vendors,id',
                'project_id' => 'nullable|exists:projects,id',
                'rfq_date' => 'required|date',
                'quotation_due_date' => 'required|date|after:rfq_date',
                'valid_until' => 'nullable|date|after:rfq_date',
                'status' => 'required|in:draft,sent',
                'estimated_budget' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'terms_conditions' => 'nullable|string',
                'delivery_terms' => 'nullable|string',
                'payment_terms' => 'nullable|string',
            ]);

            // Generate unique RFQ number if not provided
            $rfqNumber = $request->rfq_number;
            if (!$rfqNumber) {
                $year = date('Y');
                
                // Find the highest existing RFQ number for this year
                $lastRFQ = RFQ::where('rfq_number', 'like', "RFQ-{$year}-%")
                    ->orderByRaw('CAST(SUBSTRING(rfq_number, -4) AS UNSIGNED) DESC')
                    ->first();
                
                if ($lastRFQ) {
                    $lastNumber = (int) substr($lastRFQ->rfq_number, -4);
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = 1;
                }
                
                $rfqNumber = 'RFQ-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                
                // Double-check for uniqueness (in case of race conditions)
                while (RFQ::where('rfq_number', $rfqNumber)->exists()) {
                    $nextNumber++;
                    $rfqNumber = 'RFQ-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                }
            }

            $rfq = RFQ::create([
                'rfq_number' => $rfqNumber,
                'vendor_id' => $request->vendor_id,
                'project_id' => $request->project_id,
                'rfq_date' => $request->rfq_date,
                'quotation_due_date' => $request->quotation_due_date,
                'valid_until' => $request->valid_until,
                'status' => $request->status,
                'estimated_budget' => $request->estimated_budget,
                'description' => $request->description,
                'terms_conditions' => $request->terms_conditions,
                'delivery_terms' => $request->delivery_terms,
                'payment_terms' => $request->payment_terms,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('purchase-manager.rfqs.index')->with('success', 'RFQ created successfully.');

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle duplicate entry errors gracefully
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                // Generate a new unique RFQ number and try again
                $year = date('Y');
                $maxAttempts = 10;
                $attempt = 0;
                
                do {
                    $attempt++;
                    $randomSuffix = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    $rfqNumber = 'RFQ-' . $year . '-' . $randomSuffix;
                } while (RFQ::where('rfq_number', $rfqNumber)->exists() && $attempt < $maxAttempts);
                
                if ($attempt >= $maxAttempts) {
                    return redirect()->back()->with('error', 'RFQ number already exists. Please try again with a different number.')->withInput();
                }
                
                // Try creating with the new number
                try {
                    $rfq = RFQ::create([
                        'rfq_number' => $rfqNumber,
                        'vendor_id' => $request->vendor_id,
                        'project_id' => $request->project_id,
                        'rfq_date' => $request->rfq_date,
                        'quotation_due_date' => $request->quotation_due_date,
                        'valid_until' => $request->valid_until,
                        'status' => $request->status,
                        'estimated_budget' => $request->estimated_budget,
                        'description' => $request->description,
                        'terms_conditions' => $request->terms_conditions,
                        'delivery_terms' => $request->delivery_terms,
                        'payment_terms' => $request->payment_terms,
                        'created_by' => Auth::id(),
                    ]);
                    
                    return redirect()->route('purchase-manager.rfqs.index')->with('success', 'RFQ created successfully with number: ' . $rfqNumber);
                } catch (\Exception $retryException) {
                    return redirect()->back()->with('error', 'RFQ number already exists. Please try again with a different number.')->withInput();
                }
            }
            
            return redirect()->back()->with('error', 'Something went wrong. Please try again.')->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.')->withInput();
        }
    }

    public function showRFQ(RFQ $rfq)
    {
        $rfq->load(['vendor', 'items.product', 'createdBy']);
        return view('purchase-manager.rfqs.show', compact('rfq'));
    }

    public function editRFQ(RFQ $rfq)
    {
        $vendors = Vendor::where('status', 'active')->get();
        $products = Product::where('is_active', true)->get();
        $projects = \App\Models\Project::active()->get();
        $rfq->load('items');
        return view('purchase-manager.rfqs.edit', compact('rfq', 'vendors', 'products', 'projects'));
    }

    public function updateRFQ(Request $request, RFQ $rfq)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'project_id' => 'nullable|exists:projects,id',
            'rfq_date' => 'required|date',
            'quotation_due_date' => 'required|date|after:rfq_date',
            'valid_until' => 'nullable|date|after:rfq_date',
            'status' => 'required|in:draft,sent,received,evaluated,awarded,cancelled',
            'estimated_budget' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'payment_terms' => 'nullable|string',
        ]);

        $rfq->update([
            'rfq_number' => $request->rfq_number ?: $rfq->rfq_number,
            'vendor_id' => $request->vendor_id,
            'project_id' => $request->project_id,
            'rfq_date' => $request->rfq_date,
            'quotation_due_date' => $request->quotation_due_date,
            'valid_until' => $request->valid_until,
            'status' => $request->status,
            'estimated_budget' => $request->estimated_budget,
            'description' => $request->description,
            'terms_conditions' => $request->terms_conditions,
            'delivery_terms' => $request->delivery_terms,
            'payment_terms' => $request->payment_terms,
        ]);

        return redirect()->route('purchase-manager.rfqs.index')->with('success', 'RFQ updated successfully.');
    }

    public function deleteRFQ(Request $request, RFQ $rfq)
    {
        if ($request->has('approve_delete')) {
            $rfq->delete();
            return redirect()->route('purchase-manager.rfqs.index')->with('success', 'RFQ deleted successfully.');
        }

        return view('purchase-manager.rfqs.delete', compact('rfq'));
    }

    // ==================== PURCHASE REQUISITIONS ====================

    public function purchaseRequisitions()
    {
        $requisitions = PurchaseRequisition::with(['items.product', 'createdBy'])->latest()->paginate(15);
        return view('purchase-manager.purchase-requisitions.index', compact('requisitions'));
    }

    public function createPurchaseRequisition()
    {
        $products = Product::where('is_active', true)->get();
        return view('purchase-manager.purchase-requisitions.create', compact('products'));
    }

    public function storePurchaseRequisition(Request $request)
    {
        $request->validate([
            'requisition_date' => 'required|date',
            'required_date' => 'required|date|after:requisition_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.purpose' => 'nullable|string',
        ]);

        $requisition = PurchaseRequisition::create([
            'requisition_date' => $request->requisition_date,
            'required_date' => $request->required_date,
            'status' => 'pending',
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        foreach ($request->items as $item) {
            PurchaseRequisitionItem::create([
                'purchase_requisition_id' => $requisition->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'purpose' => $item['purpose'] ?? '',
            ]);
        }

        return redirect()->route('purchase-manager.purchase-requisitions.index')->with('success', 'Purchase Requisition created successfully.');
    }

    public function showPurchaseRequisition(PurchaseRequisition $requisition)
    {
        $requisition->load(['items.product', 'createdBy']);
        return view('purchase-manager.purchase-requisitions.show', compact('requisition'));
    }

    public function editPurchaseRequisition(PurchaseRequisition $requisition)
    {
        $products = Product::where('is_active', true)->get();
        $requisition->load('items');
        return view('purchase-manager.purchase-requisitions.edit', compact('requisition', 'products'));
    }

    public function updatePurchaseRequisition(Request $request, PurchaseRequisition $requisition)
    {
        $request->validate([
            'requisition_date' => 'required|date',
            'required_date' => 'required|date|after:requisition_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.purpose' => 'nullable|string',
        ]);

        $requisition->update([
            'requisition_date' => $request->requisition_date,
            'required_date' => $request->required_date,
            'notes' => $request->notes,
        ]);

        // Delete existing items
        $requisition->items()->delete();

        // Create new items
        foreach ($request->items as $item) {
            PurchaseRequisitionItem::create([
                'purchase_requisition_id' => $requisition->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'purpose' => $item['purpose'] ?? '',
            ]);
        }

        return redirect()->route('purchase-manager.purchase-requisitions.index')->with('success', 'Purchase Requisition updated successfully.');
    }

    public function deletePurchaseRequisition(Request $request, PurchaseRequisition $requisition)
    {
        if ($request->has('approve_delete')) {
            $requisition->delete();
            return redirect()->route('purchase-manager.purchase-requisitions.index')->with('success', 'Purchase Requisition deleted successfully.');
        }

        return view('purchase-manager.purchase-requisitions.delete', compact('requisition'));
    }

    // ==================== VENDOR REGISTRATIONS ====================

    public function vendorRegistrations()
    {
        $registrations = VendorRegistration::latest()->paginate(15);
        return view('purchase-manager.vendor-registrations.index', compact('registrations'));
    }

    public function createVendorRegistration()
    {
        return view('purchase-manager.vendor-registrations.create');
    }

    public function storeVendorRegistration(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:vendor_registrations,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'business_type' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $registration = VendorRegistration::create([
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'business_type' => $request->business_type,
            'gst_number' => $request->gst_number,
            'pan_number' => $request->pan_number,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('vendor-documents', 'public');
                $registration->documents()->create([
                    'file_name' => $document->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $document->getMimeType(),
                    'file_size' => $document->getSize(),
                ]);
            }
        }

        return redirect()->route('purchase-manager.vendor-registrations.index')->with('success', 'Vendor Registration created successfully.');
    }

    public function showVendorRegistration(VendorRegistration $registration)
    {
        $registration->load(['createdBy', 'documents']);
        return view('purchase-manager.vendor-registrations.show', compact('registration'));
    }

    public function editVendorRegistration(VendorRegistration $registration)
    {
        $registration->load('documents');
        return view('purchase-manager.vendor-registrations.edit', compact('registration'));
    }

    public function updateVendorRegistration(Request $request, VendorRegistration $registration)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:vendor_registrations,email,' . $registration->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'business_type' => 'required|string|max:255',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'status' => 'required|in:pending,approved,rejected',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $registration->update([
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'business_type' => $request->business_type,
            'gst_number' => $request->gst_number,
            'pan_number' => $request->pan_number,
            'status' => $request->status,
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $path = $document->store('vendor-documents', 'public');
                $registration->documents()->create([
                    'file_name' => $document->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $document->getMimeType(),
                    'file_size' => $document->getSize(),
                ]);
            }
        }

        return redirect()->route('purchase-manager.vendor-registrations.index')->with('success', 'Vendor Registration updated successfully.');
    }

    public function deleteVendorRegistration(Request $request, VendorRegistration $registration)
    {
        if ($request->has('approve_delete')) {
            // Delete associated documents
            foreach ($registration->documents as $document) {
                if (Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
                $document->delete();
            }
            
            $registration->delete();
            return redirect()->route('purchase-manager.vendor-registrations.index')->with('success', 'Vendor Registration deleted successfully.');
        }

        return view('purchase-manager.vendor-registrations.delete', compact('registration'));
    }
}