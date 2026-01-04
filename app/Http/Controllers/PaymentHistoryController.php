<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class PaymentHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = collect();
        
        // Get all commissions with payment details
        $commissions = Commission::whereNotNull('payment_details')
            ->where('paid_amount', '>', 0)
            ->with(['project', 'channelPartner', 'createdBy'])
            ->get();
            
        // Get all invoices with payment details
        $invoices = Invoice::whereNotNull('payment_details')
            ->where('paid_amount', '>', 0)
            ->with(['client', 'project', 'creator'])
            ->get();
        
        // Process commission payments
        foreach ($commissions as $commission) {
            $paymentDetails = is_array($commission->payment_details) 
                ? $commission->payment_details 
                : [$commission->payment_details];
                
            foreach ($paymentDetails as $payment) {
                $query->push([
                    'id' => 'comm_' . $commission->id,
                    'type' => 'Commission',
                    'reference' => $commission->commission_number,
                    'project_name' => $commission->project?->name ?? 'N/A',
                    'client_name' => $commission->channelPartner?->name ?? 'N/A',
                    'amount' => $commission->paid_amount,
                    'method' => $payment['method'] ?? 'N/A',
                    'transaction_id' => $payment['transaction_id'] ?? 'N/A',
                    'paid_by' => $payment['paid_by'] ?? 'N/A',
                    'paid_at' => isset($payment['paid_at']) 
                        ? \Carbon\Carbon::parse($payment['paid_at']) 
                        : $commission->paid_date,
                    'notes' => $payment['notes'] ?? 'N/A',
                    'status' => $commission->status,
                    'created_at' => $commission->created_at,
                ]);
            }
        }
        
        // Process invoice payments
        foreach ($invoices as $invoice) {
            $paymentDetails = is_array($invoice->payment_details) 
                ? $invoice->payment_details 
                : [$invoice->payment_details];
                
            foreach ($paymentDetails as $payment) {
                $query->push([
                    'id' => 'inv_' . $invoice->id,
                    'type' => 'Invoice',
                    'reference' => $invoice->invoice_number,
                    'project_name' => $invoice->project?->name ?? 'N/A',
                    'client_name' => $invoice->client?->name ?? 'N/A',
                    'amount' => $invoice->paid_amount,
                    'method' => $payment['method'] ?? 'N/A',
                    'transaction_id' => $payment['transaction_id'] ?? 'N/A',
                    'paid_by' => $payment['paid_by'] ?? 'N/A',
                    'paid_at' => isset($payment['paid_at']) 
                        ? \Carbon\Carbon::parse($payment['paid_at']) 
                        : $invoice->paid_date,
                    'notes' => $payment['notes'] ?? 'N/A',
                    'status' => $invoice->status,
                    'created_at' => $invoice->created_at,
                ]);
            }
        }
        
        // Apply filters
        if ($request->filled('type')) {
            $query = $query->where('type', $request->type);
        }
        
        if ($request->filled('method')) {
            $query = $query->where('method', $request->method);
        }
        
        if ($request->filled('date_from')) {
            $query = $query->where('paid_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query = $query->where('paid_at', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query = $query->filter(function($item) use ($search) {
                return stripos($item['reference'], $search) !== false ||
                       stripos($item['client_name'], $search) !== false ||
                       stripos($item['project_name'], $search) !== false ||
                       stripos($item['transaction_id'], $search) !== false;
            });
        }
        
        // Sort by payment date (newest first)
        $payments = $query->sortByDesc('paid_at')->values();
        
        // Paginate manually
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedPayments = $payments->slice($offset, $perPage)->values();
        
        // Create pagination data
        $total = $payments->count();
        $lastPage = ceil($total / $perPage);
        
        $pagination = [
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'per_page' => $perPage,
            'total' => $total,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total),
        ];
        
        // Calculate stats
        $stats = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'commission_payments' => $payments->where('type', 'Commission')->count(),
            'invoice_payments' => $payments->where('type', 'Invoice')->count(),
            'this_month' => $payments->filter(function($payment) {
                return $payment['paid_at'] && $payment['paid_at']->isCurrentMonth();
            })->sum('amount'),
        ];
        
        // Get unique methods for filter
        $methods = $payments->pluck('method')->unique()->filter()->sort()->values();
        
        return view('payment-history.index', compact('paginatedPayments', 'pagination', 'stats', 'methods'));
    }
    
    public function export(Request $request)
    {
        // Similar logic as index but export to CSV
        $query = collect();
        
        // Get all commissions with payment details
        $commissions = Commission::whereNotNull('payment_details')
            ->where('paid_amount', '>', 0)
            ->with(['project', 'channelPartner', 'createdBy'])
            ->get();
            
        // Get all invoices with payment details
        $invoices = Invoice::whereNotNull('payment_details')
            ->where('paid_amount', '>', 0)
            ->with(['client', 'project', 'creator'])
            ->get();
        
        // Process payments (same logic as index)
        foreach ($commissions as $commission) {
            $paymentDetails = is_array($commission->payment_details) 
                ? $commission->payment_details 
                : [$commission->payment_details];
                
            foreach ($paymentDetails as $payment) {
                $query->push([
                    'Type' => 'Commission',
                    'Reference' => $commission->commission_number,
                    'Project' => $commission->project?->name ?? 'N/A',
                    'Client' => $commission->channelPartner?->name ?? 'N/A',
                    'Amount' => $commission->paid_amount,
                    'Method' => $payment['method'] ?? 'N/A',
                    'Transaction ID' => $payment['transaction_id'] ?? 'N/A',
                    'Paid By' => $payment['paid_by'] ?? 'N/A',
                    'Paid At' => isset($payment['paid_at']) 
                        ? \Carbon\Carbon::parse($payment['paid_at'])->format('Y-m-d H:i:s')
                        : ($commission->paid_date ? $commission->paid_date->format('Y-m-d H:i:s') : 'N/A'),
                    'Notes' => $payment['notes'] ?? 'N/A',
                    'Status' => $commission->status,
                ]);
            }
        }
        
        foreach ($invoices as $invoice) {
            $paymentDetails = is_array($invoice->payment_details) 
                ? $invoice->payment_details 
                : [$invoice->payment_details];
                
            foreach ($paymentDetails as $payment) {
                $query->push([
                    'Type' => 'Invoice',
                    'Reference' => $invoice->invoice_number,
                    'Project' => $invoice->project?->name ?? 'N/A',
                    'Client' => $invoice->client?->name ?? 'N/A',
                    'Amount' => $invoice->paid_amount,
                    'Method' => $payment['method'] ?? 'N/A',
                    'Transaction ID' => $payment['transaction_id'] ?? 'N/A',
                    'Paid By' => $payment['paid_by'] ?? 'N/A',
                    'Paid At' => isset($payment['paid_at']) 
                        ? \Carbon\Carbon::parse($payment['paid_at'])->format('Y-m-d H:i:s')
                        : ($invoice->paid_date ? $invoice->paid_date->format('Y-m-d H:i:s') : 'N/A'),
                    'Notes' => $payment['notes'] ?? 'N/A',
                    'Status' => $invoice->status,
                ]);
            }
        }
        
        // Apply same filters as index
        if ($request->filled('type')) {
            $query = $query->where('Type', $request->type);
        }
        
        if ($request->filled('method')) {
            $query = $query->where('Method', $request->method);
        }
        
        if ($request->filled('date_from')) {
            $query = $query->where('Paid At', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query = $query->where('Paid At', '<=', $request->date_to);
        }
        
        $payments = $query->sortByDesc('Paid At')->values();
        
        // Generate CSV
        $filename = 'payment_history_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            if ($payments->isNotEmpty()) {
                fputcsv($file, array_keys($payments->first()));
            }
            
            // Add data rows
            foreach ($payments as $payment) {
                fputcsv($file, $payment);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}