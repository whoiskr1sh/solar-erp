@extends('layouts.app')

@section('title', 'View Lead - Delete Approval')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Deletion Request Info Banner -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800">Pending Deletion Request</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p><strong>Requested by:</strong> {{ $deleteApproval->requester->name ?? 'Unknown' }} ({{ $deleteApproval->requester->email ?? 'N/A' }})</p>
                    <p><strong>Requested on:</strong> {{ $deleteApproval->created_at->format('M d, Y h:i A') }}</p>
                    <p class="mt-2"><strong>Reason for deletion:</strong></p>
                    <p class="bg-white p-3 rounded mt-1 border border-yellow-200">{{ $deleteApproval->reason ?? 'No reason provided' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-white shadow rounded-lg p-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lead Details</h1>
            <p class="text-sm text-gray-600 mt-1">Review lead information before approving or rejecting deletion</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.delete-approvals.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Requests
            </a>
            <a href="{{ route('admin.delete-approval.reject', $deleteApproval->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Reject Deletion
            </a>
            <a href="{{ route('admin.delete-approval.approve', $deleteApproval->id) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700"
               onclick="return confirm('Are you sure you want to approve this deletion? The lead will be deleted and backed up for 40 days.')">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Approve Deletion
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lead Information -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Lead Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <p class="text-sm text-gray-900">{{ $lead->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                    <p class="text-sm text-gray-900">{{ $lead->company ?? 'Not specified' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <p class="text-sm text-gray-900">{{ $lead->phone }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <p class="text-sm text-gray-900">{{ $lead->email ?? 'Not provided' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', $lead->source)) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $lead->status === 'new' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $lead->status === 'contacted' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $lead->status === 'qualified' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $lead->status === 'lost' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $lead->status === 'converted' ? 'bg-indigo-100 text-indigo-800' : '' }}">
                        {{ ucfirst($lead->status) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $lead->priority === 'high' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $lead->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $lead->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst($lead->priority) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Value</label>
                    <p class="text-sm text-gray-900">{{ $lead->estimated_value ? '₹' . number_format($lead->estimated_value, 2) : 'Not specified' }}</p>
                </div>

                @if($lead->expected_close_date)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Close Date</label>
                    <p class="text-sm text-gray-900">{{ $lead->expected_close_date->format('M d, Y') }}</p>
                </div>
                @endif

                @if($lead->address)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <p class="text-sm text-gray-900">{{ $lead->address }}</p>
                    @if($lead->city || $lead->state || $lead->pincode)
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $lead->city }}{{ $lead->city && $lead->state ? ', ' : '' }}{{ $lead->state }}{{ ($lead->city || $lead->state) && $lead->pincode ? ' - ' : '' }}{{ $lead->pincode }}
                    </p>
                    @endif
                </div>
                @endif

                @if($lead->assignedUser)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                    <p class="text-sm text-gray-900">{{ $lead->assignedUser->name }}</p>
                </div>
                @endif

                @if($lead->creator)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Created By</label>
                    <p class="text-sm text-gray-900">{{ $lead->creator->name }}</p>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Created At</label>
                    <p class="text-sm text-gray-900">{{ $lead->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                    <p class="text-sm text-gray-900">{{ $lead->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            @if($lead->notes)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $lead->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Related Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Related Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Projects</label>
                        @if($lead->projects_count > 0)
                            <p class="text-sm text-gray-900">{{ $lead->projects_count }} project(s) associated</p>
                        @else
                            <p class="text-sm text-gray-500">No projects</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Invoices</label>
                        @if($lead->invoices_count > 0)
                            <p class="text-sm text-gray-900">{{ $lead->invoices_count }} invoice(s) associated</p>
                        @else
                            <p class="text-sm text-gray-500">No invoices</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Warning Box -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Warning</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>Approving this deletion will:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>Permanently delete this lead</li>
                                <li>Create a backup for 40 days</li>
                                <li>Remove all associations</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

