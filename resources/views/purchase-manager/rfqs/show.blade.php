@extends('layouts.app')

@section('title', 'RFQ Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $rfq->rfq_number ?? 'RFQ-' . $rfq->id }}</h1>
                        <p class="mt-2 text-gray-600">Request for Quotation Details</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('purchase-manager.rfqs.edit', $rfq) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit RFQ
                        </a>
                        <a href="{{ route('purchase-manager.rfqs.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to RFQs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">RFQ Number</label>
                            <p class="text-gray-900">{{ $rfq->rfq_number ?? 'RFQ-' . $rfq->id }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($rfq->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($rfq->status === 'sent') bg-blue-100 text-blue-800
                                @elseif($rfq->status === 'received') bg-green-100 text-green-800
                                @elseif($rfq->status === 'evaluated') bg-purple-100 text-purple-800
                                @elseif($rfq->status === 'awarded') bg-green-100 text-green-800
                                @elseif($rfq->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($rfq->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Vendor</label>
                            <p class="text-gray-900">{{ $rfq->vendor->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Project</label>
                            <p class="text-gray-900">{{ $rfq->project->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Estimated Budget</label>
                            <p class="text-gray-900">₹{{ $rfq->estimated_budget ? number_format($rfq->estimated_budget, 2) : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Important Dates -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Important Dates</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">RFQ Date</label>
                            <p class="text-gray-900">{{ $rfq->rfq_date ? $rfq->rfq_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Quotation Due Date</label>
                            <p class="text-gray-900">{{ $rfq->quotation_due_date ? $rfq->quotation_due_date->format('M d, Y') : 'N/A' }}</p>
                            @if($rfq->quotation_due_date && $rfq->quotation_due_date < now() && !in_array($rfq->status, ['awarded', 'cancelled']))
                                <p class="text-red-600 text-sm">Overdue</p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Valid Until</label>
                            <p class="text-gray-900">{{ $rfq->valid_until ? $rfq->valid_until->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                @if($rfq->description || $rfq->terms_conditions || $rfq->delivery_terms || $rfq->payment_terms)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Terms and Conditions</h3>
                    <div class="space-y-4">
                        @if($rfq->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $rfq->description }}</p>
                        </div>
                        @endif
                        
                        @if($rfq->terms_conditions)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Terms & Conditions</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $rfq->terms_conditions }}</p>
                        </div>
                        @endif
                        
                        @if($rfq->delivery_terms)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Delivery Terms</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $rfq->delivery_terms }}</p>
                        </div>
                        @endif
                        
                        @if($rfq->payment_terms)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Payment Terms</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $rfq->payment_terms }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- RFQ Items -->
                @if($rfq->items && $rfq->items->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">RFQ Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($rfq->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->item_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₹{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center
                            @if($rfq->status === 'draft') bg-gray-100
                            @elseif($rfq->status === 'sent') bg-blue-100
                            @elseif($rfq->status === 'received') bg-green-100
                            @elseif($rfq->status === 'evaluated') bg-purple-100
                            @elseif($rfq->status === 'awarded') bg-green-100
                            @elseif($rfq->status === 'cancelled') bg-red-100
                            @else bg-gray-100 @endif">
                            <i class="fas fa-file-alt text-2xl
                                @if($rfq->status === 'draft') text-gray-600
                                @elseif($rfq->status === 'sent') text-blue-600
                                @elseif($rfq->status === 'received') text-green-600
                                @elseif($rfq->status === 'evaluated') text-purple-600
                                @elseif($rfq->status === 'awarded') text-green-600
                                @elseif($rfq->status === 'cancelled') text-red-600
                                @else text-gray-600 @endif"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900">{{ ucfirst($rfq->status) }}</h4>
                        <p class="text-sm text-gray-600">RFQ Status</p>
                    </div>
                </div>

                <!-- Overdue Alert -->
                @if($rfq->quotation_due_date && $rfq->quotation_due_date < now() && !in_array($rfq->status, ['awarded', 'cancelled']))
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Overdue</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Due date: {{ $rfq->quotation_due_date->format('M d, Y') }}</p>
                                <p>Days overdue: {{ $rfq->quotation_due_date->diffInDays(now()) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('purchase-manager.rfqs.edit', $rfq) }}" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-center block">
                            <i class="fas fa-edit mr-2"></i>Edit RFQ
                        </a>
                        @php
                            $rfqDisplayName = $rfq->rfq_number ?? 'RFQ-' . $rfq->id;
                        @endphp
                        <button onclick="confirmDelete('{{ $rfq->id }}', '{{ $rfqDisplayName }}')" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete RFQ
                        </button>
                    </div>
                </div>

                <!-- RFQ Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">RFQ Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created</label>
                            <p class="text-gray-900">{{ $rfq->created_at ? $rfq->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $rfq->updated_at ? $rfq->updated_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created By</label>
                            <p class="text-gray-900">{{ $rfq->createdBy->name ?? 'N/A' }}</p>
                        </div>
                        @if($rfq->approver)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Approved By</label>
                            <p class="text-gray-900">{{ $rfq->approver->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Delete RFQ</h3>
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete <span id="rfqName" class="font-medium"></span>? This action cannot be undone.</p>
                    
                    <form id="deleteForm" method="POST" class="space-y-4">
                        @csrf
                        @method('DELETE')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deletion Reason</label>
                            <textarea name="deletion_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Please provide a reason for deletion..." required></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="confirmation" id="confirmation" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded" required>
                            <label for="confirmation" class="ml-2 block text-sm text-gray-900">I understand this action cannot be undone</label>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Delete RFQ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(rfqId, rfqName) {
    document.getElementById('rfqName').textContent = rfqName;
    document.getElementById('deleteForm').action = `/purchase-manager/rfqs/${rfqId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteForm').reset();
}
</script>
@endsection

