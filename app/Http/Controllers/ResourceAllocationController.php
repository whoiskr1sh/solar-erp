<?php

namespace App\Http\Controllers;

use App\Models\ResourceAllocation;
use App\Models\Project;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceAllocationController extends Controller
{
    public function index(Request $request)
    {
        $query = ResourceAllocation::with(['project', 'activity', 'allocatedTo', 'allocatedBy', 'approvedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('allocation_code', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('resource_name', 'like', "%{$search}%")
                ->orWhereHas('project', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('resource_type') && $request->input('resource_type') !== 'all') {
            $query->where('resource_type', $request->input('resource_type'));
        }

        if ($request->filled('priority') && $request->input('priority') !== 'all') {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('project_id') && $request->input('project_id') !== 'all') {
            $query->where('project_id', $request->input('project_id'));
        }

        if ($request->filled('activity_id') && $request->input('activity_id') !== 'all') {
            $query->where('activity_id', $request->input('activity_id'));
        }

        if ($request->filled('allocated_to') && $request->input('allocated_to') !== 'all') {
            $query->where('allocated_to', $request->input('allocated_to'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('allocation_start_date', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        if ($request->filled('overallocated') && $request->input('overallocated') === 'true') {
            $query->overallocated();
        }

        if ($request->filled('underutilized') && $request->input('underutilized') === 'true') {
            $query->underutilized();
        }

        if ($request->filled('critical') && $request->input('critical') === 'true') {
            $query->critical();
        }

        $allocations = $query->orderBy('allocation_start_date', 'asc')->paginate(20);

        // Get stats
        $stats = [
            'total' => ResourceAllocation::count(),
            'planned' => ResourceAllocation::planned()->count(),
            'allocated' => ResourceAllocation::allocated()->count(),
            'in_use' => ResourceAllocation::inUse()->count(),
            'completed' => ResourceAllocation::completed()->count(),
            'overallocated' => ResourceAllocation::overallocated()->count(),
            'underutilized' => ResourceAllocation::underutilized()->count(),
            'critical' => ResourceAllocation::critical()->count(),
        ];

        // Get filter options
        $users = User::select('id', 'name')->get();
        $projects = Project::select('id', 'name')->get();
        $activities = Activity::select('id', 'title')->get();

        return view('resource-allocations.index', compact('allocations', 'stats', 'users', 'projects', 'activities'));
    }

    public function create()
    {
        $users = User::select('id', 'name')->get();
        $projects = Project::select('id', 'name')->get();
        $activities = Activity::select('id', 'title')->get();

        return view('resource-allocations.create', compact('users', 'projects', 'activities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'resource_type' => 'required|in:human,equipment,material,financial,other',
            'priority' => 'required|in:low,medium,high,critical',
            'project_id' => 'required|exists:projects,id',
            'activity_id' => 'nullable|exists:activities,id',
            'resource_name' => 'required|string|max:255',
            'resource_category' => 'nullable|string|max:255',
            'resource_specifications' => 'nullable|string',
            'allocated_to' => 'nullable|exists:users,id',
            'allocation_start_date' => 'nullable|date|after_or_equal:today',
            'allocation_end_date' => 'nullable|date|after:allocation_start_date',
            'allocated_quantity' => 'required|numeric|min:0.01',
            'quantity_unit' => 'required|string|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'budget_allocated' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_critical' => 'boolean',
            'is_billable' => 'boolean',
        ]);

        $estimatedCost = 0;
        if ($request->resource_type === 'human') {
            $estimatedCost = $request->allocated_quantity * ($request->hourly_rate ?? 0);
        } else {
            $estimatedCost = $request->allocated_quantity * ($request->unit_cost ?? 0);
        }

        $allocation = ResourceAllocation::create([
            'allocation_code' => ResourceAllocation::generateAllocationCode(),
            'title' => $request->title,
            'description' => $request->description,
            'resource_type' => $request->resource_type,
            'priority' => $request->priority,
            'project_id' => $request->project_id,
            'activity_id' => $request->activity_id,
            'resource_name' => $request->resource_name,
            'resource_category' => $request->resource_category,
            'resource_specifications' => $request->resource_specifications,
            'allocated_to' => $request->allocated_to,
            'allocated_by' => Auth::id(),
            'allocation_start_date' => $request->allocation_start_date,
            'allocation_end_date' => $request->allocation_end_date,
            'allocated_quantity' => $request->allocated_quantity,
            'quantity_unit' => $request->quantity_unit,
            'hourly_rate' => $request->hourly_rate ?? 0,
            'unit_cost' => $request->unit_cost ?? 0,
            'total_estimated_cost' => $estimatedCost,
            'budget_allocated' => $request->budget_allocated ?? 0,
            'notes' => $request->notes,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'is_critical' => $request->boolean('is_critical'),
            'is_billable' => $request->boolean('is_billable', true),
        ]);

        return redirect()->route('resource-allocations.show', $allocation)->with('success', 'Resource allocation created successfully!');
    }

    public function show(ResourceAllocation $resourceAllocation)
    {
        $resourceAllocation->load(['project', 'activity', 'allocatedTo', 'allocatedBy', 'approvedBy']);
        
        return view('resource-allocations.show', compact('resourceAllocation'));
    }

    public function edit(ResourceAllocation $resourceAllocation)
    {
        $users = User::select('id', 'name')->get();
        $projects = Project::select('id', 'name')->get();
        $activities = Activity::select('id', 'title')->get();

        return view('resource-allocations.edit', compact('resourceAllocation', 'users', 'projects', 'activities'));
    }

    public function update(Request $request, ResourceAllocation $resourceAllocation)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'resource_type' => 'required|in:human,equipment,material,financial,other',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:planned,allocated,in_use,completed,cancelled',
            'project_id' => 'required|exists:projects,id',
            'activity_id' => 'nullable|exists:activities,id',
            'resource_name' => 'required|string|max:255',
            'resource_category' => 'nullable|string|max:255',
            'resource_specifications' => 'nullable|string',
            'allocated_to' => 'nullable|exists:users,id',
            'allocation_start_date' => 'nullable|date',
            'allocation_end_date' => 'nullable|date|after:allocation_start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after:actual_start_date',
            'allocated_quantity' => 'required|numeric|min:0.01',
            'actual_quantity' => 'nullable|numeric|min:0',
            'quantity_unit' => 'required|string|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'total_estimated_cost' => 'nullable|numeric|min:0',
            'total_actual_cost' => 'nullable|numeric|min:0',
            'budget_allocated' => 'nullable|numeric|min:0',
            'utilization_percentage' => 'nullable|numeric|min:0|max:100',
            'utilization_notes' => 'nullable|string',
            'completion_notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_critical' => 'boolean',
            'is_billable' => 'boolean',
        ]);

        $updateData = $request->except(['tags']);
        $updateData['tags'] = $request->tags ? explode(',', $request->tags) : null;
        $updateData['is_critical'] = $request->boolean('is_critical');
        $updateData['is_billable'] = $request->boolean('is_billable');

        // Handle status changes
        if ($request->status === 'allocated' && $resourceAllocation->status !== 'allocated') {
            $updateData['actual_start_date'] = $updateData['actual_start_date'] ?: now();
        }

        if ($request->status === 'completed' && $resourceAllocation->status !== 'completed') {
            $updateData['actual_end_date'] = $updateData['actual_end_date'] ?: now();
        }

        $resourceAllocation->update($updateData);

        return redirect()->route('resource-allocations.show', $resourceAllocation)->with('success', 'Resource allocation updated successfully!');
    }

    public function destroy(ResourceAllocation $resourceAllocation)
    {
        $resourceAllocation->delete();
        return redirect()->route('resource-allocations.index')->with('success', 'Resource allocation deleted successfully!');
    }

    // Status management methods
    public function markAllocated(ResourceAllocation $resourceAllocation)
    {
        $resourceAllocation->markAsAllocated();
        return back()->with('success', 'Resource marked as allocated!');
    }

    public function markInUse(ResourceAllocation $resourceAllocation)
    {
        $resourceAllocation->markAsInUse();
        return back()->with('success', 'Resource marked as in use!');
    }

    public function markCompleted(Request $request, ResourceAllocation $resourceAllocation)
    {
        $request->validate([
            'completion_notes' => 'required|string',
        ]);

        $resourceAllocation->markAsCompleted($request->completion_notes);
        return back()->with('success', 'Resource marked as completed!');
    }

    public function markCancelled(Request $request, ResourceAllocation $resourceAllocation)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $resourceAllocation->markAsCancelled($request->notes);
        return back()->with('success', 'Resource marked as cancelled!');
    }

    public function updateUtilization(Request $request, ResourceAllocation $resourceAllocation)
    {
        $request->validate([
            'utilization_percentage' => 'required|numeric|min:0|max:100',
            'utilization_notes' => 'nullable|string',
        ]);

        $resourceAllocation->updateUtilization($request->utilization_percentage, $request->utilization_notes);
        return back()->with('success', 'Utilization updated successfully!');
    }

    public function addQuantity(Request $request, ResourceAllocation $resourceAllocation)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $resourceAllocation->addActualQuantity($request->quantity);
        return back()->with('success', 'Quantity added successfully!');
    }

    public function addCost(Request $request, ResourceAllocation $resourceAllocation)
    {
        $request->validate([
            'cost' => 'required|numeric|min:0',
        ]);

        $resourceAllocation->addActualCost($request->cost);
        return back()->with('success', 'Cost added successfully!');
    }

    public function approve(ResourceAllocation $resourceAllocation)
    {
        $resourceAllocation->approve(Auth::user());
        return back()->with('success', 'Resource allocation approved successfully!');
    }

    public function export(Request $request)
    {
        $query = ResourceAllocation::with(['project', 'activity', 'allocatedTo', 'allocatedBy']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('allocation_code', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('resource_name', 'like', "%{$search}%")
                ->orWhereHas('project', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('resource_type') && $request->input('resource_type') !== 'all') {
            $query->where('resource_type', $request->input('resource_type'));
        }

        if ($request->filled('priority') && $request->input('priority') !== 'all') {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('project_id') && $request->input('project_id') !== 'all') {
            $query->where('project_id', $request->input('project_id'));
        }

        if ($request->filled('activity_id') && $request->input('activity_id') !== 'all') {
            $query->where('activity_id', $request->input('activity_id'));
        }

        if ($request->filled('allocated_to') && $request->input('allocated_to') !== 'all') {
            $query->where('allocated_to', $request->input('allocated_to'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('allocation_start_date', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        if ($request->filled('overallocated') && $request->input('overallocated') === 'true') {
            $query->overallocated();
        }

        if ($request->filled('underutilized') && $request->input('underutilized') === 'true') {
            $query->underutilized();
        }

        if ($request->filled('critical') && $request->input('critical') === 'true') {
            $query->critical();
        }

        $allocations = $query->orderBy('allocation_start_date', 'asc')->get();

        $filename = 'resource_allocations_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = function() use ($allocations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Allocation Code', 'Title', 'Resource Type', 'Priority', 'Status', 'Project',
                'Activity', 'Resource Name', 'Allocated To', 'Start Date', 'End Date', 'Quantity', 'Utilization %', 'Created At'
            ]);

            foreach ($allocations as $allocation) {
                fputcsv($file, [
                    $allocation->id,
                    $allocation->allocation_code,
                    $allocation->title,
                    ucfirst($allocation->resource_type),
                    ucfirst($allocation->priority),
                    ucfirst($allocation->status),
                    $allocation->project->name ?? 'N/A',
                    $allocation->activity->title ?? 'N/A',
                    $allocation->resource_name,
                    $allocation->allocatedTo->name ?? 'Unassigned',
                    $allocation->allocation_start_date ? $allocation->allocation_start_date->format('Y-m-d H:i') : 'Not set',
                    $allocation->allocation_end_date ? $allocation->allocation_end_date->format('Y-m-d H:i') : 'Not set',
                    $allocation->allocated_quantity . ' ' . $allocation->quantity_unit,
                    $allocation->utilization_percentage . '%',
                    $allocation->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
