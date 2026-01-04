@extends('layouts.app')

@section('title', 'Lead Reassignment Requests')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Lead Reassignment Requests</h1>
            <p class="mt-2 text-gray-600">Review and manage lead reassignment requests</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <form method="GET" action="{{ route('admin.lead-reassignment-requests.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Pending</option>
                    <option value="pending_manager_approval" {{ request('status') == 'pending_manager_approval' ? 'selected' : '' }}>Pending Manager Approval</option>
                    <option value="pending_admin_approval" {{ request('status') == 'pending_admin_approval' ? 'selected' : '' }}>Pending Admin Approval</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assign To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $request->requester->name }}</div>
                            <div class="text-xs text-gray-500">{{ $request->requester->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $request->assignedTo->name }}</div>
                            <div class="text-xs text-gray-500">{{ $request->assignedTo->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($request->reason, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->status === 'pending_manager_approval')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending Manager
                                </span>
                            @elseif($request->status === 'pending_admin_approval')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Pending Admin
                                </span>
                            @elseif($request->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->created_at->format('M d, Y g:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.lead-reassignment-requests.show', $request->id) }}" class="text-teal-600 hover:text-teal-900 mr-3">View</a>
                            @if($request->status === 'pending_manager_approval' || $request->status === 'pending_admin_approval')
                                @php
                                    $canApprove = false;
                                    if ($request->status === 'pending_manager_approval' && (auth()->user()->hasRole('SALES MANAGER') || auth()->user()->hasRole('SUPER ADMIN'))) {
                                        $canApprove = true;
                                    } elseif ($request->status === 'pending_admin_approval' && auth()->user()->hasRole('SUPER ADMIN')) {
                                        $canApprove = true;
                                    }
                                @endphp
                                @if($canApprove)
                                    <a href="{{ route('admin.lead-reassignment-requests.approve', $request->id) }}" 
                                       onclick="return confirm('Are you sure you want to approve this request?')"
                                       class="text-green-600 hover:text-green-900 mr-3">Approve</a>
                                    <a href="{{ route('admin.lead-reassignment-requests.reject', $request->id) }}" class="text-red-600 hover:text-red-900">Reject</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No reassignment requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection

