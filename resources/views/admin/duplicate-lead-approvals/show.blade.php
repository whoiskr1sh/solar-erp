@extends('layouts.app')

@section('title', 'Duplicate Lead Approval Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Duplicate Lead Approval Details</h1>
            <p class="mt-2 text-gray-600">Review duplicate lead request details</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.duplicate-lead-approvals.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Approvals
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- New Lead Data -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">New Lead Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <p class="text-sm text-gray-900">{{ $approval->lead_data['name'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-sm text-gray-900">{{ $approval->lead_data['email'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <p class="text-sm text-gray-900">{{ $approval->lead_data['phone'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                    <p class="text-sm text-gray-900">{{ $approval->lead_data['company'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                    <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $approval->lead_data['source'] ?? 'N/A')) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $approval->lead_data['status'] ?? 'N/A')) }}</p>
                </div>
            </div>
        </div>

        <!-- Existing Lead -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Existing Lead (Same Email)</h3>
            @if($approval->existingLead)
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <p class="text-sm text-gray-900">{{ $approval->existingLead->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $approval->existingLead->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <p class="text-sm text-gray-900">{{ $approval->existingLead->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                        <p class="text-sm text-gray-900">{{ $approval->existingLead->company ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $approval->existingLead->status_badge }}">
                            {{ $approval->existingLead->status_label }}
                        </span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('leads.show', $approval->existingLead) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            View Existing Lead
                        </a>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500">Existing lead not found or has been deleted.</p>
            @endif
        </div>
    </div>

    <!-- Approval Information -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Requested By</label>
                <p class="text-sm text-gray-900">{{ $approval->requester->name ?? 'Unknown' }}</p>
                <p class="text-xs text-gray-500">{{ $approval->requester->email ?? '' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Requested At</label>
                <p class="text-sm text-gray-900">{{ $approval->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                <p class="text-sm text-gray-900">{{ $approval->reason ?? 'No reason provided' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                @if($approval->status === 'pending')
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                @elseif($approval->status === 'approved')
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                    @if($approval->approver)
                        <p class="text-xs text-gray-500 mt-1">Approved by {{ $approval->approver->name }} on {{ $approval->approved_at->format('M d, Y') }}</p>
                    @endif
                @else
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                    @if($approval->approver)
                        <p class="text-xs text-gray-500 mt-1">Rejected by {{ $approval->approver->name }} on {{ $approval->approved_at->format('M d, Y') }}</p>
                    @endif
                    @if($approval->rejection_reason)
                        <p class="text-sm text-red-600 mt-2">{{ $approval->rejection_reason }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Actions -->
    @if($approval->status === 'pending')
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
        <div class="flex space-x-3">
            <a href="{{ route('admin.duplicate-lead-approvals.approve', $approval->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700"
               onclick="return confirm('Are you sure you want to approve this duplicate lead request? The lead will be created.')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Approve Request
            </a>
            <button onclick="openRejectModal()" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Reject Request
            </button>
        </div>
    </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Duplicate Lead Request</h3>
            <form method="POST" action="{{ route('admin.duplicate-lead-approvals.reject', $approval->id) }}">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="3" required minlength="10"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                              placeholder="Enter reason for rejection (minimum 10 characters)..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}
</script>
@endsection

