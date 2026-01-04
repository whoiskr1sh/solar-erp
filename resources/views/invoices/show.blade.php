@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Invoice #{{ $invoice->invoice_number }}</h1>
            <p class="mt-2 text-gray-600">{{ $invoice->client->name ?? 'Unknown Client' }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Invoice
            </a>
            <a href="{{ route('invoices.pdf', $invoice) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>
            <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Invoices
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Invoice Details -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Invoice Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Number</label>
                    <p class="text-sm text-gray-900">{{ $invoice->invoice_number }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Date</label>
                    <p class="text-sm text-gray-900">{{ $invoice->invoice_date->format('M d, Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                    <p class="text-sm text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoice->status_badge }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                    <p class="text-sm text-gray-900">{{ $invoice->client->name ?? 'Unknown Client' }}</p>
                    @if($invoice->client)
                    <p class="text-xs text-gray-500">{{ $invoice->client->company ?? '' }}</p>
                    @endif
                </div>

                @if($invoice->project)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                    <p class="text-sm text-gray-900">{{ $invoice->project->name }}</p>
                </div>
                @endif
            </div>

            <!-- Invoice Items -->
            <div class="mt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">Invoice Items</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($invoice->items && count($invoice->items) > 0)
                                @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['description'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['quantity'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item['rate'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item['amount'], 2) }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No items found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totals -->
            <div class="mt-6 border-t pt-6">
                <div class="flex justify-end">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                            <span class="text-sm text-gray-900">₹{{ number_format($invoice->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Tax:</span>
                            <span class="text-sm text-gray-900">₹{{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="text-lg font-bold text-gray-900">Total:</span>
                            <span class="text-lg font-bold text-gray-900">₹{{ number_format($invoice->total_amount, 2) }}</span>
                        </div>
                        @if($invoice->paid_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Paid:</span>
                            <span class="text-sm text-green-600">₹{{ number_format($invoice->paid_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Outstanding:</span>
                            <span class="text-sm text-red-600">₹{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($invoice->notes)
            <div class="mt-6">
                <h4 class="text-md font-medium text-gray-900 mb-2">Notes</h4>
                <p class="text-sm text-gray-700">{{ $invoice->notes }}</p>
            </div>
            @endif

            @if($invoice->terms_conditions)
            <div class="mt-6">
                <h4 class="text-md font-medium text-gray-900 mb-2">Terms & Conditions</h4>
                <p class="text-sm text-gray-700">{{ $invoice->terms_conditions }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Actions -->
            @if($invoice->status !== 'paid')
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Actions</h3>
                
                <div class="space-y-3">
                    <button onclick="openPaymentModal()" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Record Payment
                    </button>
                    
                    <form method="POST" action="{{ route('invoices.send-email', $invoice) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Send Email
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Invoice Details -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Details</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                        <p class="text-sm text-gray-900">{{ $invoice->creator->name ?? 'Unknown' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
                        <p class="text-sm text-gray-900">{{ $invoice->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                        <p class="text-sm text-gray-900">{{ $invoice->updated_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($invoice->paid_amount > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment History</h3>
                
                @if($invoice->payment_details)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($invoice->payment_details && is_array($invoice->payment_details) && count($invoice->payment_details) > 0)
                                @foreach($invoice->payment_details as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment['method'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment['transaction_id'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($invoice->paid_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment['paid_by'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ isset($payment['paid_at']) ? \Carbon\Carbon::parse($payment['paid_at'])->format('M d, Y H:i') : ($invoice->paid_date ? $invoice->paid_date->format('M d, Y H:i') : 'N/A') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $payment['notes'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            @elseif($invoice->payment_details && !is_array($invoice->payment_details))
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->payment_details['method'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->payment_details['transaction_id'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($invoice->paid_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->payment_details['paid_by'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ isset($invoice->payment_details['paid_at']) ? \Carbon\Carbon::parse($invoice->payment_details['paid_at'])->format('M d, Y H:i') : ($invoice->paid_date ? $invoice->paid_date->format('M d, Y H:i') : 'N/A') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $invoice->payment_details['notes'] ?? 'N/A' }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No payment history available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @else
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-green-900">Payment Received</p>
                            <p class="text-xs text-green-600">{{ $invoice->paid_date ? $invoice->paid_date->format('M d, Y') : $invoice->updated_at->format('M d, Y') }}</p>
                        </div>
                        <span class="text-sm font-bold text-green-900">₹{{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Payment Modal -->
@if($invoice->status !== 'paid')
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Record Payment</h3>
            
            <form method="POST" action="{{ route('invoices.mark-paid', $invoice) }}">
                @csrf
                
                <div class="mb-4">
                    <label for="paid_amount" class="block text-sm font-medium text-gray-700 mb-2">Payment Amount (₹) *</label>
                    <input type="number" id="paid_amount" name="paid_amount" step="0.01" min="0.01" max="{{ $invoice->total_amount - $invoice->paid_amount }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <p class="text-xs text-gray-500 mt-1">Outstanding: ₹{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</p>
                </div>
                
                <div class="mb-4">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select id="payment_method" name="payment_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Payment Method</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Cash">Cash</option>
                        <option value="UPI">UPI</option>
                        <option value="Card">Card</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-2">Transaction ID</label>
                    <input type="text" id="transaction_id" name="transaction_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Enter transaction ID">
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Payment notes..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePaymentModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                        Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
function openPaymentModal() {
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    // Reset form
    document.querySelector('#paymentModal form').reset();
}

// Close modal when clicking outside
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentModal();
    }
});
</script>
@endsection
