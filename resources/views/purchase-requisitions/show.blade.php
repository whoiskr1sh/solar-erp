@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $purchaseRequisition->pr_number }}</h1>
                    <p class="text-gray-600 mt-1">Purchase Requisition Details</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('purchase-requisitions.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                    @if($purchaseRequisition->status === 'draft')
                        <a href="{{ route('purchase-requisitions.edit', $purchaseRequisition) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PR Number</label>
                            <p class="text-gray-900 font-medium">{{ $purchaseRequisition->pr_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                            <p class="text-gray-900">{{ $purchaseRequisition->project?->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $purchaseRequisition->priority_badge }}">
                                {{ ucfirst($purchaseRequisition->priority) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $purchaseRequisition->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $purchaseRequisition->status)) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Requisition Date</label>
                            <p class="text-gray-900">{{ $purchaseRequisition->requisition_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Required Date</label>
                            <p class="text-gray-900 {{ $purchaseRequisition->is_overdue ? 'text-red-600' : '' }}">
                                {{ $purchaseRequisition->required_date->format('M d, Y') }}
                                @if($purchaseRequisition->is_overdue)
                                    <span class="text-xs text-red-600 ml-2">(Overdue)</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                        <p class="text-gray-900">{{ $purchaseRequisition->purpose }}</p>
                    </div>
                    
                    @if($purchaseRequisition->justification)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Justification</label>
                            <p class="text-gray-900">{{ $purchaseRequisition->justification }}</p>
                        </div>
                    @endif
                </div>

                <!-- Items -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($purchaseRequisition->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->item_name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $item->description }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->unit }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">₹{{ number_format($item->estimated_unit_price ?? 0, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">₹{{ number_format($item->estimated_total_price ?? 0, 2) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total Amount:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">₹{{ number_format($purchaseRequisition->estimated_total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($purchaseRequisition->status === 'draft')
                            <form method="POST" action="{{ route('purchase-requisitions.submit', $purchaseRequisition) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-paper-plane mr-2"></i>Submit for Approval
                                </button>
                            </form>
                        @endif
                        
                        @if($purchaseRequisition->status === 'submitted')
                            <form method="POST" action="{{ route('purchase-requisitions.approve', $purchaseRequisition) }}" class="w-full">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Approval Notes</label>
                                    <textarea name="approval_notes" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                              placeholder="Optional approval notes..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i>Approve
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('purchase-requisitions.reject', $purchaseRequisition) }}" class="w-full">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                                    <textarea name="rejection_reason" rows="3" required
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                              placeholder="Please provide reason for rejection..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Reject
                                </button>
                            </form>
                        @endif
                        
                        @if($purchaseRequisition->status === 'approved')
                            <form method="POST" action="{{ route('purchase-requisitions.convert-to-po', $purchaseRequisition) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-exchange-alt mr-2"></i>Convert to PO
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Request Details -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Details</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Requested By</label>
                            <p class="text-gray-900">{{ $purchaseRequisition->requester->name }}</p>
                        </div>
                        
                        @if($purchaseRequisition->approver)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                                <p class="text-gray-900">{{ $purchaseRequisition->approver->name }}</p>
                            </div>
                        @endif
                        
                        @if($purchaseRequisition->approved_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approved At</label>
                                <p class="text-gray-900">{{ $purchaseRequisition->approved_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($purchaseRequisition->approval_notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approval Notes</label>
                                <p class="text-gray-900">{{ $purchaseRequisition->approval_notes }}</p>
                            </div>
                        @endif
                        
                        @if($purchaseRequisition->rejection_reason)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                                <p class="text-gray-900 text-red-600">{{ $purchaseRequisition->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection








