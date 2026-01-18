<?php
namespace App\Http\Controllers;
use App\Models\ClientDocument;
use App\Models\User;
use App\Models\Notification;
use App\Models\DuplicateLeadApproval;
use App\Models\LeadContactView;
use App\Models\LeadCall;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LeadsImport;
use App\Services\BackupService;

class LeadController extends Controller
    /**
     * Show documents for a specific lead
     */
    public function documents($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        // Assuming documents are stored in a related model or in the lead itself
        // If you have a LeadDocument model, you can fetch like below:
        // $documents = LeadDocument::where('lead_id', $leadId)->get();
        // For now, just pass the lead to the view
        return view('leads.documents', compact('lead'));
    }
{
    /**
     * Save selected revised quotation for a lead (AJAX/API)
     */
    public function selectRevisedQuotation(Request $request, $leadId)
    {
        $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
        ]);

        $lead = Lead::find($leadId);
        if (!$lead) {
            return response()->json(['success' => false, 'message' => 'Lead not found.'], 404);
        }

        $lead->selected_revised_quotation_id = $request->quotation_id;
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Revised quotation selected successfully.',
            'selected_revised_quotation_id' => $lead->selected_revised_quotation_id,
        ]);
    }
    // ...existing methods...

    /**
     * Show only new leads (status = 'new')
     */
    public function newLeads(Request $request)
    {
        $leads = \App\Models\Lead::where('lead_stage', 'new')->orderByDesc('created_at')->paginate(20);
        return view('leads.new-leads', compact('leads'));
    }

    // ...existing methods...
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Load contact views for current user to check which contacts they've already viewed
        try {
            $contactViews = LeadContactView::where('user_id', $user->id)
                ->pluck('lead_id')
                ->toArray();
        } catch (\Exception $e) {
            $contactViews = [];
        }

        // Determine view mode for leads listing
        $viewMode = $request->get('view', 'all');
        if (!in_array($viewMode, ['assigned', 'all'])) {
            $viewMode = 'all';
        }

        // Base query used for both list and stats
        // Eager-load latest quotations so we can show quotation info per lead without N+1 queries
        $baseQuery = Lead::with(['assignedUser', 'creator', 'latestQuotations'])
            ->withCount([
                // Track how many revised quotations exist for each lead
                'quotations as revised_quotations_count' => function ($q) {
                    $q->where('is_revision', true);
                },
            ])
            // Exclude reassigned leads - they should only appear in the Reassigned Leads section
            ->where('is_reassigned', false);

        // Filters
        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }
        if ($request->filled('source')) {
            $baseQuery->where('source', $request->source);
        }
        if ($request->filled('assigned_to')) {
            $baseQuery->where('assigned_user_id', $request->assigned_to);
        }
        if ($request->filled('follow_up_name')) {
            $baseQuery->where('name', 'like', '%' . $request->follow_up_name . '%');
        }
        if ($request->filled('follow_up_status')) {
            if ($request->follow_up_status === 'overdue') {
                $baseQuery->where(function($q) {
                    $q->whereIn('status', ['interested', 'partially_interested'])
                      ->whereNotNull('follow_up_date')
                      ->where('follow_up_date', '<', now());
                });
            } else {
                $baseQuery->where('status', $request->follow_up_status);
            }
        }
        if ($request->filled('sub_coordinator')) {
            $baseQuery->where('assigned_user_id', $request->sub_coordinator);
        }
        if ($request->filled('search')) {
            $baseQuery->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%');
            });
        }

        // When viewing Assigned Leads (Viewed Contacts), restrict dataset to viewed leads
        if ($viewMode === 'assigned') {
            $ids = is_array($contactViews) && count($contactViews) > 0 ? $contactViews : [0];
            $baseQuery->whereIn('id', $ids);
        }

        // Clone base query for the listing
        $query = (clone $baseQuery);

        // Order leads by latest first (most recent at top)
        // This ensures all leads are visible and in a consistent order
        $query->latest('updated_at');

        // Show all leads per page (no pagination limit for better visibility)
        // Set very high limit to show all leads on one page
        $perPage = $request->get('per_page', 10000);
        $leads = $query->with('lastUpdater')->paginate($perPage)->appends($request->except('page'));
        $users = User::where('is_active', true)->get();
        
        // Get all coordinators for filter - show all active users
        // This matches the "Assigned To" filter behavior
        $subCoordinators = User::where('is_active', true)->orderBy('name')->get();
        
        // Stats based on the same filtered dataset as the list (including view mode)
        $statsBase = (clone $baseQuery);
        $stats = [
            'total' => (clone $statsBase)->count(),
            'interested' => (clone $statsBase)->where('status', 'interested')->count(),
            'not_interested' => (clone $statsBase)->where('status', 'not_interested')->count(),
            'partially_interested' => (clone $statsBase)->where('status', 'partially_interested')->count(),
            'not_reachable' => (clone $statsBase)->where('status', 'not_reachable')->count(),
            'not_answered' => (clone $statsBase)->where('status', 'not_answered')->count(),
            'needs_followup' => (clone $statsBase)->where(function($query) {
                // INTERESTED and PARTIALLY INTERESTED with overdue follow-up dates
                $query->where(function($q) {
                    $q->whereIn('status', ['interested', 'partially_interested'])
                      ->whereNotNull('follow_up_date')
                      ->where('follow_up_date', '<', now());
                })
                // OR statuses that always need follow-up
                ->orWhereIn('status', ['not_reachable', 'not_answered']);
            })->count(),
        ];

        // Load all contact views with user information to show who viewed each contact
        try {
            $contactViewsWithUsers = LeadContactView::with('user')
                ->get()
                ->groupBy('lead_id')
                ->map(function($views) {
                    return $views->map(function($view) {
                        return [
                            'user_id' => $view->user_id,
                            'user_name' => $view->user->name ?? 'Unknown',
                            'viewed_at' => $view->viewed_at,
                        ];
                    })->first(); // Get the first viewer (or we can show all)
                })
                ->toArray();
        } catch (\Exception $e) {
            $contactViewsWithUsers = [];
        }
        
        // Ensure they are arrays
        $contactViews = is_array($contactViews) ? $contactViews : [];
        $contactViewsWithUsers = is_array($contactViewsWithUsers) ? $contactViewsWithUsers : [];

        // For email blur logic: pass viewedLeadIds to Blade
        $viewedLeadIds = $contactViews;
        return view('leads.index', compact('leads', 'users', 'stats', 'subCoordinators', 'contactViews', 'contactViewsWithUsers', 'viewMode', 'viewedLeadIds'));
    }

    /**
     * Show reassigned leads for the current user
     */
    public function reassigned(Request $request)
    {
        $user = Auth::user();
        $query = Lead::with(['assignedUser', 'creator', 'lastUpdater'])
            ->where('is_reassigned', true)
            ->where('assigned_user_id', $user->id);

        // Filter by date range (optional - no default filter to show all reassigned leads)
        if ($request->filled('days')) {
            $days = (int) $request->days;
            if ($days > 0) {
                $query->where('updated_at', '>=', now()->subDays($days));
            }
            // If days is empty string or 0, show all time
        }

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('company', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Show all reassigned leads per page (no pagination limit for better visibility)
        $perPage = $request->get('per_page', 1000);
        $leads = $query->orderBy('updated_at', 'desc')->paginate($perPage)->appends($request->except('page'));

        // Get stats
        $totalReassigned = Lead::where('is_reassigned', true)
            ->where('assigned_user_id', $user->id)
            ->count();

        $recentReassigned = Lead::where('is_reassigned', true)
            ->where('assigned_user_id', $user->id)
            ->where('updated_at', '>=', now()->subDays(7))
            ->count();

        return view('leads.reassigned', compact('leads', 'totalReassigned', 'recentReassigned'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        return view('leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'consumer_number' => 'required|string|max:100',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|regex:/^[0-9]{6}$/',
            'source' => 'required|in:website,indiamart,justdial,meta_ads,referral,cold_call,other',
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,converted,lost',
            'lead_stage' => 'required|in:new,quotation_sent,site_survey_done,solar_documents_collected,loan_documents_collected',
            'priority' => 'required|in:low,medium,high,urgent',
            'estimated_value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date|after:today',
            'assigned_user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'electricity_bill' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'cancelled_cheque' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhar_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'pan_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'other_document_name' => 'nullable|string|max:255',
            'other_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'passport_photo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'site_photo_pre_installation' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'site_photo_post_installation' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Ensure lead_stage is set to 'new' if not provided (for new leads)
        if (!$request->filled('lead_stage')) {
            $request->merge(['lead_stage' => 'new']);
        }

        // Check if there's already a lead with the same email
        if ($request->filled('email')) {
            $existingLead = Lead::where('email', $request->email)->first();
            
            if ($existingLead) {
                // Check if there's already a pending approval for this email
                $existingApproval = DuplicateLeadApproval::where('existing_lead_id', $existingLead->id)
                    ->where('status', 'pending')
                    ->where('requested_by', Auth::id())
                    ->first();
                
                if ($existingApproval) {
                    return redirect()->back()
                        ->with('info', 'A duplicate lead approval request for this email is already pending. Please wait for Sales Manager approval.')
                        ->withInput();
                }
                
                // Create duplicate lead approval request
                // Only store fillable fields in lead_data (exclude system fields)
                $leadData = $request->only([
                    'name', 'email', 'phone', 'consumer_number', 'company', 'address', 'city', 'state', 'pincode', 'source', 
                    'status', 'lead_stage', 'priority', 'notes', 'estimated_value', 
                    'expected_close_date', 'follow_up_date', 'follow_up_notes', 
                    'assigned_user_id', 'channel_partner_id'
                ]);
                
                $approval = DuplicateLeadApproval::create([
                    'requested_by' => Auth::id(),
                    'existing_lead_id' => $existingLead->id,
                    'lead_data' => $leadData,
                    'reason' => $request->input('duplicate_reason', 'Duplicate lead with same email address'),
                    'status' => 'pending',
                ]);
                
                // Notify sales managers
                $salesManagers = User::whereHas('roles', function($query) {
                    $query->where('name', 'SALES MANAGER');
                })->get();
                
                foreach ($salesManagers as $manager) {
                    Notification::create([
                        'user_id' => $manager->id,
                        'title' => 'Duplicate Lead Approval Required',
                        'message' => "A duplicate lead request for {$request->name} ({$request->email}) requires your approval. An existing lead with the same email already exists.",
                        'type' => 'approval',
                        'data' => [
                            'approval_id' => $approval->id,
                            'approval_type' => 'duplicate_lead',
                            'lead_name' => $request->name,
                            'existing_lead_id' => $existingLead->id,
                        ]
                    ]);
                }
                
                return redirect()->route('leads.index')
                    ->with('info', 'A lead with this email already exists. Your request has been sent to Sales Manager for approval. You will be notified once it\'s approved or rejected.');
            }
        }

        $leadData = $request->except([
            'electricity_bill',
            'cancelled_cheque',
            'aadhar_document',
            'pan_document',
            'other_document',
            'passport_photo',
            'site_photo_pre_installation',
            'site_photo_post_installation',
            'status', // Remove status from request data
        ]);
        $leadData['created_by'] = Auth::id();
        // Always set status to a valid enum value
        $leadData['status'] = 'new';
        $leadData['lead_stage'] = 'new';

        // Handle mandatory attachments
        if ($request->hasFile('electricity_bill')) {
            $leadData['electricity_bill_path'] = $request->file('electricity_bill')->store('leads/electricity-bills', 'public');
        }

        if ($request->hasFile('cancelled_cheque')) {
            $leadData['cancelled_cheque_path'] = $request->file('cancelled_cheque')->store('leads/cancelled-cheques', 'public');
        }

        // Optional documentation uploads
        if ($request->hasFile('aadhar_document')) {
            $leadData['aadhar_path'] = $request->file('aadhar_document')->store('leads/documents/aadhar', 'public');
        }

        if ($request->hasFile('pan_document')) {
            $leadData['pan_path'] = $request->file('pan_document')->store('leads/documents/pan', 'public');
        }

        if ($request->hasFile('other_document')) {
            $leadData['other_document_path'] = $request->file('other_document')->store('leads/documents/other', 'public');
        }

        if ($request->hasFile('passport_photo')) {
            $leadData['passport_photo_path'] = $request->file('passport_photo')->store('leads/documents/passport-photo', 'public');
        }

        // Site photos
        if ($request->hasFile('site_photo_pre_installation')) {
            $leadData['site_photo_pre_installation_path'] = $request->file('site_photo_pre_installation')->store('leads/site-photos/pre-installation', 'public');
        }

        if ($request->hasFile('site_photo_post_installation')) {
            $leadData['site_photo_post_installation_path'] = $request->file('site_photo_post_installation')->store('leads/site-photos/post-installation', 'public');
        }
        
        // If status is INTERESTED or PARTIALLY INTERESTED, set follow-up date to 10 days from now
        // Only do this if $request->status is present and valid, but do NOT overwrite $leadData['status']
        if (isset($request->status) && in_array($request->status, ['interested', 'partially_interested'])) {
            $leadData['follow_up_date'] = now()->addDays(10);
            $leadData['last_follow_up_at'] = now();
        }
        
        $lead = Lead::create($leadData);

        // Create notification for assigned user (if any)
        if ($lead->assigned_user_id) {
            Notification::create([
                'user_id' => $lead->assigned_user_id,
                'title' => 'New Lead Assigned',
                'message' => "You have been assigned a new lead: {$lead->name} ({$lead->company})",
                'type' => 'lead',
                'data' => [
                    'lead_id' => $lead->id,
                    'lead_name' => $lead->name,
                    'assigned_by' => Auth::user()->name,
                ]
            ]);
        }

        // Create notification for sales managers
        $salesManagers = User::whereHas('roles', function($query) {
            $query->where('name', 'SALES MANAGER');
        })->get();

        foreach ($salesManagers as $manager) {
            if ($manager->id !== Auth::id() && $manager->id !== $lead->assigned_user_id) {
                Notification::create([
                    'user_id' => $manager->id,
                    'title' => 'New Lead Created',
                    'message' => "A new lead has been created: {$lead->name} ({$lead->company})",
                    'type' => 'lead',
                    'data' => [
                        'lead_id' => $lead->id,
                        'lead_name' => $lead->name,
                        'created_by' => Auth::user()->name,
                    ]
                ]);
            }
        }

        return redirect()->route('leads.index')->with('success', 'Lead created successfully!');
    }

    public function show(Lead $lead)
    {
        $lead->load(['assignedUser', 'creator', 'lastUpdater', 'projects', 'invoices']);
        
        // Load latest quotations (only show the latest revision of each quotation)
        $quotations = Quotation::where('client_id', $lead->id)
            ->where(function($q) {
                $q->where('is_latest', true)
                  ->orWhereNull('parent_quotation_id');
            })
            ->with(['project', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('leads.show', compact('lead', 'quotations'));
    }

    public function edit(Lead $lead)
    {
        $users = User::where('is_active', true)->get();
        return view('leads.edit', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'consumer_number' => 'required|string|max:100',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|regex:/^[0-9]{6}$/',
            'source' => 'required|in:website,indiamart,justdial,meta_ads,referral,cold_call,other',
            'status' => 'required|in:interested,not_interested,partially_interested,not_reachable,not_answered',
            'lead_stage' => 'nullable|in:quotation_sent,site_survey_done,solar_documents_collected,loan_documents_collected',
            'priority' => 'required|in:low,medium,high,urgent',
            'estimated_value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date',
            'follow_up_date' => 'nullable|date',
            'follow_up_notes' => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'electricity_bill' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'cancelled_cheque' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'aadhar_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'pan_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'other_document_name' => 'nullable|string|max:255',
            'other_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'passport_photo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'site_photo_pre_installation' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'site_photo_post_installation' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $oldStatus = $lead->status;
        $updateData = [
            ...$request->except([
                'electricity_bill',
                'cancelled_cheque',
                'aadhar_document',
                'pan_document',
                'other_document',
                'passport_photo',
                'site_photo_pre_installation',
                'site_photo_post_installation',
            ]),
            'last_updated_by' => Auth::id(),
        ];

        // Track calls - increment call_count when status changes
        $statusChanged = $oldStatus !== $request->status;
        if ($statusChanged) {
            $updateData['call_count'] = ($lead->call_count ?? 0) + 1;
            
            // Log the call with caller and assigned user information
            // This ensures incentives go to assigned_user_id (Person B), not caller (Person A)
            \App\Models\LeadCall::create([
                'lead_id' => $lead->id,
                'caller_id' => Auth::id(), // Person A - who made the call
                'assigned_user_id' => $lead->assigned_user_id, // Person B - who gets the incentive
                'status' => $request->status,
                'notes' => $request->follow_up_notes ?? null,
                'called_at' => now(),
            ]);
        }

        // If status is INTERESTED or PARTIALLY INTERESTED, set follow-up date to 10 days from now
        if (in_array($request->status, ['interested', 'partially_interested'])) {
            $updateData['follow_up_date'] = now()->addDays(10);
            $updateData['last_follow_up_at'] = now();
        }
        // If status is one that needs follow-up (not_reachable, not_answered), update last_follow_up_at
        elseif (in_array($request->status, ['not_reachable', 'not_answered'])) {
            $updateData['last_follow_up_at'] = now();
        }

        // Handle optional attachment updates
        if ($request->hasFile('electricity_bill')) {
            $updateData['electricity_bill_path'] = $request->file('electricity_bill')->store('leads/electricity-bills', 'public');
        }

        if ($request->hasFile('cancelled_cheque')) {
            $updateData['cancelled_cheque_path'] = $request->file('cancelled_cheque')->store('leads/cancelled-cheques', 'public');
        }

        if ($request->hasFile('aadhar_document')) {
            $updateData['aadhar_path'] = $request->file('aadhar_document')->store('leads/documents/aadhar', 'public');
        }

        if ($request->hasFile('pan_document')) {
            $updateData['pan_path'] = $request->file('pan_document')->store('leads/documents/pan', 'public');
        }

        if ($request->hasFile('other_document')) {
            $updateData['other_document_path'] = $request->file('other_document')->store('leads/documents/other', 'public');
        }

        if ($request->hasFile('passport_photo')) {
            $updateData['passport_photo_path'] = $request->file('passport_photo')->store('leads/documents/passport-photo', 'public');
        }

        if ($request->hasFile('site_photo_pre_installation')) {
            $updateData['site_photo_pre_installation_path'] = $request->file('site_photo_pre_installation')->store('leads/site-photos/pre-installation', 'public');
        }

        if ($request->hasFile('site_photo_post_installation')) {
            $updateData['site_photo_post_installation_path'] = $request->file('site_photo_post_installation')->store('leads/site-photos/post-installation', 'public');
        }

        $lead->update($updateData);

        return redirect()->route('leads.index')->with('success', 'Lead updated successfully!');
    }

    public function destroy(Request $request, Lead $lead)
    {
        $user = Auth::user();
        
        // Check if lead is "not_reachable" - only allow deletion for not_reachable leads
        if ($lead->status !== 'not_reachable') {
            return redirect()->back()->with('error', 'Only leads with status "Not Reachable" can be deleted.');
        }
        
        // If user is SUPER ADMIN, delete directly with backup
        if ($user->hasRole('SUPER ADMIN')) {
            try {
                $reason = $request->input('reason', 'Deleted by Super Admin');
                $leadId = $lead->id;
                
                // Create backup BEFORE deleting the lead (use transaction)
                \DB::beginTransaction();
                
                $backupService = new BackupService();
                $backupId = $backupService->createBackup($lead, $user->id, $user->id, $reason);
                
                // Also create lead-specific backup for backward compatibility
                $this->createLeadBackup($lead, $user->id, $user->id, $reason);
                
                // Delete the lead
        $lead->delete();
                
                \DB::commit();
                
                \Log::info('Lead deleted and backed up by SUPER ADMIN', [
                    'lead_id' => $leadId,
                    'backup_id' => $backupId,
                    'deleted_by' => $user->id,
                    'reason' => $reason
                ]);
                
                return redirect()->route('leads.index')->with('success', 'Lead deleted successfully! The lead has been backed up and can be restored from Backups within 40 days.');
            } catch (\Exception $e) {
                \DB::rollBack();
                
                \Log::error('Failed to delete lead or create backup', [
                    'lead_id' => $lead->id ?? 'unknown',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->back()->with('error', 'Failed to delete lead: ' . $e->getMessage());
            }
        }

        // For all other roles, check if it's a "higher lead" (high/urgent priority or high estimated value)
        $isHigherLead = in_array($lead->priority, ['high', 'urgent']) || 
                        ($lead->estimated_value && $lead->estimated_value >= 100000); // Consider 1 lakh+ as high value
        
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.required' => 'Please provide a reason for deletion.',
            'reason.min' => 'Reason must be at least 10 characters long.',
        ]);

        // Check if already has pending approval
        $existingApproval = \App\Models\DeleteApproval::where('model_type', Lead::class)
            ->where('model_id', $lead->id)
            ->where('status', 'pending')
            ->first();

        if ($existingApproval) {
            $approverRole = $isHigherLead ? 'Admin' : 'Sales Manager';
            return redirect()->back()->with('info', 'A deletion request for this lead is already pending approval from ' . $approverRole . '.');
        }

        // Store model data before deletion
        $modelData = $lead->toArray();

        $deleteApproval = \App\Models\DeleteApproval::create([
            'model_type' => Lead::class,
            'model_id' => $lead->id,
            'requested_by' => $user->id,
            'model_name' => 'Lead: ' . $lead->name . ' (' . ($lead->company ?: 'No company') . ')',
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
            'model_data' => $modelData,
        ]);

        // Send email to appropriate approver
        try {
            if ($isHigherLead) {
                // For higher leads, send to SUPER ADMIN
                $approvers = User::whereHas('roles', function($query) {
                    $query->where('name', 'SUPER ADMIN');
                })->get();
            } else {
                // For regular not_reachable leads, send to SALES MANAGER
                $approvers = User::whereHas('roles', function($query) {
                    $query->where('name', 'SALES MANAGER');
                })->get();
            }

            foreach ($approvers as $approver) {
                \Mail::to($approver->email)->send(new \App\Mail\DeleteApprovalNotification($deleteApproval));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send delete approval email', [
                'error' => $e->getMessage(),
                'delete_approval_id' => $deleteApproval->id,
            ]);
        }

        $approverRole = $isHigherLead ? 'Admin' : 'Sales Manager';
        return redirect()->back()->with('success', 'Delete request sent to ' . $approverRole . ' for approval.');
    }

    /**
     * Create backup of lead before deletion
     */
    private function createLeadBackup(Lead $lead, $deletedBy, $approvedBy, $reason = null)
    {
        // Don't use nested transactions - let the caller handle the transaction
        try {
            $backupId = \DB::table('lead_backups')->insertGetId([
                'original_lead_id' => $lead->id,
                'name' => $lead->name ?? '',
                'email' => $lead->email,
                'phone' => $lead->phone ?? '',
                'company' => $lead->company,
                'address' => $lead->address,
                'source' => $lead->source ?? 'other',
                'status' => $lead->status ?? 'interested',
                'priority' => $lead->priority ?? 'medium',
                'notes' => $lead->notes,
                'estimated_value' => $lead->estimated_value,
                'expected_close_date' => $lead->expected_close_date,
                'assigned_user_id' => $lead->assigned_user_id,
                'channel_partner_id' => $lead->channel_partner_id,
                'created_by' => $lead->created_by,
                'deleted_by' => $deletedBy,
                'approved_by' => $approvedBy,
                'deleted_at' => now(),
                'expires_at' => now()->addDays(40),
                'deletion_reason' => $reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            \Log::info('Lead backup created successfully', [
                'lead_id' => $lead->id,
                'backup_id' => $backupId,
                'backup_created' => true,
                'deleted_by' => $deletedBy
            ]);
            
            return $backupId;
        } catch (\Exception $e) {
            \Log::error('Error creating lead backup', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Lookup lead by email
     */
    public function lookupByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $lead = Lead::where('email', $request->email)->first();

        if ($lead) {
            return redirect()->route('leads.show', $lead)->with('info', 'Lead found by email.');
        }

        // Also check in backups
        $backup = \DB::table('lead_backups')
            ->where('email', $request->email)
            ->where('expires_at', '>', now())
            ->orderBy('deleted_at', 'desc')
            ->first();

        if ($backup) {
            return redirect()->route('leads.index')
                ->with('info', 'Lead was found in backups (deleted ' . \Carbon\Carbon::parse($backup->deleted_at)->diffForHumans() . '). Backup expires on ' . \Carbon\Carbon::parse($backup->expires_at)->format('M d, Y') . '.');
        }

        return redirect()->route('leads.index')->with('error', 'No lead found with this email address.');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:interested,not_interested,partially_interested,not_reachable,not_answered',
        ]);

        $oldStatus = $lead->status;
        
        // Track calls - increment call_count when status changes
        $statusChanged = $oldStatus !== $request->status;
        $updateData = [
            'status' => $request->status,
            'last_updated_by' => Auth::id(),
        ];

        if ($statusChanged) {
            $updateData['call_count'] = ($lead->call_count ?? 0) + 1;
            
            // Log the call with caller and assigned user information
            // This ensures incentives go to assigned_user_id (Person B), not caller (Person A)
            \App\Models\LeadCall::create([
                'lead_id' => $lead->id,
                'caller_id' => Auth::id(), // Person A - who made the call
                'assigned_user_id' => $lead->assigned_user_id, // Person B - who gets the incentive
                'status' => $request->status,
                'notes' => $request->notes ?? null,
                'called_at' => now(),
            ]);
        }

        // If status is INTERESTED or PARTIALLY INTERESTED, set follow-up date to 10 days from now
        if (in_array($request->status, ['interested', 'partially_interested'])) {
            $updateData['follow_up_date'] = now()->addDays(10);
            $updateData['last_follow_up_at'] = now();
        }
        // If status is one that needs follow-up (not_reachable, not_answered), update last_follow_up_at
        elseif (in_array($request->status, ['not_reachable', 'not_answered'])) {
            $updateData['last_follow_up_at'] = now();
        }

        $lead->update($updateData);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead status updated successfully!',
                'lead' => $lead->fresh()
            ]);
        }
        return redirect()->back()->with('success', 'Lead status updated successfully!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            Excel::import(new LeadsImport, $request->file('excel_file'));
            return redirect()->route('leads.index')->with('success', 'Leads imported successfully!');
        } catch (\Exception $e) {
            return redirect()->route('leads.index')->with('error', 'Error importing leads: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $csvContent = "Name,Phone,Email,Company,Source,Status,Priority,Address,City,State,Pincode,Industry,Estimated Value,Expected Close Date,Notes\n";
        $csvContent .= "John Doe,9876543210,john@example.com,ABC Corp,website,new,medium,123 Main Street,Mumbai,Maharashtra,400001,residential,50000,2024-12-31,Interested in solar installation\n";
        $csvContent .= "Jane Smith,9876543211,jane@example.com,XYZ Ltd,referral,contacted,high,456 Park Avenue,Delhi,Delhi,110001,commercial,100000,2024-11-30,Commercial project inquiry\n";

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads_template.csv"',
        ]);
    }

    public function revealContact(Lead $lead)
    {
        $user = Auth::user();
            Log::info('RevealContact called', [
                'user_id' => $user->id,
                'lead_id' => $lead->id,
                'expects_json' => request()->expectsJson(),
                'ajax' => request()->ajax(),
                'headers' => request()->header(),
            ]);
    {
        $user = Auth::user();
        // Superadmin can always view
        if ($user->hasRole('SUPER ADMIN')) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'message' => 'Contact details revealed.'
                ]);
            }
            return redirect()->back()->with('success', 'Contact details revealed.');
        }

        // If lead is assigned to another user, block reveal
        if ($lead->assigned_user_id !== null && $lead->assigned_user_id !== $user->id) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'This lead is already assigned to another user.'], 403);
            }
            abort(403, 'This lead is already assigned to another user.');
        }


        // First-click wins for assignment, lock unless admin
        if ($lead->assigned_user_id === null) {
            $lead->assigned_user_id = $user->id;
            $lead->last_updated_by = $user->id;
            $lead->save();
        }

        // Record that this user has viewed this lead's contact (for UI and stats)
        \App\Models\LeadContactView::firstOrCreate([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
        ], [
            'viewed_at' => now(),
        ]);

        // Enforce backend authorization: only assigned user can view
        if ($lead->assigned_user_id !== $user->id) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'You are not authorized to view this contact/email.'], 403);
            }
            abort(403, 'You are not authorized to view this contact/email.');
        }

        // If AJAX, return JSON with success
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'message' => 'Contact details revealed.'
            ]);
        }
        // Otherwise, redirect
        return redirect()->back()->with('success', 'Contact details revealed.');
    }
    }

    public function export()
    {
        return Excel::download(new \App\Exports\LeadsExport, 'leads_export_' . now()->format('Y-m-d') . '.xlsx');
    }
}
