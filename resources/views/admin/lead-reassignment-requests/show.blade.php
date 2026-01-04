@extends('layouts.app')

@section('title', 'View Reassignment Request')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reassignment Request Details</h1>
            <p class="mt-2 text-gray-600">Review request details and leads to be reassigned</p>
        </div>
        <a href="{{ route('admin.lead-reassignment-requests.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Requests
        </a>
    </div>

    <!-- Request Info -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Request Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Requested By</label>
                <p class="text-sm text-gray-900">{{ $reassignmentRequest->requester->name }}</p>
                <p class="text-xs text-gray-500">{{ $reassignmentRequest->requester->email }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                <p class="text-sm text-gray-900">{{ $reassignmentRequest->assignedTo->name }}</p>
                <p class="text-xs text-gray-500">{{ $reassignmentRequest->assignedTo->email }}</p>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $reassignmentRequest->reason }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                @if($reassignmentRequest->status === 'pending_manager_approval')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending Manager Approval
                    </span>
                @elseif($reassignmentRequest->status === 'pending_admin_approval')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        Pending Admin Approval
                    </span>
                @elseif($reassignmentRequest->status === 'approved')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Approved
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Rejected
                    </span>
                @endif
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Requested At</label>
                <p class="text-sm text-gray-900">{{ $reassignmentRequest->created_at->format('M d, Y g:i A') }}</p>
            </div>
            
            @if($reassignmentRequest->status !== 'pending_manager_approval' && $reassignmentRequest->status !== 'pending_admin_approval')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Processed By</label>
                    <p class="text-sm text-gray-900">{{ $reassignmentRequest->approver->name ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Processed At</label>
                    <p class="text-sm text-gray-900">{{ $reassignmentRequest->approved_at ? $reassignmentRequest->approved_at->format('M d, Y g:i A') : 'N/A' }}</p>
                </div>
                
                @if($reassignmentRequest->rejection_reason)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                        <p class="text-sm text-gray-900 bg-red-50 p-3 rounded-md">{{ $reassignmentRequest->rejection_reason }}</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Leads to be Reassigned -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            Leads to be Reassigned 
            @if($reassignmentRequest->selected_lead_ids && is_array($reassignmentRequest->selected_lead_ids) && !empty($reassignmentRequest->selected_lead_ids))
                <span class="text-sm font-normal text-gray-600">({{ $leads->count() }} specific leads selected)</span>
            @else
                <span class="text-sm font-normal text-gray-600">({{ $reassignmentRequest->leads_count ?? $leads->count() }} leads by count)</span>
            @endif
        </h3>
        
        @if($leads->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($leads as $lead)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $lead->name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $lead->phone }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $lead->email ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lead->status_badge }}">
                                    {{ $lead->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lead->priority_badge }}">
                                    {{ ucfirst($lead->priority) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('leads.show', $lead) }}" class="text-teal-600 hover:text-teal-900">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">No leads found.</p>
        @endif
    </div>

    <!-- Actions -->
    @if($reassignmentRequest->status === 'pending_manager_approval' || $reassignmentRequest->status === 'pending_admin_approval')
        @php
            $canApprove = false;
            if ($reassignmentRequest->status === 'pending_manager_approval' && (auth()->user()->hasRole('SALES MANAGER') || auth()->user()->hasRole('SUPER ADMIN'))) {
                $canApprove = true;
            } elseif ($reassignmentRequest->status === 'pending_admin_approval' && auth()->user()->hasRole('SUPER ADMIN')) {
                $canApprove = true;
            }
        @endphp
        @if($canApprove)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.lead-reassignment-requests.approve', $reassignmentRequest->id) }}" 
                   onclick="return confirm('Are you sure you want to approve this request? All {{ $leads->count() }} lead(s) will be reassigned to {{ $reassignmentRequest->assignedTo->name }}.')"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve Request
                </a>
                <a href="{{ route('admin.lead-reassignment-requests.reject', $reassignmentRequest->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject Request
                </a>
            </div>
        </div>
        @endif
    @endif
</div>
@endsection

