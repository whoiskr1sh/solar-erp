@extends('layouts.app')

@section('title', 'Vendor Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $vendor->name }}</h1>
                        <p class="mt-2 text-gray-600">{{ $vendor->company_name ?? 'Vendor Details' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('purchase-manager.vendors.edit', $vendor) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Vendor
                        </a>
                        <a href="{{ route('purchase-manager.vendors.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Vendors
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
                            <label class="block text-sm font-medium text-gray-500 mb-1">Vendor Name</label>
                            <p class="text-gray-900">{{ $vendor->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Company Name</label>
                            <p class="text-gray-900">{{ $vendor->company_name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email Address</label>
                            <p class="text-gray-900">{{ $vendor->email }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
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
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                            <p class="text-gray-900">{{ $vendor->address ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">City</label>
                            <p class="text-gray-900">{{ $vendor->city ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">State</label>
                            <p class="text-gray-900">{{ $vendor->state ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Pincode</label>
                            <p class="text-gray-900">{{ $vendor->pincode ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Country</label>
                            <p class="text-gray-900">{{ $vendor->country ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Business Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">GST Number</label>
                            <p class="text-gray-900">{{ $vendor->gst_number ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">PAN Number</label>
                            <p class="text-gray-900">{{ $vendor->pan_number ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Bank Name</label>
                            <p class="text-gray-900">{{ $vendor->bank_name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Account Number</label>
                            <p class="text-gray-900">{{ $vendor->account_number ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">IFSC Code</label>
                            <p class="text-gray-900">{{ $vendor->ifsc_code ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Credit Limit</label>
                            <p class="text-gray-900">₹{{ $vendor->credit_limit ? number_format($vendor->credit_limit, 2) : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                @if($vendor->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                        <p class="text-gray-900 whitespace-pre-line">{{ $vendor->notes }}</p>
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
                            @if($vendor->status === 'active') bg-green-100
                            @elseif($vendor->status === 'pending') bg-yellow-100
                            @elseif($vendor->status === 'inactive') bg-red-100
                            @elseif($vendor->status === 'suspended') bg-orange-100
                            @else bg-gray-100 @endif">
                            <i class="fas fa-user text-2xl
                                @if($vendor->status === 'active') text-green-600
                                @elseif($vendor->status === 'pending') text-yellow-600
                                @elseif($vendor->status === 'inactive') text-red-600
                                @elseif($vendor->status === 'suspended') text-orange-600
                                @else text-gray-600 @endif"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900">{{ ucfirst($vendor->status) }}</h4>
                        <p class="text-sm text-gray-600">Vendor Status</p>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('purchase-manager.vendors.edit', $vendor) }}" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-center block">
                            <i class="fas fa-edit mr-2"></i>Edit Vendor
                        </a>
                        <button onclick="confirmDelete('{{ $vendor->id }}', '{{ $vendor->name }}')" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete Vendor
                        </button>
                    </div>
                </div>

                <!-- Vendor Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Vendor Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created</label>
                            <p class="text-gray-900">{{ $vendor->created_at ? $vendor->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $vendor->updated_at ? $vendor->updated_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created By</label>
                            <p class="text-gray-900">{{ $vendor->createdBy->name ?? 'N/A' }}</p>
                        </div>
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Vendor</h3>
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete <span id="vendorName" class="font-medium"></span>? This action cannot be undone.</p>
                    
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
                                Delete Vendor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(vendorId, vendorName) {
    document.getElementById('vendorName').textContent = vendorName;
    document.getElementById('deleteForm').action = `/purchase-manager/vendors/${vendorId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteForm').reset();
}
</script>
@endsection





