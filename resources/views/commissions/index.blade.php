@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Commissions</h1>
            <p class="text-gray-600">Manage channel partner commissions and payments</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportCommissions()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Export CSV
            </button>
            <a href="{{ route('commissions.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                Add Commission
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-4">
        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-blue-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Total</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-yellow-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Pending</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['pending'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-green-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Approved</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['approved'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-blue-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Paid</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['paid'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-purple-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Total Amount</p>
                <p class="text-lg font-bold text-gray-900">Rs. {{ number_format($stats['total_amount'], 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-red-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Overdue</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['overdue'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-3 mb-4">
        <form method="GET" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search commissions..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="disputed" {{ request('status') == 'disputed' ? 'selected' : '' }}>Disputed</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Payment Status</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Channel Partner</label>
                <select name="channel_partner_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Partners</option>
                    @foreach($channelPartners as $partner)
                    <option value="{{ $partner->id }}" {{ request('channel_partner_id') == $partner->id ? 'selected' : '' }}>{{ $partner->company_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reference Type</label>
                <select name="reference_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Types</option>
                    <option value="project" {{ request('reference_type') == 'project' ? 'selected' : '' }}>Project</option>
                    <option value="invoice" {{ request('reference_type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                    <option value="quotation" {{ request('reference_type') == 'quotation' ? 'selected' : '' }}>Quotation</option>
                    <option value="manual" {{ request('reference_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <label class="flex items-center">
                    <input type="checkbox" name="overdue" value="true" {{ request('overdue') == 'true' ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <span class="ml-2 text-sm text-gray-700">Overdue Only</span>
                </label>
            </div>
            
            <div class="lg:col-span-6 flex justify-end space-x-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                    Apply Filters
                </button>
                <a href="{{ route('commissions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Commissions Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div>
            <table class="w-full divide-y divide-gray-200 text-xs">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">Commission #</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Channel Partner</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">Reference</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">Base Amount</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Rate</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">Commission</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Status</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6">Payment</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">Due Date</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">Payment History</th>
                        <th class="px-1 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($commissions as $commission)
                    <tr class="hover:bg-gray-50 {{ $commission->is_overdue ? 'bg-red-50' : '' }}">
                        <td class="px-1 py-1">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $commission->commission_number }}">{{ Str::limit($commission->commission_number, 6) }}</div>
                        </td>
                        <td class="px-1 py-1">
                            <div class="text-xs text-gray-900 truncate" title="{{ $commission->channelPartner->company_name }}">{{ Str::limit($commission->channelPartner->company_name, 10) }}</div>
                            <div class="text-xs text-gray-500 truncate" title="{{ $commission->channelPartner->contact_person }}">{{ Str::limit($commission->channelPartner->contact_person, 8) }}</div>
                        </td>
                        <td class="px-1 py-1">
                            <div class="text-xs text-gray-900 truncate" title="{{ $commission->reference_display }}">{{ Str::limit($commission->reference_display, 10) }}</div>
                            @if($commission->reference_number)
                            <div class="text-xs text-gray-500 truncate" title="{{ $commission->reference_number }}">{{ Str::limit($commission->reference_number, 8) }}</div>
                            @endif
                        </td>
                        <td class="px-1 py-1">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $commission->formatted_base_amount }}">{{ Str::limit($commission->formatted_base_amount, 8) }}</div>
                        </td>
                        <td class="px-1 py-1">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $commission->formatted_commission_rate }}">{{ Str::limit($commission->formatted_commission_rate, 6) }}</div>
                        </td>
                        <td class="px-1 py-1">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $commission->formatted_commission_amount }}">{{ Str::limit($commission->formatted_commission_amount, 8) }}</div>
                            @if($commission->paid_amount > 0)
                            <div class="text-xs text-green-600 truncate" title="Paid: {{ $commission->formatted_paid_amount }}">{{ Str::limit('Paid: ' . $commission->formatted_paid_amount, 8) }}</div>
                            @endif
                        </td>
                        <td class="px-1 py-1">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $commission->status_badge }}" title="{{ ucfirst($commission->status) }}">
                                {{ ucfirst($commission->status) }}
                            </span>
                        </td>
                        <td class="px-1 py-1">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $commission->payment_status_badge }}" title="{{ ucfirst($commission->payment_status) }}">
                                {{ ucfirst($commission->payment_status) }}
                            </span>
                        </td>
                        <td class="px-1 py-1">
                            @if($commission->due_date)
                            <div class="text-xs text-gray-900 truncate" title="{{ $commission->due_date->format('M d, Y') }}">{{ $commission->due_date->format('M d, Y') }}</div>
                            @if($commission->is_overdue)
                            <div class="text-xs text-red-600 truncate">Overdue</div>
                            @elseif($commission->is_due_soon)
                            <div class="text-xs text-yellow-600 truncate">Due Soon</div>
                            @endif
                            @else
                            <div class="text-xs text-gray-500 truncate">No Due Date</div>
                            @endif
                        </td>
                        <td class="px-1 py-1">
                            @if($commission->payment_details && $commission->paid_amount > 0)
                                @if(is_array($commission->payment_details))
                                    @foreach($commission->payment_details as $payment)
                                    <div class="text-xs text-gray-900 truncate" title="{{ $payment['method'] ?? 'N/A' }} - {{ $payment['transaction_id'] ?? 'N/A' }}">
                                        {{ Str::limit($payment['method'] ?? 'N/A', 8) }}
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-xs text-gray-900 truncate" title="{{ $commission->payment_details['method'] ?? 'N/A' }} - {{ $commission->payment_details['transaction_id'] ?? 'N/A' }}">
                                        {{ Str::limit($commission->payment_details['method'] ?? 'N/A', 8) }}
                                    </div>
                                @endif
                            @else
                                <div class="text-xs text-gray-500 truncate">No Payment</div>
                            @endif
                        </td>
                        <td class="px-2 py-2 text-xs font-medium">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('commissions.show', $commission) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="View">V</a>
                                <a href="{{ route('commissions.edit', $commission) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs" title="Edit">E</a>
                                @if($commission->status === 'pending')
                                <form method="POST" action="{{ route('commissions.approve', $commission) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" title="Approve">A</button>
                                </form>
                                @endif
                                @if($commission->status === 'approved' && $commission->payment_status !== 'paid')
                                <button onclick="openMarkPaidModal({{ $commission->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="Mark Paid">P</button>
                                @endif
                                @if(in_array($commission->status, ['pending', 'approved']))
                                <form method="POST" action="{{ route('commissions.cancel', $commission) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-1 rounded text-xs" title="Cancel">C</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('commissions.destroy', $commission) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this commission?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Delete">D</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-2 py-6 text-center">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">No commissions found</h3>
                            <p class="text-xs text-gray-600">Get started by creating your first commission.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($commissions->hasPages())
        <div class="px-2 py-2 border-t border-gray-200">
            {{ $commissions->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function exportCommissions() {
    console.log('Export function called');
    
    // Get current filter values with null checks
    const searchElement = document.querySelector('input[name="search"]');
    const statusElement = document.querySelector('select[name="status"]');
    const paymentStatusElement = document.querySelector('select[name="payment_status"]');
    const channelPartnerIdElement = document.querySelector('select[name="channel_partner_id"]');
    const referenceTypeElement = document.querySelector('select[name="reference_type"]');
    const overdueElement = document.querySelector('input[name="overdue"]');
    
    const search = searchElement ? searchElement.value : '';
    const status = statusElement ? statusElement.value : 'all';
    const paymentStatus = paymentStatusElement ? paymentStatusElement.value : 'all';
    const channelPartnerId = channelPartnerIdElement ? channelPartnerIdElement.value : 'all';
    const referenceType = referenceTypeElement ? referenceTypeElement.value : 'all';
    const overdue = overdueElement ? overdueElement.checked : false;
    
    console.log('Filter values:', { search, status, paymentStatus, channelPartnerId, referenceType, overdue });
    
    // Build query string
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (status && status !== 'all') params.append('status', status);
    if (paymentStatus && paymentStatus !== 'all') params.append('payment_status', paymentStatus);
    if (channelPartnerId && channelPartnerId !== 'all') params.append('channel_partner_id', channelPartnerId);
    if (referenceType && referenceType !== 'all') params.append('reference_type', referenceType);
    if (overdue) params.append('overdue', 'true');
    
    // Create export URL
    const exportUrl = '{{ route("commissions.export") }}' + (params.toString() ? '?' + params.toString() : '');
    console.log('Export URL:', exportUrl);
    
    // Create hidden link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'commissions_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    console.log('Download triggered');
    
    // Show success message
    setTimeout(() => {
        console.log('Export completed successfully!');
    }, 1000);
}

// Mark as Paid Modal functionality
let currentCommissionId = null;

function openMarkPaidModal(commissionId) {
    currentCommissionId = commissionId;
    const form = document.getElementById('markPaidForm');
    form.action = `/commissions/${commissionId}/mark-paid`;
    document.getElementById('markPaidModal').classList.remove('hidden');
}

function closeMarkPaidModal() {
    document.getElementById('markPaidModal').classList.add('hidden');
    currentCommissionId = null;
    // Reset form
    document.getElementById('markPaidForm').reset();
}

// Close modal when clicking outside
document.getElementById('markPaidModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMarkPaidModal();
    }
});
</script>

<!-- Mark as Paid Modal -->
<div id="markPaidModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mark Commission as Paid</h3>
            
            <form id="markPaidForm" method="POST" action="">
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
</script>
@endsection