@extends('layouts.app')

@section('title', 'Delete RFQ')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Delete RFQ</h1>
                        <p class="mt-2 text-gray-600">Confirm RFQ deletion</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('purchase-manager.rfqs.show', $rfq) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>View RFQ
                        </a>
                        <a href="{{ route('purchase-manager.rfqs.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to RFQs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-8">
                <!-- Warning Header -->
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">Delete RFQ</h3>
                        <p class="text-sm text-gray-600">This action cannot be undone</p>
                    </div>
                </div>

                <!-- RFQ Information -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">RFQ Information</h4>
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
                            <label class="block text-sm font-medium text-gray-500 mb-1">RFQ Date</label>
                            <p class="text-gray-900">{{ $rfq->rfq_date ? $rfq->rfq_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Due Date</label>
                            <p class="text-gray-900">{{ $rfq->quotation_due_date ? $rfq->quotation_due_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Estimated Budget</label>
                            <p class="text-gray-900">₹{{ $rfq->estimated_budget ? number_format($rfq->estimated_budget, 2) : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created</label>
                            <p class="text-gray-900">{{ $rfq->created_at ? $rfq->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created By</label>
                            <p class="text-gray-900">{{ $rfq->createdBy->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Deletion Form -->
                <form action="{{ route('purchase-manager.rfqs.destroy', $rfq) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('DELETE')
                    
                    <div>
                        <label for="deletion_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Deletion Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deletion_reason" id="deletion_reason" rows="4" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                            placeholder="Please provide a detailed reason for deleting this RFQ..."></textarea>
                        @error('deletion_reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>This action will permanently delete the RFQ and all associated data</li>
                                        <li>All quotations, evaluations, and vendor responses will be lost</li>
                                        <li>Project references and purchase order links will be affected</li>
                                        <li>This action cannot be undone</li>
                                        <li>Please ensure you have backed up any important data before proceeding</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="confirmation" id="confirmation" required 
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="confirmation" class="ml-2 block text-sm text-gray-900">
                            I understand that this action cannot be undone and I have provided a valid reason for deletion
                        </label>
                    </div>
                    @error('confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('purchase-manager.rfqs.show', $rfq) }}" 
                            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete RFQ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection





