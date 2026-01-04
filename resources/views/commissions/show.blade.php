@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Commission Details</h1>
            <p class="text-gray-600">Commission #{{ $commission->commission_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('commissions.edit', $commission) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg">
                Edit Commission
            </a>
            <a href="{{ route('commissions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Commissions
            </a>
        </div>
    </div>

    <!-- Status and Payment Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex space-x-4">
                <div>
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $commission->status_badge }}">
                        {{ ucfirst($commission->status) }}
                    </span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Payment Status:</span>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $commission->payment_status_badge }}">
                        {{ ucfirst($commission->payment_status) }}
                    </span>
                </div>
            </div>
            
            <div class="flex space-x-2">
                @if($commission->status === 'pending')
                <form method="POST" action="{{ route('commissions.approve', $commission) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                        Approve
                    </button>
                </form>
                @endif
                
                @if($commission->status === 'approved' && $commission->payment_status !== 'paid')
                <button onclick="openMarkPaidModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    Mark as Paid
                </button>
                @endif
                
                @if(in_array($commission->status, ['pending', 'approved']))
                <form method="POST" action="{{ route('commissions.cancel', $commission) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm" onclick="return confirm('Are you sure you want to cancel this commission?')">
                        Cancel
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Commission Details -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Commission Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commission Number</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $commission->commission_number }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Channel Partner</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $commission->channelPartner->company_name }}</p>
                    <p class="text-xs text-gray-500">{{ $commission->channelPartner->contact_person }} - {{ $commission->channelPartner->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Reference Type</label>
                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($commission->reference_type) }}</p>
                </div>
                
                @if($commission->reference_number)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Reference Number</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $commission->reference_number }}</p>
                </div>
                @endif
                
                @if($commission->project)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Project</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $commission->project->name }}</p>
                </div>
                @endif
                
                @if($commission->invoice)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Invoice</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $commission->invoice->invoice_number }}</p>
                </div>
                @endif
                
                @if($commission->quotation)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Quotation</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $commission->quotation->quotation_number }}</p>
                </div>
                @endif
                
                @if($commission->due_date)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Due Date</label>
                    <p class="mt-1 text-sm text-gray-900 {{ $commission->is_overdue ? 'text-red-600 font-medium' : ($commission->is_due_soon ? 'text-yellow-600 font-medium' : '') }}">
                        {{ $commission->due_date->format('M d, Y') }}
                        @if($commission->is_overdue)
                        <span class="text-red-600">(Overdue)</span>
                        @elseif($commission->is_due_soon)
                        <span class="text-yellow-600">(Due Soon)</span>
                        @endif
                    </p>
                </div>
                @endif
                
                @if($commission->description)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $commission->description }}</p>
                </div>
                @endif
                
                @if($commission->notes)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $commission->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Financial Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Base Amount</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $commission->formatted_base_amount }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commission Rate</label>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $commission->formatted_commission_rate }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commission Amount</label>
                    <p class="mt-1 text-xl font-bold text-teal-600">{{ $commission->formatted_commission_amount }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Paid Amount</label>
                    <p class="mt-1 text-lg font-semibold text-green-600">{{ $commission->formatted_paid_amount }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pending Amount</label>
                    <p class="mt-1 text-lg font-semibold text-orange-600">{{ $commission->formatted_pending_amount }}</p>
                </div>
                
                @if($commission->paid_date)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Paid Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $commission->paid_date->format('M d, Y') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment History -->
    @if($commission->payment_details && count($commission->payment_details) > 0)
    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment History</h3>
        
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
                    @if($commission->payment_details && is_array($commission->payment_details) && count($commission->payment_details) > 0)
                        @foreach($commission->payment_details as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment['method'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment['transaction_id'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($commission->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment['paid_by'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ isset($payment['paid_at']) ? \Carbon\Carbon::parse($payment['paid_at'])->format('M d, Y H:i') : ($commission->paid_date ? $commission->paid_date->format('M d, Y H:i') : 'N/A') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $payment['notes'] ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    @elseif($commission->payment_details && !is_array($commission->payment_details))
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commission->payment_details['method'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commission->payment_details['transaction_id'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($commission->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $commission->payment_details['paid_by'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ isset($commission->payment_details['paid_at']) ? \Carbon\Carbon::parse($commission->payment_details['paid_at'])->format('M d, Y H:i') : ($commission->paid_date ? $commission->paid_date->format('M d, Y H:i') : 'N/A') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $commission->payment_details['notes'] ?? 'N/A' }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No payment history available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Audit Information -->
    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Audit Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Created By</label>
                <p class="mt-1 text-sm text-gray-900">{{ $commission->creator->name ?? 'N/A' }}</p>
                <p class="text-xs text-gray-500">{{ $commission->created_at->format('M d, Y H:i') }}</p>
            </div>
            
            @if($commission->approver)
            <div>
                <label class="block text-sm font-medium text-gray-700">Approved By</label>
                <p class="mt-1 text-sm text-gray-900">{{ $commission->approver->name }}</p>
                <p class="text-xs text-gray-500">{{ $commission->approved_at->format('M d, Y H:i') }}</p>
            </div>
            @endif
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                <p class="mt-1 text-sm text-gray-900">{{ $commission->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Paid Modal -->
<div id="markPaidModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mark Commission as Paid</h3>
            
            <form method="POST" action="{{ route('commissions.mark-paid', $commission) }}">
                @csrf
                
                <div class="mb-4">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select id="payment_method" name="payment_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Payment Method</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Cash">Cash</option>
                        <option value="UPI">UPI</option>
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
                    <button type="button" onclick="closeMarkPaidModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Mark as Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openMarkPaidModal() {
    document.getElementById('markPaidModal').classList.remove('hidden');
}

function closeMarkPaidModal() {
    document.getElementById('markPaidModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('markPaidModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMarkPaidModal();
    }
});
</script>
@endsection
