<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contractor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ContractorController extends Controller
{
    public function index(Request $request)
    {
        $query = Contractor::with(['creator', 'assignee']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contractor_code', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('contractor_type', $request->type);
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        if ($request->filled('state')) {
            $query->where('state', 'like', "%{$request->state}%");
        }

        if ($request->filled('specialization')) {
            $query->where('specialization', 'like', "%{$request->specialization}%");
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified === 'yes');
        }

        if ($request->filled('min_experience')) {
            $query->where('years_of_experience', '>=', $request->min_experience);
        }

        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        $contractors = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate summary stats
        $stats = [
            'total_contractors' => Contractor::count(),
            'active_contractors' => Contractor::where('status', 'active')->count(),
            'verified_contractors' => Contractor::where('is_verified', true)->count(),
            'available_contractors' => Contractor::where('availability', 'available')->count(),
            'companies' => Contractor::where('contractor_type', 'company')->count(),
            'individuals' => Contractor::where('contractor_type', 'individual')->count(),
            'subcontractors' => Contractor::where('contractor_type', 'subcontractor')->count(),
            'suspended_contractors' => Contractor::where('status', 'suspended')->count(),
            'total_projects_value' => Contractor::sum('total_value'),
            'avg_rating' => Contractor::avg('rating'),
            'avg_experience' => Contractor::avg('years_of_experience'),
            'this_month_added' => Contractor::whereMonth('created_at', now()->month)->count(),
        ];
        
        return view('contractors.index', compact('contractors', 'stats'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        
        return view('contractors.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'contractor_type' => 'required|in:individual,company,partnership,subcontractor',
            'pan_number' => 'nullable|string|max:12',
            'gst_number' => 'nullable|string|max:15',
            'aadhar_number' => 'nullable|string|max:12',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:11',
            'branch_name' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'skills' => 'nullable|array',
            'experience_description' => 'nullable|string',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'monthly_rate' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'availability' => 'required|in:available,busy,unavailable,on_project',
            'availability_notes' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended,blacklisted',
            'status_notes' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['created_by'] = Auth::id();
        
        Contractor::create($validated);

        return redirect()->route('contractors.index')
            ->with('success', 'Contractor created successfully.');
    }

    public function show(Contractor $contractor)
    {
        $contractor->load(['creator', 'assignee', 'verifier']);
        
        return view('contractors.show', compact('contractor'));
    }

    public function edit(Contractor $contractor)
    {
        $users = User::where('is_active', true)->get();
        
        return view('contractors.edit', compact('contractor', 'users'));
    }

    public function update(Request $request, Contractor $contractor)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'numeric|digits:6|regex:/^[1-9][0-9]{5}$/',
            'country' => 'nullable|string|max:100',
            'contractor_type' => 'required|in:individual,company,partnership,subcontractor',
            'pan_number' => 'nullable|string|max:12',
            'gst_number' => 'nullable|string|max:15',
            'aadhar_number' => 'nullable|string|max:12',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:11',
            'branch_name' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'skills' => 'nullable|array',
            'experience_description' => 'nullable|string',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'monthly_rate' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'availability' => 'required|in:available,busy,unavailable,on_project',
            'availability_notes' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended,blacklisted',
            'status_notes' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $contractor->update($validated);

        return redirect()->route('contractors.show', $contractor)
            ->with('success', 'Contractor updated successfully.');
    }

    public function destroy(Contractor $contractor)
    {
        $contractor->update(['status' => 'inactive']);

        return redirect()->route('contractors.index')
            ->with('success', 'Contractor deactivated successfully.');
    }

    public function verify(Contractor $contractor)
    {
        $contractor->markAsVerified(Auth::id());

        return redirect()->route('contractors.show', $contractor)
            ->with('success', 'Contractor verified successfully.');
    }

    public function updateAvailability(Request $request, Contractor $contractor)
    {
        $validated = $request->validate([
            'availability' => 'required|in:available,busy,unavailable,on_project',
            'availability_notes' => 'nullable|string',
        ]);

        $contractor->updateAvailability(
            $validated['availability'],
            $validated['availability_notes'] ?? null
        );

        return redirect()->route('contractors.show', $contractor)
            ->with('success', 'Contractor availability updated successfully.');
    }

    public function updateRating(Request $request, Contractor $contractor)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $contractor->updateRating($validated['rating']);

        return redirect()->route('contractors.show', $contractor)
            ->with('success', 'Contractor rating updated successfully.');
    }

    public function dashboard()
    {
        // Contractor summary stats
        $summary = [
            'total_contractors' => Contractor::count(),
            'active_contractors' => Contractor::where('status', 'active')->count(),
            'verified_contractors' => Contractor::where('is_verified', true)->count(),
            'available_contractors' => Contractor::where('availability', 'available')->count(),
            'companies' => Contractor::where('contractor_type', 'company')->count(),
            'individuals' => Contractor::where('contractor_type', 'individual')->count(),
            'certifications' => Contractor::where('contractor_type', 'company')->count(),
            'subcontractors' => Contractor::where('contractor_type', 'subcontractor')->count(),
            'avg_rating' => Contractor::avg('rating'),
            'avg_experience' => Contractor::avg('years_of_experience'),
            'total_projects_value' => Contractor::sum('total_value'),
            'this_month_added' => Contractor::whereMonth('created_at', now()->month)->count(),
        ];

        // Top contractors by rating
        $topContractors = Contractor::where('rating', '>', 4)
            ->orderByDesc('rating')
            ->orderByDesc('total_projects')
            ->take(5)
            ->get();

        // Type breakdown
        $typeBreakdown = Contractor::groupBy('contractor_type')
            ->selectRaw('contractor_type, count(*) as count')
            ->pluck('count', 'contractor_type');

        // Status breakdown
        $statusBreakdown = Contractor::groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->pluck('count', 'status');

        // Availability breakdown
        $availabilityBreakdown = Contractor::groupBy('availability')
            ->selectRaw('availability, count(*) as count')
            ->pluck('count', 'availability');

        // Recent contractors
        $recentContractors = Contractor::with(['creator'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Top performing contractors
        $performingContractors = Contractor::orderByDesc('total_value')
            ->orderByDesc('total_projects')
            ->take(5)
            ->get();

        // Specialization breakdown
        $specializationBreakdown = Contractor::whereNotNull('specialization')
            ->selectRaw('specialization, count(*) as count')
            ->groupBy('specialization')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('contractors.dashboard', compact(
            'summary',
            'topContractors',
            'typeBreakdown',
            'statusBreakdown',
            'availabilityBreakdown',
            'recentContractors',
            'performingContractors',
            'specializationBreakdown'
        ));
    }
}