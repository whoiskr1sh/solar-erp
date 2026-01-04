@extends('layouts.app')

@section('title', 'Delete Vendor')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Delete Vendor</h1>
                        <p class="mt-2 text-gray-600">Confirm vendor deletion</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('purchase-manager.vendors.show', $vendor) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>View Vendor
                        </a>
                        <a href="{{ route('purchase-manager.vendors.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Vendors
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
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Delete Vendor</h3>
                        <p class="text-sm text-gray-600">This action cannot be undone</p>
                    </div>
                </div>

                <!-- Vendor Information -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Vendor Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Vendor Name</label>
                            <p class="text-gray-900">{{ $vendor->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Company Name</label>
                            <p class="text-gray-900">{{ $vendor->company_name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-900">{{ $vendor->email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                            <p class="text-gray-900">{{ $vendor->phone ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($vendor->category === 'supplier') bg-blue-100 text-blue-800
                                @elseif($vendor->category === 'contractor') bg-green-100 text-green-800
                                @elseif($vendor->category === 'service_provider') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $vendor->category ?? 'N/A')) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($vendor->status === 'active') bg-green-100 text-green-800
                                @elseif($vendor->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($vendor->status === 'inactive') bg-red-100 text-red-800
                                @elseif($vendor->status === 'suspended') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($vendor->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created</label>
                            <p class="text-gray-900">{{ $vendor->created_at ? $vendor->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created By</label>
                            <p class="text-gray-900">{{ $vendor->createdBy->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Deletion Form -->
                <form action="{{ route('purchase-manager.vendors.destroy', $vendor) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('DELETE')
                    
                    <div>
                        <label for="deletion_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Deletion Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deletion_reason" id="deletion_reason" rows="4" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                            placeholder="Please provide a detailed reason for deleting this vendor..."></textarea>
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
                                        <li>This action will permanently delete the vendor and all associated data</li>
                                        <li>All purchase orders, invoices, and transactions related to this vendor will be affected</li>
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
                        <a href="{{ route('purchase-manager.vendors.show', $vendor) }}" 
                            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete Vendor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection





