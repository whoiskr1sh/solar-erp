<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\StockValuation;
use App\Models\StockLedger;
use App\Models\QualityCheck;
use App\Models\InventoryAudit;
use App\Models\Warehouse;

class InventoryController extends Controller
{
    /**
     * Display Inward-GRN page
     */
    public function inwardGrn(Request $request): View
    {
        $grnController = new \App\Http\Controllers\GRNController();
        return $grnController->index($request);
    }

    /**
     * Display Outward-Delivery Challan/Note page
     */
    public function outwardDelivery(): View
    {
        return view('inventory.outward-delivery');
    }

    /**
     * Export outward delivery data as CSV (static/demo data for now).
     */
    public function exportOutwardDelivery(Request $request)
    {
        $filename = 'outward_delivery_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Challan Number',
                'Customer Name',
                'Delivery Date',
                'Status',
                'Total Quantity',
            ]);

            // Placeholder rows for now
            fputcsv($file, ['DC-1001', 'ABC Solar Pvt Ltd', date('Y-m-d'), 'delivered', 10]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Warehouse Management page
     */
    public function warehouseManagement(Request $request): View
    {
        $query = Warehouse::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('warehouse_name', 'like', '%' . $request->search . '%')
                  ->orWhere('warehouse_code', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $warehouses = $query->orderBy('warehouse_name', 'asc')->paginate(20);

        $stats = [
            'total_warehouses' => Warehouse::count(),
            'active_warehouses' => Warehouse::where('status', 'active')->count(),
            'maintenance_warehouses' => Warehouse::where('status', 'maintenance')->count(),
            'total_items' => Warehouse::sum('total_items'),
        ];

        return view('inventory.warehouse-management', compact('warehouses', 'stats'));
    }

    /**
     * Export warehouse management data as CSV (applies same filters as warehouseManagement).
     */
    public function exportWarehouseManagement(Request $request)
    {
        $query = Warehouse::query();

        // Apply same filters as warehouseManagement
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('warehouse_name', 'like', '%' . $request->search . '%')
                  ->orWhere('warehouse_code', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $warehouses = $query->orderBy('warehouse_name', 'asc')->get();

        $filename = 'warehouses_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($warehouses) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Warehouse Code',
                'Warehouse Name',
                'Location',
                'Manager Name',
                'Manager Email',
                'Status',
                'Capacity Percentage',
                'Total Items',
            ]);

            foreach ($warehouses as $warehouse) {
                fputcsv($file, [
                    $warehouse->warehouse_code,
                    $warehouse->warehouse_name,
                    $warehouse->location,
                    $warehouse->manager_name,
                    $warehouse->manager_email,
                    $warehouse->status,
                    $warehouse->capacity_percentage,
                    $warehouse->total_items,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Stock Ledger page
     */
    public function stockLedger(Request $request): View
    {
        $query = StockLedger::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->search . '%')
                  ->orWhere('item_code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('warehouse')) {
            $query->where('warehouse', $request->warehouse);
        }

        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }

        $stockLedgers = $query->orderBy('transaction_date', 'desc')
                             ->orderBy('transaction_time', 'desc')
                             ->paginate(20);

        return view('inventory.stock-ledger', compact('stockLedgers'));
    }

    /**
     * Export stock ledger as CSV (applies same filters as stockLedger).
     */
    public function exportStockLedger(Request $request)
    {
        $query = StockLedger::query();

        // Apply same filters as stockLedger
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->search . '%')
                  ->orWhere('item_code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('warehouse')) {
            $query->where('warehouse', $request->warehouse);
        }

        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }

        $stockLedgers = $query->orderBy('transaction_date', 'desc')
                             ->orderBy('transaction_time', 'desc')
                             ->get();

        $filename = 'stock_ledger_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($stockLedgers) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Transaction Date',
                'Transaction Time',
                'Item Code',
                'Item Name',
                'Warehouse',
                'Transaction Type',
                'Quantity',
                'Balance Quantity',
                'Reference',
            ]);

            foreach ($stockLedgers as $ledger) {
                fputcsv($file, [
                    $ledger->transaction_date ? $ledger->transaction_date->format('Y-m-d') : null,
                    $ledger->transaction_time,
                    $ledger->item_code,
                    $ledger->item_name,
                    $ledger->warehouse,
                    $ledger->transaction_type,
                    $ledger->quantity,
                    $ledger->balance_quantity,
                    $ledger->reference,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Inward Quality Check page
     */
    public function inwardQualityCheck(Request $request): View
    {
        $query = QualityCheck::with(['checkedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('qc_number', 'like', '%' . $request->search . '%')
                  ->orWhere('item_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('inspector')) {
            $query->where('inspector_name', $request->inspector);
        }

        $qualityChecks = $query->orderBy('qc_date', 'desc')->paginate(20);

        return view('inventory.inward-quality-check', compact('qualityChecks'));
    }

    /**
     * Export inward quality checks as CSV (applies same filters as inwardQualityCheck).
     */
    public function exportInwardQualityCheck(Request $request)
    {
        $query = QualityCheck::with(['checkedBy']);

        // Apply same filters as inwardQualityCheck
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('qc_number', 'like', '%' . $request->search . '%')
                  ->orWhere('item_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('inspector')) {
            $query->where('inspector_name', $request->inspector);
        }

        $qualityChecks = $query->orderBy('qc_date', 'desc')->get();

        $filename = 'inward_quality_checks_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($qualityChecks) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'QC Number',
                'QC Date',
                'Item Name',
                'Quantity Received',
                'Quantity Accepted',
                'Quantity Rejected',
                'Status',
                'Inspector Name',
            ]);

            foreach ($qualityChecks as $qc) {
                fputcsv($file, [
                    $qc->qc_number,
                    $qc->qc_date ? $qc->qc_date->format('Y-m-d') : null,
                    $qc->item_name,
                    $qc->quantity_received,
                    $qc->quantity_accepted,
                    $qc->quantity_rejected,
                    $qc->status,
                    $qc->inspector_name,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Stock Valuation Summary page
     */
    public function stockValuationSummary(): View
    {
        $stockValuations = StockValuation::all();
        
        $stats = [
            'total_stock_value' => StockValuation::sum('total_value'),
            'total_items' => StockValuation::sum('quantity'),
            'avg_cost_per_item' => StockValuation::avg('unit_cost'),
            'total_warehouses' => StockValuation::distinct('warehouse')->count(),
        ];

        return view('inventory.stock-valuation-summary', compact('stockValuations', 'stats'));
    }

    /**
     * Export stock valuation summary as CSV.
     */
    public function exportStockValuationSummary()
    {
        $stockValuations = StockValuation::all();

        $filename = 'stock_valuation_summary_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($stockValuations) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Item Code',
                'Item Name',
                'Warehouse',
                'Quantity',
                'Unit Cost',
                'Total Value',
            ]);

            foreach ($stockValuations as $sv) {
                fputcsv($file, [
                    $sv->item_code,
                    $sv->item_name,
                    $sv->warehouse,
                    $sv->quantity,
                    $sv->unit_cost,
                    $sv->total_value,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Warehouse Location page
     */
    public function warehouseLocation(): View
    {
        return view('inventory.warehouse-location');
    }

    /**
     * Export warehouse locations as CSV (static/demo data for now).
     */
    public function exportWarehouseLocation()
    {
        $filename = 'warehouse_locations_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Warehouse Code',
                'Warehouse Name',
                'Zone',
                'Rack Range',
            ]);

            // Demo rows to match UI example
            fputcsv($file, ['WH-001', 'Main Warehouse - Mumbai', 'Zone A - Solar Panels', 'Rack 1-10']);
            fputcsv($file, ['WH-001', 'Main Warehouse - Mumbai', 'Zone B - Inverters', 'Rack 11-20']);
            fputcsv($file, ['WH-002', 'Secondary Warehouse - Pune', 'Zone A - Panels', 'Rack 1-8']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display Inventory Audit page
     */
    public function inventoryAudit(Request $request): View
    {
        $query = InventoryAudit::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('audit_id', 'like', '%' . $request->search . '%')
                  ->orWhere('warehouse_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inventoryAudits = $query->orderBy('start_date', 'desc')->paginate(20);

        $stats = [
            'total_audits' => InventoryAudit::count(),
            'completed' => InventoryAudit::where('status', 'completed')->count(),
            'in_progress' => InventoryAudit::where('status', 'in_progress')->count(),
            'discrepancies' => InventoryAudit::where('status', 'completed')->where('remarks', 'like', '%discrepancy%')->count(),
        ];

        return view('inventory.inventory-audit', compact('inventoryAudits', 'stats'));
    }

    /**
     * Export inventory audits as CSV (applies same filters as inventoryAudit).
     */
    public function exportInventoryAudit(Request $request)
    {
        $query = InventoryAudit::query();

        // Apply same filters as inventoryAudit
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('audit_id', 'like', '%' . $request->search . '%')
                  ->orWhere('warehouse_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inventoryAudits = $query->orderBy('start_date', 'desc')->get();

        $filename = 'inventory_audits_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($inventoryAudits) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Audit ID',
                'Warehouse Name',
                'Start Date',
                'End Date',
                'Status',
                'Remarks',
            ]);

            foreach ($inventoryAudits as $audit) {
                fputcsv($file, [
                    $audit->audit_id,
                    $audit->warehouse_name,
                    $audit->start_date ? $audit->start_date->format('Y-m-d') : null,
                    $audit->end_date ? $audit->end_date->format('Y-m-d') : null,
                    $audit->status,
                    $audit->remarks,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
