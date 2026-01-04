<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\HandlesDeletionApproval;

class QuotationController extends Controller
{
    use HandlesDeletionApproval;
    public function index(Request $request)
    {
        // Show only latest quotations (not older revisions)
        $query = Quotation::with(['client', 'project', 'creator'])
            ->where(function($q) {
                $q->where('is_latest', true)
                  ->orWhereNull('parent_quotation_id'); // Include original quotations without revisions
            });

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('quotation_type')) {
            $query->where('quotation_type', $request->quotation_type);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('date_from')) {
            $query->where('quotation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('quotation_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('quotation_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('client', function($clientQuery) use ($request) {
                      $clientQuery->where('name', 'like', '%' . $request->search . '%')
                                  ->orWhere('company', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Get all quotations first to sort by follow-up priority
        $allQuotations = $query->get();
        
        // Separate quotations that need follow-up
        $followUpQuotations = $allQuotations->filter(function($quotation) {
            return $quotation->needsFollowUp();
        })->sortByDesc(function($quotation) {
            // Sort by most urgent (older first)
            $checkDate = $quotation->last_modified_at ?? $quotation->created_at;
            return $checkDate->timestamp;
        });
        
        $otherQuotations = $allQuotations->filter(function($quotation) {
            return !$quotation->needsFollowUp();
        })->sortByDesc('created_at');
        
        // Combine: follow-up quotations first, then others
        $sortedQuotations = $followUpQuotations->merge($otherQuotations);
        
        // Paginate the sorted results
        $currentPage = $request->get('page', 1);
        $perPage = 15;
        $currentItems = $sortedQuotations->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $quotations = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $sortedQuotations->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        $clients = Lead::all();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        
        $stats = [
            'total' => Quotation::count(),
            'draft' => Quotation::where('status', 'draft')->count(),
            'sent' => Quotation::where('status', 'sent')->count(),
            'accepted' => Quotation::where('status', 'accepted')->count(),
            'approved' => Quotation::where('status', 'approved')->count(),
            'pending' => Quotation::whereIn('status', ['draft', 'sent'])->count(),
            'rejected' => Quotation::where('status', 'rejected')->count(),
            'expired' => Quotation::where('valid_until', '<', now())->count(),
            'total_amount' => Quotation::sum('total_amount'),
            'accepted_amount' => Quotation::where('status', 'accepted')->sum('total_amount'),
        ];

        return view('quotations.index', compact('quotations', 'clients', 'projects', 'stats'));
    }

    public function create()
    {
        $clients = Lead::all();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        $products = Product::where('is_active', true)->get();
        
        // Generate quotation number
        $quotationNumber = 'QT-' . str_pad(Quotation::count() + 1, 4, '0', STR_PAD_LEFT);
        
        return view('quotations.create', compact('clients', 'projects', 'products', 'quotationNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quotation_number' => 'required|string|max:50|unique:quotations',
            'quotation_type' => 'required|in:solar_chakki,solar_street_light,commercial,subsidy_quotation',
            'quotation_date' => 'required|date',
            'valid_until' => 'required|date|after:quotation_date',
            'client_id' => 'required|exists:leads,id',
            'project_id' => 'nullable|exists:projects,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        $quotation = Quotation::create([
            'quotation_number' => $request->quotation_number,
            'quotation_type' => $request->quotation_type,
            'quotation_date' => $request->quotation_date,
            'valid_until' => $request->valid_until,
            'client_id' => $request->client_id,
            'project_id' => $request->project_id,
            'subtotal' => $request->subtotal,
            'tax_amount' => $request->tax_amount,
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'terms_conditions' => $request->terms_conditions,
            'follow_up_date' => $request->follow_up_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation created successfully!');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['client', 'project', 'creator', 'revisions']);
        
        // Get all revisions (including original and all revisions)
        $allRevisions = $quotation->allRevisions();
        
        // Get the latest revision
        $latestQuotation = $quotation->getLatestRevision();
        
        return view('quotations.show', compact('quotation', 'allRevisions', 'latestQuotation'));
    }

    public function edit(Quotation $quotation)
    {
        $clients = Lead::all();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        $products = Product::where('is_active', true)->get();
        
        return view('quotations.edit', compact('quotation', 'clients', 'projects', 'products'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        // If only status is being updated
        if ($request->has('status') && !$request->has('quotation_number')) {
            $request->validate([
                'status' => 'required|in:draft,sent,accepted,approved,rejected,expired',
            ]);
            
            $quotation->update(['status' => $request->status]);
            
            return redirect()->route('quotations.index')->with('success', 'Quotation status updated successfully!');
        }
        
        // Full update validation
        $request->validate([
            'quotation_number' => 'required|string|max:50|unique:quotations,quotation_number,' . $quotation->id,
            'quotation_type' => 'required|in:solar_chakki,solar_street_light,commercial,subsidy_quotation',
            'quotation_date' => 'required|date',
            'valid_until' => 'required|date|after:quotation_date',
            'client_id' => 'required|exists:leads,id',
            'project_id' => 'nullable|exists:projects,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:draft,sent,accepted,approved,rejected,expired',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
        ]);

        // Update will automatically set last_modified_at via model boot method
        $quotation->update($request->all());

        return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation updated successfully!');
    }

    public function destroy(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.required' => 'Please provide a reason for deletion.',
            'reason.min' => 'Reason must be at least 10 characters long.',
        ]);

        $modelName = 'Quotation: ' . $quotation->quotation_number;
        return $this->handleDeletion($quotation, $modelName, $validated['reason'], 'quotations.index');
    }

    public function pdf(Quotation $quotation)
    {
        $quotation->load(['client', 'project', 'creator']);
        
        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
            'encoding' => 'UTF-8',
            'enable-local-file-access' => true,
        ]);
        
        return $pdf->download("quotation-{$quotation->quotation_number}.pdf");
    }

    public function preview(Quotation $quotation)
    {
        $quotation->load(['client', 'project', 'creator']);
        return view('quotations.pdf', compact('quotation'));
    }

    public function convertToInvoice(Quotation $quotation)
    {
        // Convert quotation to invoice
        $invoice = \App\Models\Invoice::create([
            'invoice_number' => 'INV-' . substr($quotation->quotation_number, 4),
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'client_id' => $quotation->client_id,
            'project_id' => $quotation->project_id,
            'subtotal' => $quotation->subtotal,
            'tax_amount' => $quotation->tax_amount,
            'total_amount' => $quotation->total_amount,
            'notes' => $quotation->notes,
            'terms_conditions' => $quotation->terms_conditions,
            'created_by' => Auth::id(),
        ]);

        $quotation->update(['status' => 'accepted']);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Quotation converted to invoice successfully!');
    }

    public function sendEmail(Quotation $quotation)
    {
        // Placeholder for email sending functionality
        $quotation->update(['status' => 'sent']);
        
        // If this is a revision and it's being sent, make it the latest
        if ($quotation->is_revision || $quotation->parent_quotation_id) {
            $parentId = $quotation->parent_quotation_id ?? $quotation->id;
            
            // Mark all other revisions as not latest
            Quotation::where(function($q) use ($parentId) {
                $q->where('parent_quotation_id', $parentId)
                  ->orWhere('id', $parentId);
            })->update(['is_latest' => false]);
            
            // Mark this as latest
            $quotation->update(['is_latest' => true]);
        }
        
        return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation sent successfully!');
    }

    /**
     * Create a revision of an existing quotation
     */
    public function createRevision(Quotation $quotation)
    {
        $clients = Lead::all();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        $products = Product::where('is_active', true)->get();
        
        // Get the original quotation (if this is already a revision, get the parent)
        $originalQuotation = $quotation->parent_quotation_id 
            ? Quotation::find($quotation->parent_quotation_id) 
            : $quotation;
        
        // Get the latest revision number
        $latestRevision = Quotation::where('parent_quotation_id', $originalQuotation->id)
            ->orderBy('revision_number', 'desc')
            ->first();
        
        $nextRevisionNumber = $latestRevision ? $latestRevision->revision_number + 1 : 1;
        
        // Generate new quotation number with revision
        $revisionQuotationNumber = $originalQuotation->quotation_number . '-R' . $nextRevisionNumber;
        
        return view('quotations.create-revision', compact('quotation', 'originalQuotation', 'clients', 'projects', 'products', 'nextRevisionNumber', 'revisionQuotationNumber'));
    }

    /**
     * Store a revision of an existing quotation
     */
    public function storeRevision(Request $request, Quotation $quotation)
    {
        $request->validate([
            'quotation_number' => 'required|string|max:50|unique:quotations',
            'quotation_type' => 'required|in:solar_chakki,solar_street_light,commercial,subsidy_quotation',
            'quotation_date' => 'required|date',
            'valid_until' => 'required|date|after:quotation_date',
            'client_id' => 'required|exists:leads,id',
            'project_id' => 'nullable|exists:projects,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        // Get the original quotation
        $originalQuotation = $quotation->parent_quotation_id 
            ? Quotation::find($quotation->parent_quotation_id) 
            : $quotation;

        // Get the latest revision number
        $latestRevision = Quotation::where('parent_quotation_id', $originalQuotation->id)
            ->orderBy('revision_number', 'desc')
            ->first();
        
        $nextRevisionNumber = $latestRevision ? $latestRevision->revision_number + 1 : 1;

        // Mark all previous revisions as not latest
        Quotation::where(function($q) use ($originalQuotation) {
            $q->where('parent_quotation_id', $originalQuotation->id)
              ->orWhere('id', $originalQuotation->id);
        })->update(['is_latest' => false]);

        // Create the revision
        $revision = Quotation::create([
            'quotation_number' => $request->quotation_number,
            'quotation_type' => $request->quotation_type,
            'quotation_date' => $request->quotation_date,
            'valid_until' => $request->valid_until,
            'client_id' => $request->client_id,
            'project_id' => $request->project_id,
            'subtotal' => $request->subtotal,
            'tax_amount' => $request->tax_amount,
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'terms_conditions' => $request->terms_conditions,
            'follow_up_date' => $request->follow_up_date,
            'parent_quotation_id' => $originalQuotation->id,
            'revision_number' => $nextRevisionNumber,
            'is_revision' => true,
            'is_latest' => true,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('quotations.show', $revision)->with('success', 'Quotation revision created successfully!');
    }
}
