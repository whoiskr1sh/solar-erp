<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Costing;
use App\Models\Project;
use App\Models\Lead;

class CostingController extends Controller
{
    public function index(Request $request)
    {
        $query = Costing::with(['project', 'creator', 'approver']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('costing_number', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $costings = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get filter options
        $projects = Project::select('id', 'name')->get();
        $users = \App\Models\User::select('id', 'name')->get();

        // Calculate stats
        $stats = [
            'total' => Costing::count(),
            'draft' => Costing::draft()->count(),
            'pending' => Costing::pending()->count(),
            'approved' => Costing::approved()->count(),
            'total_value' => Costing::sum('total_cost'),
            'this_month' => Costing::whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->count(),
        ];

        return view('costing.index', compact('costings', 'projects', 'users', 'stats'));
    }

    public function create()
    {
        $projects = Project::select('id', 'name')->get();
        $leads = Lead::select('id', 'name', 'email', 'phone')->get();
        
        return view('costing.create', compact('projects', 'leads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string|max:20',
            'project_description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'material_cost' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'equipment_cost' => 'required|numeric|min:0',
            'transportation_cost' => 'required|numeric|min:0',
            'overhead_cost' => 'required|numeric|min:0',
            'profit_margin' => 'required|numeric|min:0|max:100',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'validity_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $costing = new Costing($request->all());
        $costing->costing_number = $costing->generateCostingNumber();
        $costing->created_by = Auth::id();
        $costing->total_cost = $costing->calculateTotalCost();
        $costing->save();

        return redirect()->route('costing.index')
                        ->with('success', 'Costing created successfully!');
    }

    public function show(Costing $costing)
    {
        $costing->load(['project', 'creator', 'approver']);
        return view('costing.show', compact('costing'));
    }

    public function edit(Costing $costing)
    {
        $projects = Project::select('id', 'name')->get();
        $leads = Lead::select('id', 'name', 'email', 'phone')->get();
        
        return view('costing.edit', compact('costing', 'projects', 'leads'));
    }

    public function update(Request $request, Costing $costing)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|string|max:20',
            'project_description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'material_cost' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'equipment_cost' => 'required|numeric|min:0',
            'transportation_cost' => 'required|numeric|min:0',
            'overhead_cost' => 'required|numeric|min:0',
            'profit_margin' => 'required|numeric|min:0|max:100',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'validity_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $costing->update($request->all());
        $costing->total_cost = $costing->calculateTotalCost();
        $costing->save();

        return redirect()->route('costing.index')
                        ->with('success', 'Costing updated successfully!');
    }

    public function destroy(Costing $costing)
    {
        $costing->delete();
        
        return redirect()->route('costing.index')
                        ->with('success', 'Costing deleted successfully!');
    }

    public function approve(Costing $costing)
    {
        $costing->approve(Auth::id());
        
        return redirect()->back()
                        ->with('success', 'Costing approved successfully!');
    }

    public function reject(Costing $costing)
    {
        $costing->reject(Auth::id());
        
        return redirect()->back()
                        ->with('success', 'Costing rejected successfully!');
    }

    public function export(Request $request)
    {
        $query = Costing::with(['project', 'creator', 'approver']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('costing_number', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $costings = $query->orderBy('created_at', 'desc')->get();

        $filename = 'costings_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($costings) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Costing Number',
                'Project Name',
                'Client Name',
                'Client Email',
                'Client Phone',
                'Location',
                'Material Cost',
                'Labor Cost',
                'Equipment Cost',
                'Transportation Cost',
                'Overhead Cost',
                'Profit Margin (%)',
                'Tax Rate (%)',
                'Discount',
                'Total Cost',
                'Status',
                'Created By',
                'Created At',
                'Valid Until'
            ]);

            // CSV Data
            foreach ($costings as $costing) {
                fputcsv($file, [
                    $costing->costing_number,
                    $costing->project_name,
                    $costing->client_name,
                    $costing->client_email,
                    $costing->client_phone,
                    $costing->location,
                    $costing->material_cost,
                    $costing->labor_cost,
                    $costing->equipment_cost,
                    $costing->transportation_cost,
                    $costing->overhead_cost,
                    $costing->profit_margin,
                    $costing->tax_rate,
                    $costing->discount,
                    $costing->total_cost,
                    $costing->status,
                    $costing->creator->name ?? 'N/A',
                    $costing->created_at->format('Y-m-d H:i:s'),
                    $costing->validity_date ? $costing->validity_date->format('Y-m-d') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}