<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialRequest;
use App\Models\Material;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MaterialRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialRequest::with(['project', 'requester', 'approver', 'assignee']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('request_type')) {
            $query->where('request_type', $request->request_type);
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        if ($request->filled('urgent')) {
            $query->where(function($q) {
                $q->where('priority', 'urgent')
                  ->orWhere('is_urgent', true)
                  ->orWhere('days_until_required', '<=', 3);
            });
        }

        $materialRequests = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate summary stats
        $stats = [
            'total_requests' => MaterialRequest::count(),
            'pending_requests' => MaterialRequest::whereIn('status', ['draft', 'pending'])->count(),
            'approved_requests' => MaterialRequest::where('status', 'approved')->count(),
            'completed_requests' => MaterialRequest::where('status', 'completed')->count(),
            'urgent_requests' => MaterialRequest::where('priority', 'urgent')->orWhere('is_urgent', true)->count(),
            'overdue_requests' => MaterialRequest::where('required_date', '<', now())->count(),
            'total_amount' => MaterialRequest::sum('total_amount'),
            'approved_amount' => MaterialRequest::sum('approved_amount'),
            'consumed_amount' => MaterialRequest::sum('consumed_amount'),
            'this_month_requests' => MaterialRequest::whereMonth('created_at', now()->month)->count(),
        ];
        
        return view('material-requests.index', compact('materialRequests', 'stats'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        $users = User::where('is_active', true)->get();
        
        return view('material-requests.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:raw_materials,tools_equipment,consumables,safety_items,electrical,mechanical,other',
            'request_type' => 'required|in:purchase,rental,transfer,emergency',
            'required_date' => 'required|date|after_or_equal:today',
            'urgency_reason' => 'required|in:normal,delay_risk,deadline_critical,equipment_failure,weather_dependent',
            'justification' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'materials.*.name' => 'required|string|max:255',
            'materials.*.description' => 'nullable|string',
            'materials.*.specification' => 'nullable|string',
            'materials.*.unit' => 'nullable|string|max:50',
            'materials.*.quantity' => 'required|integer|min:1',
            'materials.*.unit_price' => 'required|numeric|min:0',
        ]);

        $validated['requested_by'] = Auth::id();
        $validated['total_amount'] = $this->calculateTotalAmount($validated['materials']);

        $materialRequest = MaterialRequest::create($validated);

        // Create materials
        if (isset($validated['materials'])) {
            foreach ($validated['materials'] as $materialData) {
                $materialData['material_request_id'] = $materialRequest->id;
                $materialData['total_price'] = $materialData['quantity'] * $materialData['unit_price'];
                Material::create($materialData);
            }
        }

        return redirect()->route('material-requests.show', $materialRequest)
            ->with('success', 'Material request created successfully.');
    }

    public function show(MaterialRequest $materialRequest)
    {
        $materialRequest->load(['project', 'requester', 'approver', 'assignee', 'materials']);
        
        return view('material-requests.show', compact('materialRequest'));
    }

    public function edit(MaterialRequest $materialRequest)
    {
        $projects = Project::where('status', 'active')->get();
        $users = User::where('is_active', true)->get();
        
        return view('material-requests.edit', compact('materialRequest', 'projects', 'users'));
    }

    public function update(Request $request, MaterialRequest $materialRequest)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:raw_materials,tools_equipment,consumables,safety_items,electrical,mechanical,other',
            'request_type' => 'required|in:purchase,rental,transfer,emergency',
            'required_date' => 'required|date',
            'urgency_reason' => 'required|in:normal,delay_risk,deadline_critical,equipment_failure,weather_dependent',
            'justification' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $materialRequest->update($validated);

        return redirect()->route('material-requests.show', $materialRequest)
            ->with('success', 'Material request updated successfully.');
    }

    public function destroy(MaterialRequest $materialRequest)
    {
        $materialRequest->materials()->delete();
        $materialRequest->delete();

        return redirect()->route('material-requests.index')
            ->with('success', 'Material request deleted successfully.');
    }

    public function approve(Request $request, MaterialRequest $materialRequest)
    {
        $validated = $request->validate([
            'approved_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $materialRequest->approve(Auth::id(), $validated['approved_amount']);

        if ($request->filled('notes')) {
            $materialRequest->update(['notes' => $request->notes]);
        }

        return redirect()->route('material-requests.show', $materialRequest)
            ->with('success', 'Material request approved successfully.');
    }

    public function reject(Request $request, MaterialRequest $materialRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $materialRequest->reject(Auth::id(), $validated['rejection_reason']);

        return redirect()->route('material-requests.show', $materialRequest)
            ->with('success', 'Material request rejected.');
    }

    public function markInProgress(Request $request, MaterialRequest $materialRequest)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $materialRequest->markInProgress($validated['assigned_to']);

        return redirect()->route('material-requests.show', $materialRequest)
            ->with('success', 'Material request marked as in progress.');
    }

    public function markCompleted(MaterialRequest $materialRequest)
    {
        $materialRequest->markCompleted();

        return redirect()->route('material-requests.show', $materialRequest)
            ->with('success', 'Material request marked as completed.');
    }

    public function dashboard()
    {
        // Material request summary stats
        $summary = [
            'total_requests' => MaterialRequest::count(),
            'pending_requests' => MaterialRequest::whereIn('status', ['draft', 'pending'])->count(),
            'approved_requests' => MaterialRequest::where('status', 'approved')->count(),
            'completed_requests' => MaterialRequest::where('status', 'completed')->count(),
            'urgent_requests' => MaterialRequest::where('priority', 'urgent')->orWhere('is_urgent', true)->count(),
            'overdue_requests' => MaterialRequest::where('required_date', '<', now())->count(),
            'total_value' => MaterialRequest::sum('total_amount'),
            'approved_value' => MaterialRequest::sum('approved_amount'),
            'this_month_requests' => MaterialRequest::whereMonth('created_at', now()->month)->count(),
        ];

        // Recent requests
        $recentRequests = MaterialRequest::with(['project', 'requester'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Urgent requests
        $urgentRequests = MaterialRequest::with(['project', 'requester'])
            ->where(function($query) {
                $query->where('priority', 'urgent')
                      ->orWhere('is_urgent', true)
                      ->orWhere('days_until_required', '<=', 3);
            })
            ->take(5)
            ->get();

        // Status breakdown
        $statusBreakdown = MaterialRequest::groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status');

        // Category breakdown
        $categoryBreakdown = MaterialRequest::groupBy('category')
            ->selectRaw('category, count(*) as count')
            ->pluck('count', 'category');

        // Priority breakdown
        $priorityBreakdown = MaterialRequest::groupBy('priority')
            ->selectRaw('priority, count(*) as count')
            ->pluck('count', 'priority');

        // Type breakdown
        $typeBreakdown = MaterialRequest::groupBy('request_type')
            ->selectRaw('request_type, count(*) as count')
            ->pluck('count', 'request_type');

        return view('material-requests.dashboard', compact(
            'summary',
            'recentRequests',
            'urgentRequests',
            'statusBreakdown',
            'categoryBreakdown',
            'priorityBreakdown',
            'typeBreakdown'
        ));
    }

    private function calculateTotalAmount($materials): float
    {
        $total = 0;
        foreach ($materials as $material) {
            $total += $material['quantity'] * $material['unit_price'];
        }
        return $total;
    }
}