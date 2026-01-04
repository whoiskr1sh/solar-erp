@extends('layouts.app')

@section('title', 'Contractor Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $contractor->display_name }}</h1>
            <p class="text-gray-600">{{ $contractor->contractor_code }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('contractors.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Contractors
            </a>
            <a href="{{ route('contractors.edit', $contractor) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Edit Contractor
            </a>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $contractor->status_badge }}">
                    {{ ucfirst($contractor->status) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $contractor->availability_badge }}">
                    {{ ucfirst(str_replace('_', ' ', $contractor->availability)) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $contractor->type_badge }}">
                    {{ ucfirst($contractor->contractor_type) }}
                </span>
                @if($contractor->is_verified)
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        Verified
                    </span>
                @else
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                        Unverified
                    </span>
                @endif
            </div>
            <div class="text-sm text-gray-500">
                Created {{ $contractor->created_at->diffForHumans() }}
            </div>
        </div>
        
        @if($contractor->rating)
        <div class="flex items-center space-x-2">
            <span class="text-sm font-medium text-gray-700">Rating:</span>
            <div class="flex items-center">
                <span class="text-yellow-500">{{ str_repeat('★', floor($contractor->rating)) }}</span>
                <span class="text-gray-400">{{ str_repeat('☆', 5 - floor($contractor->rating)) }}</span>
                <span class="ml-2 text-sm font-medium">{{ $contractor->rating }}/5</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Basic Information</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-700">Company:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->company_name ?: 'Individual Contractor' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Contact Person:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->contact_person }}</span>
                </div>
                @if($contractor->designation)
                <div class="flex justify-between">
                    <span class="text-gray-700">Designation:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->display_name }}</span>
                </div>
                @endif
                @if($contractor->email)
                <div class="flex justify-between">
                    <span class="text-gray-700">Email:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->email }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-700">Phone:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->phone }}</span>
                </div>
                @if($contractor->alternate_phone)
                <div class="flex justify-between">
                    <span class="text-gray-700">Alternate Phone:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->alternate_phone }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Address Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Address:</label>
                    <span class="text-gray-900">{{ $contractor->full_address }}</span>
                </div>
                @if($contractor->city)
                <div class="flex justify-between">
                    <span class="text-gray-700">City:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->city }}</span>
                </div>
                @endif
                @if($contractor->state)
                <div class="flex justify-between">
                    <span class="text-gray-700">State:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->state }}</span>
                </div>
                @endif
                @if($contractor->pincode)
                <div class="flex justify-between">
                    <span class="text-gray-700">Pincode:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->pincode }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-700">Country:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->country }}</span>
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Professional Information</h3>
            <div class="space-y-4">
                @if($contractor->specialization)
                <div class="flex justify-between">
                    <span class="text-gray-700">Specialization:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->specialization }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-700">Experience:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->years_of_experience }} years</span>
                </div>
                @if($contractor->skills)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Skills:</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($contractor->skills as $skill)
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-sm rounded">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($contractor->experience_description)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Experience Description:</label>
                    <p class="text-gray-900">{{ $contractor->experience_description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Financial Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Financial Information </h3>
            <div class="space-y-4">
                @if($contractor->hourly_rate)
                <div class="flex justify-between">
                    <span class="text-gray-700">Hourly Rate:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->currency }} {{ $contractor->formatted_hourly_rate }}</span>
                </div>
                @endif
                @if($contractor->daily_rate)
                <div class="flex justify-between">
                    <span class="text-gray-700">Daily Rate:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->currency }} {{ $contractor->formatted_daily_rate }}</span>
                </div>
                @endif
                @if($contractor->monthly_rate)
                <div class="flex justify-between">
                    <span class="text-gray-700">Monthly Rate:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->currency }} {{ $contractor->formatted_monthly_rate }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-700">Currency:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->currency }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Total Projects:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->total_projects }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Total Value:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->currency }} {{ $contractor->formatted_total_value }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($contractor->pan_number || $contractor->gst_number || $contractor->bank_name)
    <!-- Financial Documents -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Financial Documents</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                @if($contractor->pan_number)
                <div class="flex justify-between">
                    <span class="text-gray-700">PAN Number:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->pan_number }}</span>
                </div>
                @endif
                @if($contractor->gst_number)
                <div class="flex justify-between">
                    <span class="text-gray-700">GST Number:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->gst_number }}</span>
                </div>
                @endif
                @if($contractor->aadhar_number)
                <div class="flex justify-between">
                    <span class="text-gray-700">Aadhar Number:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->aadhar_number }}</span>
                </div>
                @endif
            </div>
            @if($contractor->bank_name)
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-700">Bank Name:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->bank_name }}</span>
                </div>
                @if($contractor->account_number)
                <div class="flex justify-between">
                    <span class="text-gray-700">Account Number:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->account_number }}</span>
                </div>
                @endif
                @if($contractor->ifsc_code)
                <div class="flex justify-between">
                    <span class="text-gray-700">IFSC Code:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->ifsc_code }}</span>
                </div>
                @endif
                @if($contractor->branch_name)
                <div class="flex justify-between">
                    <span class="text-gray-700">Branch Name:</span>
                    <span class="font-medium text-gray-900">{{ $contractor->branch_name }}</span>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($contractor->notes)
    <!-- Notes Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Notes</h3>
        <p class="text-gray-900">{{ $contractor->notes }}</p>
    </div>
    @endif
</div>
@endsection
