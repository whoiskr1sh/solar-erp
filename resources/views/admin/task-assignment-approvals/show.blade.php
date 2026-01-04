@extends('layouts.app')

@section('title', 'View Task Assignment Approval')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Task Assignment Approval Details</h1>
            <p class="mt-2 text-gray-600">Review task assignment approval request</p>
        </div>
        <a href="{{ route('admin.task-assignment-approvals.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Approvals
        </a>
    </div>

    <!-- Approval Info -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Task</label>
                <p class="text-sm text-gray-900 font-medium">{{ $approval->task->title }}</p>
                <p class="text-xs text-gray-500">{{ $approval->task->project->name ?? 'No Project' }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Requested By</label>
                <p class="text-sm text-gray-900">{{ $approval->requester->name }}</p>
                <p class="text-xs text-gray-500">{{ $approval->requester->email }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                <p class="text-sm text-gray-900">{{ $approval->assignedTo->name }}</p>
                <p class="text-xs text-gray-500">{{ $approval->assignedTo->email }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                @if($approval->status === 'pending_manager_approval')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Pending Manager Approval
                    </span>
                @elseif($approval->status === 'pending_admin_approval')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        Pending Admin Approval
                    </span>
                @elseif($approval->status === 'approved')
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
                <p class="text-sm text-gray-900">{{ $approval->created_at->format('M d, Y g:i A') }}</p>
            </div>
            
            @if($approval->manager_approved_at)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Manager Approved By</label>
                    <p class="text-sm text-gray-900">{{ $approval->managerApprover->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $approval->manager_approved_at->format('M d, Y g:i A') }}</p>
                </div>
            @endif
            
            @if($approval->admin_approved_at)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Approved By</label>
                    <p class="text-sm text-gray-900">{{ $approval->adminApprover->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $approval->admin_approved_at->format('M d, Y g:i A') }}</p>
                </div>
            @endif
            
            @if($approval->manager_rejection_reason)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Manager Rejection Reason</label>
                    <p class="text-sm text-gray-900 bg-red-50 p-3 rounded-md">{{ $approval->manager_rejection_reason }}</p>
                </div>
            @endif
            
            @if($approval->admin_rejection_reason)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Rejection Reason</label>
                    <p class="text-sm text-gray-900 bg-red-50 p-3 rounded-md">{{ $approval->admin_rejection_reason }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Task Details -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Task Details</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $approval->task->description ?? 'No description' }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($approval->task->priority === 'critical') bg-red-100 text-red-800
                    @elseif($approval->task->priority === 'high') bg-orange-100 text-orange-800
                    @elseif($approval->task->priority === 'medium') bg-yellow-100 text-yellow-800
                    @else bg-green-100 text-green-800
                    @endif">
                    {{ ucfirst($approval->task->priority) }}
                </span>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                <p class="text-sm text-gray-900">{{ $approval->task->due_date->format('M d, Y') }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    @if($approval->task->status === 'completed') bg-green-100 text-green-800
                    @elseif($approval->task->status === 'in_progress') bg-blue-100 text-blue-800
                    @elseif($approval->task->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $approval->task->status)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    @if($approval->status === 'pending_manager_approval' || $approval->status === 'pending_admin_approval')
        @php
            $canApprove = false;
            if ($approval->status === 'pending_manager_approval' && (auth()->user()->hasRole('SALES MANAGER') || auth()->user()->hasRole('SUPER ADMIN'))) {
                $canApprove = true;
            } elseif ($approval->status === 'pending_admin_approval' && auth()->user()->hasRole('SUPER ADMIN')) {
                $canApprove = true;
            }
        @endphp
        @if($canApprove)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.task-assignment-approvals.approve', $approval->id) }}" 
                   onclick="return confirm('Are you sure you want to approve this task assignment?')"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve Assignment
                </a>
                <a href="{{ route('admin.task-assignment-approvals.reject', $approval->id) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject Assignment
                </a>
            </div>
        </div>
        @endif
    @endif
</div>
@endsection

