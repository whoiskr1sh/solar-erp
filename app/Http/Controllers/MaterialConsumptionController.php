<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialConsumption;
use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MaterialConsumptionController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialConsumption::with(['material', 'materialRequest', 'project', 'consumedBy', 'supervisedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('consumption_number', 'like', "%{$search}%")
                  ->orWhere('activity_description', 'like', "%{$search}%")
                  ->orWhereHas('material', function($subQuery) use ($search) {
                      $subQuery->where('item_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('consumption_status', $request->status);
        }

        if ($request->filled('quality_status')) {
            $query->where('quality_status', $request->quality_status);
        }

        if ($request->filled('work_phase')) {
            $query->where('work_phase', $request->work_phase);
        }

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        if ($request->filled('consumed_by')) {
            $query->where('consumed_by', $request->consumed_by);
        }

        if ($request->filled('high_wastage')) {
            $query->where('wastage_percentage', '>', 10);
        }

        if ($request->filled('date_from')) {
            $query->where('consumption_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('consumption_date', '<=', $request->date_to);
        }

        $consumptions = $query->orderBy('consumption_date', 'desc')->paginate(15);

        // Calculate summary stats
        $stats = [
            'total_consumptions' => MaterialConsumption::count(),
            'completed_consumptions' => MaterialConsumption::where('consumption_status', 'completed')->count(),
            'in_progress_consumptions' => MaterialConsumption::where('consumption_status', 'in_progress')->count(),
            'draft_consumptions' => MaterialConsumption::where('consumption_status', 'draft')->count(),
            'damaged_consumptions' => MaterialConsumption::where('quality_status', 'damaged')->count(),
            'high_wastage_consumptions' => MaterialConsumption::where('wastage_percentage', '>', 10)->count(),
            'total_cost' => MaterialConsumption::sum('total_cost'),
            'total_wastage_cost' => MaterialConsumption::sum('wastage_cost'),
            'this_month_consumptions' => MaterialConsumption::whereMonth('consumption_date', now()->month)->count(),
            'avg_efficiency' => MaterialConsumption::avg('consumption_percentage'),
        ];
        
        // Filter options
        $projects = Project::where('status', 'active')->get();
        $users = User::where('is_active', true)->get();
        
        return view('material-consumptions.index', compact('consumptions', 'stats', 'projects', 'users'));
    }

    public function create()
    {
        $materials = Material::where('status', 'received')->get();
        $projects = Project::where('status', 'active')->get();
        $users = User::where('is_active', true)->get();
        
        return view('material-consumptions.create', compact('materials', 'projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'material_request_id' => 'required|exists:material_requests,id',
            'project_id' => 'nullable|exists:projects,id',
            'activity_type' => 'required|in:installation,maintenance,repair,testing,demo,training',
            'activity_description' => 'required|string|max:1000',
            'work_phase' => 'required|in:preparation,foundation,structure,electrical,commissioning,maintenance,other',
            'work_location' => 'nullable|string|max:200',
            'quantity_consumed' => 'required|integer|min:1',
            'unit_of_measurement' => 'nullable|string|max:50',
            'consumption_percentage' => 'required|numeric|between:0,100',
            'wastage_percentage' => 'required|numeric|between:0,100',
            'return_percentage' => 'required|numeric|between:0,100',
            'quality_status' => 'required|in:good,damaged,defective,expired',
            'consumption_status' => 'required|in:draft,in_progress,completed,partial,damaged,returned',
            'unit_cost' => 'required|numeric|min:0',
            'cost_center' => 'nullable|string|max:100',
            'consumption_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'documentation_type' => 'nullable|in:receipt,photo,video,report',
            'documentation_path' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'quality_observations' => 'nullable|string|max:1000',
            'consumed_by' => 'required|exists:users,id',
            'supervised_by' => 'nullable|exists:users,id',
        ]);

        // Validate that percentages sum correctly
        $totalPercentage = $validated['consumption_percentage'] + $validated['wastage_percentage'] + $validated['return_percentage'];
        if ($totalPercentage > 100) {
            return back()->withErrors([
                'percentage_total' => 'Consumption, wastage, and return percentages must not exceed 100%'
            ])->withInput();
        }

        // Validate quantity against material availability
        $material = Material::findOrFail($validated['material_id']);
        if ($validated['quantity_consumed'] > $material->remaining_quantity) {
            return back()->withErrors([
                'quantity_consumed' => 'Quantity consumed cannot exceed remaining quantity (' . $material->remaining_quantity . ')'
            ])->withInput();
        }

        $consumption = MaterialConsumption::create($validated);

        return redirect()->route('material-consumptions.show', $consumption)
            ->with('success', 'Material consumption recorded successfully.');
    }

    public function show(MaterialConsumption $materialConsumption)
    {
        $materialConsumption->load(['material', 'materialRequest', 'project', 'consumedBy', 'supervisedBy', 'approvedBy']);
        return view('material-consumptions.show', compact('materialConsumption'));
    }

    public function edit(MaterialConsumption $materialConsumption)
    {
        $materials = Material::where('status', 'received')->get();
        $projects = Project::where('status', 'active')->get();
        $users = User::where('is_active', true)->get();
        
        return view('material-consumptions.edit', compact('materialConsumption', 'materials', 'projects', 'users'));
    }

    public function update(Request $request, MaterialConsumption $materialConsumption)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'material_request_id' => 'required|exists:material_requests,id',
            'project_id' => 'nullable|exists:projects,id',
            'activity_type' => 'required|in:installation,maintenance,repair,testing,demo,training',
            'activity_description' => 'required|string|max:1000',
            'work_phase' => 'required|in:preparation,foundation,structure,electrical,commissioning,maintenance,other',
            'work_location' => 'nullable|string|max:200',
            'quantity_consumed' => 'required|integer|min:1',
            'unit_of_measurement' => 'nullable|string|max:50',
            'consumption_percentage' => 'required|numeric|between:0,100',
            'wastage_percentage' => 'required|numeric|between:0,100',
            'return_percentage' => 'required|numeric|between:0,100',
            'quality_status' => 'required|in:good,damaged,defective,expired',
            'consumption_status' => 'required|in:draft,in_progress,completed,partial,damaged,returned',
            'unit_cost' => 'required|numeric|min:0',
            'cost_center' => 'nullable|string|max:100',
            'consumption_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'documentation_type' => 'nullable|in:receipt,photo,video,report',
            'documentation_path' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'quality_observations' => 'nullable|string|max:1000',
            'consumed_by' => 'required|exists:users,id',
            'supervised_by' => 'nullable|exists:users,id',
        ]);

        // Validate that percentages sum correctly
        $totalPercentage = $validated['consumption_percentage'] + $validated['wastage_percentage'] + $validated['return_percentage'];
        if ($totalPercentage > 100) {
            return back()->withErrors([
                'percentage_total' => 'Consumption, wastage, and return percentages must not exceed 100%'
            ])->withInput();
        }

        $materialConsumption->update($validated);

        return redirect()->route('material-consumptions.show', $materialConsumption)
            ->with('success', 'Material consumption updated successfully.');
    }

    public function destroy(MaterialConsumption $materialConsumption)
    {
        // Release consumed material back to stock
        $materialConsumption->material()->update([
            'consumed_quantity' => $materialConsumption->material->consumed_quantity - $materialConsumption->quantity_consumed,
            'remaining_quantity' => $materialConsumption->material->remaining_quantity + $materialConsumption->quantity_consumed,
            'status' => 'available',
        ]);

        $materialConsumption->delete();

        return redirect()->route('material-consumptions.index')
            ->with('success', 'Material consumption deleted successfully.');
    }

    public function dashboard()
    {
        // Get comprehensive dashboard stats
        $summary = [
            'total_consumptions' => MaterialConsumption::count(),
            'completed_consumptions' => MaterialConsumption::where('consumption_status', 'completed')->count(),
            'in_progress_consumptions' => MaterialConsumption::where('consumption_status', 'in_progress')->count(),
            'draft_consumptions' => MaterialConsumption::where('consumption_status', 'draft')->count(),
            'damaged_consumptions' => MaterialConsumption::where('quality_status', 'damaged')->count(),
            'high_wastage_count' => MaterialConsumption::where('wastage_percentage', '>', 10)->count(),
            'total_cost' => MaterialConsumption::sum('total_cost'),
            'total_wastage_cost' => MaterialConsumption::sum('wastage_cost'),
            'avg_efficiency' => MaterialConsumption::avg('consumption_percentage'),
            'this_month_consumptions' => MaterialConsumption::whereMonth('consumption_date', now()->month)->count(),
        ];

        // Calculate efficiency metrics
        $summary['efficiency_rate'] = $summary['avg_efficiency'] ? round($summary['avg_efficiency'], 1) : 0;
        $summary['wastage_rate'] = MaterialConsumption::avg('wastage_percentage') ? round(MaterialConsumption::avg('wastage_percentage'), 1) : 0;

        // Recent consumptions
        $recentConsumptions = MaterialConsumption::with(['material', 'project', 'consumedBy'])
            ->orderBy('consumption_date', 'desc')
            ->take(5)
            ->get();

        // High wastage consumptions
        $highWastageConsumptions = MaterialConsumption::with(['material', 'project', 'consumedBy'])
            ->where('wastage_percentage', '>', 10)
            ->orderBy('wastage_percentage', 'desc')
            ->take(5)
            ->get();

        // Status breakdown
        $statusBreakdown = MaterialConsumption::groupBy('consumption_status')
            ->selectRaw('consumption_status, count(*) as count')
            ->pluck('count', 'consumption_status');

        // Quality breakdown
        $qualityBreakdown = MaterialConsumption::groupBy('quality_status')
            ->selectRaw('quality_status, count(*) as count')
            ->pluck('count', 'quality_status');

        // Work phase breakdown
        $workPhaseBreakdown = MaterialConsumption::groupBy('work_phase')
            ->selectRaw('work_phase, count(*) as count')
            ->pluck('count', 'work_phase');

        // Activity type breakdown
        $activityTypeBreakdown = MaterialConsumption::groupBy('activity_type')
            ->selectRaw('activity_type, count(*) as count')
            ->pluck('count', 'activity_type');

        // Monthly consumption trend (last 6 months)
        $monthlyTrend = MaterialConsumption::selectRaw('MONTH(consumption_date) as month, COUNT(*) as count, SUM(total_cost) as total_cost')
            ->where('consumption_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top consumed materials
        $topConsumedMaterials = MaterialConsumption::with('material')
            ->selectRaw('material_id, COUNT(*) as consumption_count, SUM(quantity_consumed) as total_quantity, SUM(total_cost) as total_cost')
            ->groupBy('material_id')
            ->orderByDesc('consumption_count')
            ->take(5)
            ->get();

        return view('material-consumptions.dashboard', compact(
            'summary',
            'recentConsumptions',
            'highWastageConsumptions',
            'statusBreakdown',
            'qualityBreakdown',
            'workPhaseBreakdown',
            'activityTypeBreakdown',
            'monthlyTrend',
            'topConsumedMaterials'
        ));
    }

    // Workflow Methods
    public function approve(MaterialConsumption $consumption)
    {
        $consumption->approve();
        
        return redirect()->route('material-consumptions.show', $consumption)
            ->with('success', 'Consumption approved successfully.');
    }

    public function markCompleted(MaterialConsumption $consumption)
    {
        $consumption->markCompleted();
        
        return redirect()->route('material-consumptions.show', $consumption)
            ->with('success', 'Consumption marked as completed.');
    }

    public function recordWaste(MaterialConsumption $consumption)
    {
        $consumption->recordWaste();
        
        return redirect()->route('material-consumptions.show', $consumption)
            ->with('success', 'Waste recorded successfully.');
    }

    public function returnToStock(Request $request, MaterialConsumption $consumption)
    {
        // Ensure quantity_consumed is not null
        $maxQuantity = $consumption->quantity_consumed ?? 0;
        
        $validated = $request->validate([
            'return_quantity' => 'required|integer|min:1|max:' . $maxQuantity,
        ]);

        $consumption->returnToStock($validated['return_quantity']);
        
        return redirect()->route('material-consumptions.show', $consumption)
            ->with('success', 'Return to stock processed successfully.');
    }

    // API Methods for mobile/app consumption
    public function quickConsume(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'quantity_consumed' => 'required|integer|min:1',
            'activity_description' => 'required|string',
            'work_location' => 'required|string',
        ]);

        // Auto-fill common fields
        $material = Material::findOrFail($validated['material_id']);
        
        $consumptionData = [
            'material_id' => $validated['material_id'],
            'material_request_id' => $material->material_request_id,
            'activity_type' => 'installation',
            'activity_description' => $validated['activity_description'],
            'work_phase' => 'structure',
            'work_location' => $validated['work_location'],
            'quantity_consumed' => $validated['quantity_consumed'],
            'consumption_percentage' => 90,
            'wastage_percentage' => 10,
            'return_percentage' => 0,
            'quality_status' => 'good',
            'consumption_status' => 'completed',
            'unit_cost' => $material->unit_price,
            'consumption_date' => now()->toDateString(),
            'consumed_by' => Auth::id(),
        ];

        $consumption = MaterialConsumption::create($consumptionData);
        $consumption->approve(); // Auto-approve quick consumes

        return response()->json([
            'success' => true,
            'consumption_id' => $consumption->id,
            'message' => 'Material consumed successfully'
        ]);
    }
}