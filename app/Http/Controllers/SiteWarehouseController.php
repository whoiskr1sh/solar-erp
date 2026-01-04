<?php

namespace App\Http\Controllers;

use App\Models\SiteWarehouse;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = SiteWarehouse::with(['project', 'manager']);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('warehouse_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%')
                  ->orWhere('contact_person', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('project', function($projectQuery) use ($searchTerm) {
                      $projectQuery->where('name', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('project_code', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('managed_by')) {
            $query->where('managed_by', $request->managed_by);
        }

        $warehouses = $query->orderBy('created_at', 'desc')->paginate(15);
        $projects = Project::orderBy('name')->get();
        $managers = User::orderBy('name')->get(); // Remove department filter for now

        return view('site-warehouses.index', compact('warehouses', 'projects', 'managers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $projects = Project::orderBy('name')->get();
        $managers = User::orderBy('name')->get(); // Remove department filter for now
        
        return view('site-warehouses.create', compact('projects', 'managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'warehouse_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'total_capacity' => 'nullable|numeric|min:0',
            'used_capacity' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,maintenance',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'managed_by' => 'required|exists:users,id',
        ]);

        SiteWarehouse::create($validated);

        return redirect()->route('site-warehouses.index')
            ->with('success', 'Site warehouse created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SiteWarehouse $siteWarehouse): View
    {
        $siteWarehouse->load(['project', 'manager']);
        return view('site-warehouses.show', compact('siteWarehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteWarehouse $siteWarehouse): View
    {
        $projects = Project::orderBy('name')->get();
        $managers = User::orderBy('name')->get(); // Remove department filter for now
        
        return view('site-warehouses.edit', compact('siteWarehouse', 'projects', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SiteWarehouse $siteWarehouse): RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'warehouse_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'total_capacity' => 'nullable|numeric|min:0',
            'used_capacity' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,maintenance',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'managed_by' => 'required|exists:users,id',
        ]);

        $siteWarehouse->update($validated);

        return redirect()->route('site-warehouses.index')
            ->with('success', 'Site warehouse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SiteWarehouse $siteWarehouse): RedirectResponse
    {
        $siteWarehouse->delete();

        return redirect()->route('site-warehouses.index')
            ->with('success', 'Site warehouse deleted successfully.');
    }
}