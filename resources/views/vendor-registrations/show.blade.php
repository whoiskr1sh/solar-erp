@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $vendorRegistration->registration_number }}</h1>
                    <p class="text-gray-600 mt-1">Vendor Registration Details</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('vendor-registrations.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                    @if($vendorRegistration->status === 'pending' || $vendorRegistration->status === 'rejected')
                        <a href="{{ route('vendor-registrations.edit', $vendorRegistration) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
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
                <!-- Company Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Registration Number</label>
                            <p class="text-gray-900 font-medium">{{ $vendorRegistration->registration_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                            <p class="text-gray-900">{{ $vendorRegistration->company_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <p class="text-gray-900">{{ $vendorRegistration->contact_person }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-gray-900">{{ $vendorRegistration->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <p class="text-gray-900">{{ $vendorRegistration->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                            <p class="text-gray-900">
                                @if($vendorRegistration->website)
                                    <a href="{{ $vendorRegistration->website }}" target="_blank" class="text-teal-600 hover:text-teal-800">
                                        {{ $vendorRegistration->website }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Registration Type</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $vendorRegistration->registration_type_badge }}">
                                {{ $vendorRegistration->registration_type }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                            <p class="text-gray-900">{{ $vendorRegistration->registration_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Business Description</label>
                        <p class="text-gray-900">{{ $vendorRegistration->business_description }}</p>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-gray-900">{{ $vendorRegistration->address }}</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <p class="text-gray-900">{{ $vendorRegistration->city }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                <p class="text-gray-900">{{ $vendorRegistration->state }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pincode</label>
                                <p class="text-gray-900">{{ $vendorRegistration->pincode }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <p class="text-gray-900">{{ $vendorRegistration->country }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tax Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tax Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">GST Number</label>
                            <p class="text-gray-900">{{ $vendorRegistration->gst_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PAN Number</label>
                            <p class="text-gray-900">{{ $vendorRegistration->pan_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Business Categories -->
                @if($vendorRegistration->categories && count($vendorRegistration->categories) > 0)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Categories</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($vendorRegistration->categories as $category)
                                <span class="inline-flex px-3 py-1 text-sm bg-teal-100 text-teal-800 rounded-full">
                                    {{ $category }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Documents -->
                @if($vendorRegistration->documents && count($vendorRegistration->documents) > 0)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($vendorRegistration->documents as $document)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                        <span class="text-sm text-gray-700">{{ basename($document) }}</span>
                                    </div>
                                    <a href="{{ Storage::url($document) }}" target="_blank" 
                                       class="text-teal-600 hover:text-teal-800">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                    <div class="text-center">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $vendorRegistration->status_badge }}">
                            {{ ucfirst(str_replace('_', ' ', $vendorRegistration->status)) }}
                        </span>
                        <p class="text-sm text-gray-500 mt-2">
                            Registered {{ $vendorRegistration->days_since_registration }} days ago
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                @if($vendorRegistration->status === 'pending' || $vendorRegistration->status === 'under_review')
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                        <div class="space-y-3">
                            <form method="POST" action="{{ route('vendor-registrations.approve', $vendorRegistration) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i>Approve Registration
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('vendor-registrations.reject', $vendorRegistration) }}" class="w-full">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                                    <textarea name="rejection_reason" rows="3" required
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                              placeholder="Please provide reason for rejection..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Reject Registration
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Registration Details -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration Details</h3>
                    <div class="space-y-3">
                        @if($vendorRegistration->approver)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Processed By</label>
                                <p class="text-gray-900">{{ $vendorRegistration->approver->name }}</p>
                            </div>
                        @endif
                        
                        @if($vendorRegistration->approved_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Processed At</label>
                                <p class="text-gray-900">{{ $vendorRegistration->approved_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($vendorRegistration->rejection_reason)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                                <p class="text-gray-900 text-red-600">{{ $vendorRegistration->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
