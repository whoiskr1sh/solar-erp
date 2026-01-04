<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['lead', 'project', 'creator']);

        // Show all documents (active and draft) to authenticated users
        // Hide only deleted documents
        $query->whereIn('status', ['active', 'draft', 'archived']);

        // Filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('lead_id')) {
            $query->where('lead_id', $request->lead_id);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('file_name', 'like', '%' . $request->search . '%');
            });
        }

        $documents = $query->latest()->paginate(15);
        $leads = Lead::all();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        
        $stats = [
            'total' => Document::whereIn('status', ['active', 'draft', 'archived'])->count(),
            'active' => Document::where('status', 'active')->count(),
            'draft' => Document::where('status', 'draft')->count(),
            'archived' => Document::where('status', 'archived')->count(),
            'total_size' => Document::whereIn('status', ['active', 'draft', 'archived'])->sum('file_size'),
            'by_category' => Document::whereIn('status', ['active', 'draft', 'archived'])->selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->get(),
        ];

        return view('documents.index', compact('documents', 'leads', 'projects', 'stats'));
    }

    public function create()
    {
        $leads = Lead::all();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        
        return view('documents.create', compact('leads', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
            'category' => 'required|in:proposal,contract,invoice,quotation,report,presentation,technical_spec,other',
            'status' => 'required|in:draft,active,archived,deleted',
            'lead_id' => 'nullable|exists:leads,id',
            'project_id' => 'nullable|exists:projects,id',
            'tags' => 'nullable|string',
            'expiry_date' => 'nullable|date|after:today',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        $document = Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'category' => $request->category,
            'status' => $request->status,
            'lead_id' => $request->lead_id,
            'project_id' => $request->project_id,
            'created_by' => Auth::id(),
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('documents.show', $document)->with('success', 'Document uploaded successfully!');
    }

    public function show(Document $document)
    {
        $document->load(['lead', 'project', 'creator']);
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        $leads = Lead::all();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        
        return view('documents.edit', compact('document', 'leads', 'projects'));
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:proposal,contract,invoice,quotation,report,presentation,technical_spec,other',
            'status' => 'required|in:draft,active,archived,deleted',
            'lead_id' => 'nullable|exists:leads,id',
            'project_id' => 'nullable|exists:projects,id',
            'tags' => 'nullable|string',
            'expiry_date' => 'nullable|date',
        ]);

        $document->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status,
            'lead_id' => $request->lead_id,
            'project_id' => $request->project_id,
            'tags' => $request->tags ? explode(',', $request->tags) : null,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('documents.show', $document)->with('success', 'Document updated successfully!');
    }

    public function destroy(Document $document)
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document deleted successfully!');
    }

    public function download(Document $document)
    {
        // Allow download for all documents except deleted ones
        if ($document->status === 'deleted' && !auth()->user()->hasRole('SUPER ADMIN')) {
            return redirect()->back()->with('error', 'You do not have permission to download this document!');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    public function preview(Document $document)
    {
        // Allow preview for all documents except deleted ones
        if ($document->status === 'deleted' && !auth()->user()->hasRole('SUPER ADMIN')) {
            return redirect()->back()->with('error', 'You do not have permission to preview this document!');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $filePath = Storage::disk('public')->path($document->file_path);
        $fileType = $document->file_type;

        // For images and PDFs, we can show inline preview
        if (str_starts_with($fileType, 'image/') || $fileType === 'application/pdf') {
            return response()->file($filePath);
        }

        // For other files, force download
        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}
