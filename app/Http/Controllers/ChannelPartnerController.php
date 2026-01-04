<?php

namespace App\Http\Controllers;

use App\Models\ChannelPartner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChannelPartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add Spatie permissions middleware here if needed
        // $this->middleware('permission:view channel partners')->only(['index', 'show']);
        // $this->middleware('permission:create channel partners')->only(['create', 'store']);
        // $this->middleware('permission:edit channel partners')->only(['edit', 'update']);
        // $this->middleware('permission:delete channel partners')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = ChannelPartner::with(['assignedUser', 'creator']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('partner_code', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('partner_type') && $request->input('partner_type') !== 'all') {
            $query->where('partner_type', $request->input('partner_type'));
        }

        if ($request->filled('assigned_to') && $request->input('assigned_to') !== 'all') {
            $query->where('assigned_to', $request->input('assigned_to'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        $partners = $query->orderBy('created_at', 'desc')->paginate(10);

        // Calculate stats
        $totalPartners = ChannelPartner::count();
        $activePartners = ChannelPartner::active()->count();
        $pendingPartners = ChannelPartner::pending()->count();
        $suspendedPartners = ChannelPartner::suspended()->count();
        $totalCommission = ChannelPartner::sum('commission_rate');
        $thisMonthPartners = ChannelPartner::whereMonth('created_at', now()->month)->count();

        $stats = [
            'total' => $totalPartners,
            'active' => $activePartners,
            'pending' => $pendingPartners,
            'suspended' => $suspendedPartners,
            'total_commission' => $totalCommission,
            'this_month' => $thisMonthPartners,
        ];

        $users = User::all(); // For 'assigned_to' filter

        return view('channel-partners.index', compact('partners', 'stats', 'users'));
    }

    public function create()
    {
        $users = User::all();
        return view('channel-partners.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:channel_partners',
            'phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:15',
            'pan_number' => 'nullable|string|max:10',
            'website' => 'nullable|url|max:255',
            'partner_type' => ['required', Rule::in(['distributor', 'dealer', 'installer', 'consultant', 'other'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended', 'pending'])],
            'commission_rate' => 'required|numeric|min:0|max:100',
            'credit_limit' => 'required|numeric|min:0',
            'agreement_start_date' => 'nullable|date',
            'agreement_end_date' => 'nullable|date|after:agreement_start_date',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:100',
            'notes' => 'nullable|string',
            'bank_details' => 'nullable|array',
            'documents' => 'nullable|array',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $partner = ChannelPartner::create($validated);

        return redirect()->route('channel-partners.index')->with('success', 'Channel partner created successfully!');
    }

    public function show(ChannelPartner $channelPartner)
    {
        $channelPartner->load(['assignedUser', 'creator', 'leads', 'projects']);
        return view('channel-partners.show', compact('channelPartner'));
    }

    public function edit(ChannelPartner $channelPartner)
    {
        $users = User::all();
        return view('channel-partners.edit', compact('channelPartner', 'users'));
    }

    public function update(Request $request, ChannelPartner $channelPartner)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('channel_partners')->ignore($channelPartner->id)],
            'phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:15',
            'pan_number' => 'nullable|string|max:10',
            'website' => 'nullable|url|max:255',
            'partner_type' => ['required', Rule::in(['distributor', 'dealer', 'installer', 'consultant', 'other'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended', 'pending'])],
            'commission_rate' => 'required|numeric|min:0|max:100',
            'credit_limit' => 'required|numeric|min:0',
            'agreement_start_date' => 'nullable|date',
            'agreement_end_date' => 'nullable|date|after:agreement_start_date',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:100',
            'notes' => 'nullable|string',
            'bank_details' => 'nullable|array',
            'documents' => 'nullable|array',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $channelPartner->update($validated);

        return redirect()->route('channel-partners.index')->with('success', 'Channel partner updated successfully!');
    }

    public function destroy(ChannelPartner $channelPartner)
    {
        $channelPartner->delete();
        return redirect()->route('channel-partners.index')->with('success', 'Channel partner deleted successfully!');
    }

    public function activate(ChannelPartner $channelPartner)
    {
        $channelPartner->activate();
        return back()->with('success', 'Channel partner activated successfully!');
    }

    public function deactivate(ChannelPartner $channelPartner)
    {
        $channelPartner->deactivate();
        return back()->with('success', 'Channel partner deactivated successfully!');
    }

    public function suspend(ChannelPartner $channelPartner)
    {
        $channelPartner->suspend();
        return back()->with('success', 'Channel partner suspended successfully!');
    }

    public function export(Request $request)
    {
        $query = ChannelPartner::with(['assignedUser', 'creator']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('partner_code', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('partner_type') && $request->input('partner_type') !== 'all') {
            $query->where('partner_type', $request->input('partner_type'));
        }

        if ($request->filled('assigned_to') && $request->input('assigned_to') !== 'all') {
            $query->where('assigned_to', $request->input('assigned_to'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date') . ' 23:59:59']);
        }

        $partners = $query->orderBy('created_at', 'desc')->get();

        $filename = 'channel_partners_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = function() use ($partners) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Partner Code', 'Company Name', 'Contact Person', 'Email', 'Phone',
                'Partner Type', 'Status', 'Commission Rate', 'Credit Limit', 'Assigned To', 'Created At'
            ]);

            foreach ($partners as $partner) {
                fputcsv($file, [
                    $partner->id,
                    $partner->partner_code,
                    $partner->company_name,
                    $partner->contact_person,
                    $partner->email,
                    $partner->phone,
                    ucfirst($partner->partner_type),
                    ucfirst($partner->status),
                    $partner->commission_rate . '%',
                    '₹' . number_format($partner->credit_limit, 2),
                    $partner->assignedUser->name ?? 'N/A',
                    $partner->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        // Add success message to session for redirect
        session()->flash('success', 'Channel partners exported successfully!');
        
        return response()->stream($callback, 200, $headers);
    }
}