<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'project', 'creator']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('date_from')) {
            $query->where('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('invoice_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('client', function($clientQuery) use ($request) {
                      $clientQuery->where('name', 'like', '%' . $request->search . '%')
                                  ->orWhere('company', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $invoices = $query->latest()->paginate(15);
        $clients = Lead::where('status', 'converted')->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        
        $stats = [
            'total' => Invoice::count(),
            'draft' => Invoice::where('status', 'draft')->count(),
            'sent' => Invoice::where('status', 'sent')->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'overdue' => Invoice::where('status', 'overdue')->count(),
            'total_amount' => Invoice::sum('total_amount'),
            'paid_amount' => Invoice::sum('paid_amount'),
            'outstanding_amount' => Invoice::sum('total_amount') - Invoice::sum('paid_amount'),
        ];

        return view('invoices.index', compact('invoices', 'clients', 'projects', 'stats'));
    }

    public function create()
    {
        $clients = Lead::where('status', 'converted')->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        $products = Product::where('is_active', true)->get();
        
        return view('invoices.create', compact('clients', 'projects', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:50|unique:invoices',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'client_id' => 'required|exists:leads,id',
            'project_id' => 'nullable|exists:projects,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'client_id' => $request->client_id,
            'project_id' => $request->project_id,
            'subtotal' => $request->subtotal,
            'tax_amount' => $request->tax_amount,
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'terms_conditions' => $request->terms_conditions,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'creator']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $clients = Lead::where('status', 'converted')->get();
        $projects = Project::where('status', '!=', 'cancelled')->get();
        $products = Product::where('is_active', true)->get();
        
        return view('invoices.edit', compact('invoice', 'clients', 'projects', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number,' . $invoice->id,
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'client_id' => 'required|exists:leads,id',
            'project_id' => 'nullable|exists:projects,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
        ]);

        $invoice->update($request->all());

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'creator']);
        
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial',
            'encoding' => 'UTF-8'
        ]);
        
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function preview(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'creator']);
        return view('invoices.pdf', compact('invoice'));
    }

    public function markAsPaid(Request $request, Invoice $invoice)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0|max:' . $invoice->total_amount,
            'payment_method' => 'required|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $paymentDetails = [
            'method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'notes' => $request->notes,
            'paid_at' => now()->toISOString(),
            'paid_by' => Auth::user()->name,
        ];

        $existingDetails = $invoice->payment_details ?? [];
        
        // If existing details is not an array, convert it to array format
        if (!is_array($existingDetails)) {
            $existingDetails = [];
        }
        
        // Add new payment details as an array element
        $existingDetails[] = $paymentDetails;

        $invoice->update([
            'paid_amount' => $request->paid_amount,
            'status' => $request->paid_amount >= $invoice->total_amount ? 'paid' : 'sent',
            'payment_details' => $existingDetails,
            'paid_date' => $request->paid_amount >= $invoice->total_amount ? now() : null,
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Payment recorded successfully!');
    }

    public function sendEmail(Invoice $invoice)
    {
        // Placeholder for email sending functionality
        $invoice->update(['status' => 'sent']);
        
        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice sent successfully!');
    }
}
