@extends('layouts.app')

@section('title', 'Lead Backup Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-white shadow rounded-lg p-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $backup->name }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $backup->company ?? 'No Company' }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.lead-backups.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Backups
            </a>
            @if(!$backup->isExpired())
            <form method="POST" action="{{ route('admin.lead-backups.restore', $backup->id) }}" onsubmit="return confirm('Are you sure you want to restore this lead? The backup will be deleted after restoration.');">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Restore Lead
                </button>
            </form>
            @else
            <button disabled class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Cannot Restore (Expired)
            </button>
            @endif
        </div>
    </div>

    <!-- Deletion Info Banner -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800">Deleted Lead Backup</h3>
                <div class="mt-2 text-sm text-yellow-700 space-y-1">
                    <p><strong>Deleted by:</strong> {{ $backup->deletedBy->name ?? 'Unknown' }} on {{ $backup->deleted_at->format('M d, Y h:i A') }}</p>
                    @if($backup->approvedBy)
                    <p><strong>Approved by:</strong> {{ $backup->approvedBy->name }}</p>
                    @endif
                    @if($backup->deletion_reason)
                    <p class="mt-2"><strong>Deletion Reason:</strong></p>
                    <p class="bg-white p-3 rounded mt-1 border border-yellow-200">{{ $backup->deletion_reason }}</p>
                    @endif
                    <p class="mt-2">
                        <strong>Status:</strong> 
                        @if($backup->isExpired())
                            <span class="text-red-600 font-semibold">Expired</span> (Expired on {{ $backup->expires_at->format('M d, Y') }})
                        @else
                            <span class="text-green-600 font-semibold">Active</span> 
                            @if($backup->expires_at)
                                (Expires in {{ $backup->daysUntilExpiration() }} days on {{ $backup->expires_at->format('M d, Y') }})
                            @endif
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Lead Information -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Lead Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <p class="text-sm text-gray-900">{{ $backup->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                    <p class="text-sm text-gray-900">{{ $backup->company ?? 'Not specified' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <p class="text-sm text-gray-900">{{ $backup->phone }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <p class="text-sm text-gray-900">{{ $backup->email ?? 'Not provided' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', $backup->source)) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $backup->status === 'new' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $backup->status === 'contacted' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $backup->status === 'qualified' ? 'bg-purple-100 text-purple-800' : '' }}
                        {{ $backup->status === 'lost' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $backup->status === 'converted' ? 'bg-indigo-100 text-indigo-800' : '' }}">
                        {{ ucfirst($backup->status) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $backup->priority === 'high' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $backup->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $backup->priority === 'low' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst($backup->priority) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Value</label>
                    <p class="text-sm text-gray-900">{{ $backup->estimated_value ? '₹' . number_format($backup->estimated_value, 2) : 'Not specified' }}</p>
                </div>

                @if($backup->expected_close_date)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Close Date</label>
                    <p class="text-sm text-gray-900">{{ $backup->expected_close_date->format('M d, Y') }}</p>
                </div>
                @endif

                @if($backup->address)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <p class="text-sm text-gray-900">{{ $backup->address }}</p>
                    @if($backup->city || $backup->state || $backup->pincode)
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $backup->city }}{{ $backup->city && $backup->state ? ', ' : '' }}{{ $backup->state }}{{ ($backup->city || $backup->state) && $backup->pincode ? ' - ' : '' }}{{ $backup->pincode }}
                    </p>
                    @endif
                </div>
                @endif

                @if($backup->assignedUser)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                    <p class="text-sm text-gray-900">{{ $backup->assignedUser->name }}</p>
                </div>
                @endif

                @if($backup->creator)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Created By</label>
                    <p class="text-sm text-gray-900">{{ $backup->creator->name }}</p>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Original Lead ID</label>
                    <p class="text-sm text-gray-900">{{ $backup->original_lead_id ?? 'N/A' }}</p>
                </div>
            </div>

            @if($backup->notes)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $backup->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Backup Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Backup Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Backup ID</label>
                        <p class="text-sm text-gray-900">#{{ $backup->id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deleted At</label>
                        <p class="text-sm text-gray-900">{{ $backup->deleted_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $backup->deleted_at->format('h:i A') }}</p>
                    </div>
                    
                    @if($backup->expires_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expires At</label>
                        <p class="text-sm text-gray-900">{{ $backup->expires_at->format('M d, Y') }}</p>
                        <p class="text-xs {{ $backup->isExpired() ? 'text-red-600' : 'text-gray-500' }}">
                            @if($backup->isExpired())
                                Expired
                            @else
                                {{ $backup->daysUntilExpiration() }} days remaining
                            @endif
                        </p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deleted By</label>
                        <p class="text-sm text-gray-900">{{ $backup->deletedBy->name ?? 'Unknown' }}</p>
                        @if($backup->deletedBy)
                        <p class="text-xs text-gray-500">{{ $backup->deletedBy->email ?? '' }}</p>
                        @endif
                    </div>
                    
                    @if($backup->approvedBy)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                        <p class="text-sm text-gray-900">{{ $backup->approvedBy->name }}</p>
                        <p class="text-xs text-gray-500">{{ $backup->approvedBy->email ?? '' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Warning Box -->
            @if($backup->isExpired())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Expired Backup</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>This backup has expired and may be automatically deleted soon.</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Active Backup</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>This backup is active and will be retained for {{ $backup->daysUntilExpiration() }} more days.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

