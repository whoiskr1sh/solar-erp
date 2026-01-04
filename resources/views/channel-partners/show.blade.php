@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $channelPartner->company_name }}</h1>
            <p class="text-gray-600">Channel Partner Details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('channel-partners.edit', $channelPartner) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Edit Partner
            </a>
            <a href="{{ route('channel-partners.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Partners
            </a>
        </div>
    </div>

    <!-- Status Actions -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $channelPartner->status_badge }}">
                    {{ ucfirst($channelPartner->status) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $channelPartner->partner_type_badge }}">
                    {{ ucfirst($channelPartner->partner_type) }}
                </span>
            </div>
            <div class="flex space-x-2">
                @if($channelPartner->status === 'active')
                <form method="POST" action="{{ route('channel-partners.deactivate', $channelPartner) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">
                        Deactivate
                    </button>
                </form>
                @elseif($channelPartner->status === 'inactive')
                <form method="POST" action="{{ route('channel-partners.activate', $channelPartner) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                        Activate
                    </button>
                </form>
                @endif
                @if($channelPartner->status !== 'suspended')
                <form method="POST" action="{{ route('channel-partners.suspend', $channelPartner) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                        Suspend
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Partner Code</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->partner_code }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Company Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->company_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact Person</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->contact_person }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <a href="mailto:{{ $channelPartner->email }}" class="text-teal-600 hover:text-teal-900">{{ $channelPartner->email }}</a>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <a href="tel:{{ $channelPartner->phone }}" class="text-teal-600 hover:text-teal-900">{{ $channelPartner->phone }}</a>
                        </p>
                    </div>
                    @if($channelPartner->alternate_phone)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alternate Phone</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <a href="tel:{{ $channelPartner->alternate_phone }}" class="text-teal-600 hover:text-teal-900">{{ $channelPartner->alternate_phone }}</a>
                        </p>
                    </div>
                    @endif
                    @if($channelPartner->website)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Website</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <a href="{{ $channelPartner->website }}" target="_blank" class="text-teal-600 hover:text-teal-900">{{ $channelPartner->website }}</a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($channelPartner->address)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->address }}</p>
                    </div>
                    @endif
                    @if($channelPartner->city)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">City</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->city }}</p>
                    </div>
                    @endif
                    @if($channelPartner->state)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">State</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->state }}</p>
                    </div>
                    @endif
                    @if($channelPartner->pincode)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pincode</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->pincode }}</p>
                    </div>
                    @endif
                    @if($channelPartner->country)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Country</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->country }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Business Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($channelPartner->gst_number)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">GST Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->gst_number }}</p>
                    </div>
                    @endif
                    @if($channelPartner->pan_number)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">PAN Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->pan_number }}</p>
                    </div>
                    @endif
                    @if($channelPartner->specializations)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Specializations</label>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach($channelPartner->specializations as $specialization)
                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full">
                                {{ $specialization }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($channelPartner->notes)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                <p class="text-sm text-gray-900">{{ $channelPartner->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Financial Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Commission Rate</label>
                        <p class="mt-1 text-lg font-semibold text-teal-600">{{ $channelPartner->formatted_commission_rate }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Credit Limit</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $channelPartner->formatted_credit_limit }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Outstanding Amount</label>
                        <p class="mt-1 text-lg font-semibold text-red-600">{{ $channelPartner->formatted_outstanding_amount }}</p>
                    </div>
                </div>
            </div>

            <!-- Agreement Information -->
            @if($channelPartner->agreement_start_date || $channelPartner->agreement_end_date)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Agreement Information</h3>
                <div class="space-y-4">
                    @if($channelPartner->agreement_start_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->agreement_start_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                    @if($channelPartner->agreement_end_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->agreement_end_date->format('M d, Y') }}</p>
                        @if($channelPartner->is_agreement_expired)
                        <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full mt-1">
                            Expired
                        </span>
                        @elseif($channelPartner->is_agreement_expiring_soon)
                        <span class="inline-flex px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full mt-1">
                            Expiring Soon
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Assignment Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Assigned To</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->assignedUser->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created By</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->creator->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $channelPartner->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Leads</span>
                        <span class="text-sm font-medium text-gray-900">{{ $channelPartner->leads->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Projects</span>
                        <span class="text-sm font-medium text-gray-900">{{ $channelPartner->projects->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Active Projects</span>
                        <span class="text-sm font-medium text-gray-900">{{ $channelPartner->projects->where('status', 'active')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Records -->
    @if($channelPartner->leads->count() > 0 || $channelPartner->projects->count() > 0)
    <div class="mt-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Related Records</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Leads -->
            @if($channelPartner->leads->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Leads</h3>
                <div class="space-y-3">
                    @foreach($channelPartner->leads->take(5) as $lead)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $lead->name }}</p>
                            <p class="text-xs text-gray-500">{{ $lead->company }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $lead->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @if($channelPartner->leads->count() > 5)
                <div class="mt-4">
                    <a href="{{ route('leads.index', ['channel_partner_id' => $channelPartner->id]) }}" class="text-sm text-teal-600 hover:text-teal-900">
                        View all {{ $channelPartner->leads->count() }} leads →
                    </a>
                </div>
                @endif
            </div>
            @endif

            <!-- Recent Projects -->
            @if($channelPartner->projects->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Projects</h3>
                <div class="space-y-3">
                    @foreach($channelPartner->projects->take(5) as $project)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $project->name }}</p>
                            <p class="text-xs text-gray-500">{{ $project->project_code }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $project->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($project->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @if($channelPartner->projects->count() > 5)
                <div class="mt-4">
                    <a href="{{ route('projects.index', ['channel_partner_id' => $channelPartner->id]) }}" class="text-sm text-teal-600 hover:text-teal-900">
                        View all {{ $channelPartner->projects->count() }} projects →
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
