<?php

namespace App\Http\Controllers;

use App\Models\VendorRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VendorRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = VendorRegistration::with(['reviewer']);

        // Apply filters
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('company_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('contact_person', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('registration_number', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('registration_type')) {
            $query->where('registration_type', $request->registration_type);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $vendorRegistrations = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('vendor-registrations.index', compact('vendorRegistrations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('vendor-registrations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        \Log::info('VendorRegistration store method called', ['request_data' => $request->all()]);
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'registration_type' => 'required|in:Individual,Partnership,Company,LLP',
            'registration_date' => 'required|date',
            'business_description' => 'required|string',
            'categories' => 'required|array|min:1',
            'categories.*' => 'string|max:255',
        ]);

        \Log::info('Validation passed', ['validated_data' => $validated]);

        // Generate Registration Number
        $registrationNumber = 'VR-' . date('Y') . '-' . str_pad(VendorRegistration::count() + 1, 4, '0', STR_PAD_LEFT);

        try {
            $vendorRegistration = VendorRegistration::create([
                'registration_number' => $registrationNumber,
                'company_name' => $validated['company_name'],
                'contact_person' => $validated['contact_person'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'website' => $validated['website'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'pincode' => $validated['pincode'],
                'country' => $validated['country'],
                'gst_number' => $validated['gst_number'],
                'pan_number' => $validated['pan_number'],
                'registration_type' => $validated['registration_type'],
                'registration_date' => $validated['registration_date'],
                'business_description' => $validated['business_description'],
                'categories' => $validated['categories'],
                'documents' => [], // Will be handled by file upload later
            ]);

            \Log::info('Vendor registration created successfully', ['id' => $vendorRegistration->id]);

            return redirect()->route('vendor-registrations.index')
                ->with('success', 'Vendor registration submitted successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Error creating vendor registration', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create vendor registration: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VendorRegistration $vendorRegistration): View
    {
        $vendorRegistration->load(['reviewer']);
        return view('vendor-registrations.show', compact('vendorRegistration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VendorRegistration $vendorRegistration): View
    {
        return view('vendor-registrations.edit', compact('vendorRegistration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VendorRegistration $vendorRegistration): RedirectResponse
    {
        \Log::info('VendorRegistration update method called', ['id' => $vendorRegistration->id, 'request_data' => $request->all()]);
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'registration_type' => 'required|in:Individual,Partnership,Company,LLP',
            'registration_date' => 'required|date',
            'business_description' => 'required|string',
            'categories' => 'required|array|min:1',
            'categories.*' => 'string|max:255',
            'status' => 'required|in:pending,under_review,approved,rejected,suspended',
        ]);

        \Log::info('Validation passed for update', ['validated_data' => $validated]);

        try {
            $vendorRegistration->update($validated);
            
            \Log::info('Vendor registration updated successfully', ['id' => $vendorRegistration->id]);

            return redirect()->route('vendor-registrations.index')
                ->with('success', 'Vendor registration updated successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Error updating vendor registration', ['id' => $vendorRegistration->id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update vendor registration: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VendorRegistration $vendorRegistration): RedirectResponse
    {
        $vendorRegistration->delete();

        return redirect()->route('vendor-registrations.index')
            ->with('success', 'Vendor registration deleted successfully.');
    }

    /**
     * Approve the vendor registration
     */
    public function approve(Request $request, VendorRegistration $vendorRegistration): RedirectResponse
    {
        $validated = $request->validate([
            'review_notes' => 'nullable|string',
        ]);

        $vendorRegistration->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes'],
        ]);

        return redirect()->route('vendor-registrations.show', $vendorRegistration)
            ->with('success', 'Vendor registration approved successfully.');
    }

    /**
     * Reject the vendor registration
     */
    public function reject(Request $request, VendorRegistration $vendorRegistration): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $vendorRegistration->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('vendor-registrations.show', $vendorRegistration)
            ->with('success', 'Vendor registration rejected.');
    }

    /**
     * Dashboard view
     */
    public function dashboard(): View
    {
        $stats = [
            'total_registrations' => VendorRegistration::count(),
            'pending_review' => VendorRegistration::where('status', 'pending')->count(),
            'under_review' => VendorRegistration::where('status', 'under_review')->count(),
            'approved' => VendorRegistration::where('status', 'approved')->count(),
            'rejected' => VendorRegistration::where('status', 'rejected')->count(),
        ];

        $recentRegistrations = VendorRegistration::with(['reviewer'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('vendor-registrations.dashboard', compact('stats', 'recentRegistrations'));
    }
}