@extends('layouts.app')

@section('title', 'Duplicate Lead Approval Requests')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Duplicate Lead Approval Requests</h1>
            <p class="mt-2 text-gray-600">Review and approve/reject duplicate lead requests</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('admin.duplicate-lead-approvals.index') }}" class="flex gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="" {{ (!request()->has('status') || request('status') === '') ? 'selected' : '' }}>All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Pending Requests -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                @if(request()->filled('status') && request('status') == 'approved')
                    Approved Requests
                @elseif(request()->filled('status') && request('status') == 'rejected')
                    Rejected Requests
                @elseif(request()->filled('status') && request('status') == 'pending')
                    Pending Approval Requests
                @else
                    All Duplicate Lead Approval Requests
                @endif
            </h2>
            
            @if($approvals->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Lead Info</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Existing Lead</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested By</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($approvals as $approval)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $approval->lead_data['name'] ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $approval->lead_data['email'] ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $approval->lead_data['phone'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($approval->existingLead)
                                    <a href="{{ route('leads.show', $approval->existingLead) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                        {{ $approval->existingLead->name }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ $approval->existingLead->email }}</div>
                                @else
                                    <span class="text-sm text-gray-500">Lead not found</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $approval->requester->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $approval->requester->email ?? '' }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $approval->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $approval->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="text-sm text-gray-900">{{ Str::limit($approval->reason ?? 'No reason provided', 50) }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($approval->status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($approval->status === 'approved')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.duplicate-lead-approvals.show', $approval->id) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                        View
                                    </a>
                                    @if($approval->status === 'pending')
                                    <a href="{{ route('admin.duplicate-lead-approvals.approve', $approval->id) }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs"
                                       onclick="return confirm('Are you sure you want to approve this duplicate lead request? The lead will be created.')">
                                        Approve
                                    </a>
                                    <button onclick="openRejectModal({{ $approval->id }})" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                        Reject
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $approvals->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No approval requests</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->filled('status'))
                        There are no {{ request('status') }} duplicate lead approval requests.
                    @else
                        There are no duplicate lead approval requests.
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Duplicate Lead Request</h3>
            <form id="rejectForm" method="POST" action="">
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
function openRejectModal(approvalId) {
    document.getElementById('rejectForm').action = '/admin/duplicate-lead-approvals/' + approvalId + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}
</script>
@endsection

