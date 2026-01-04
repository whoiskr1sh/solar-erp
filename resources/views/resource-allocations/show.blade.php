@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $resourceAllocation->title }}</h1>
            <p class="text-gray-600 mt-2">{{ $resourceAllocation->allocation_code }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('resource-allocations.edit', $resourceAllocation) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('resource-allocations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Allocations
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Basic Information</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Allocation Code</label>
                            <p class="text-gray-900">{{ $resourceAllocation->allocation_code }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $resourceAllocation->status_badge }}">{{ ucfirst($resourceAllocation->status) }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Resource Type</label>
                            <p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $resourceAllocation->resource_type_badge }}">{{ ucfirst($resourceAllocation->resource_type) }}</span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $resourceAllocation->priority_badge }}">{{ ucfirst($resourceAllocation->priority) }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <p class="text-gray-900">{{ $resourceAllocation->description ?: 'No description provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Resource Details -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Resource Details</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Resource Name</label>
                            <p class="text-gray-900">{{ $resourceAllocation->resource_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Resource Category</label>
                            <p class="text-gray-900">{{ $resourceAllocation->resource_category ?: 'Not specified' }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Resource Specifications</label>
                        <p class="text-gray-900">{{ $resourceAllocation->resource_specifications ?: 'No specifications provided' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Allocated Quantity</label>
                            <p class="text-gray-900">{{ $resourceAllocation->allocated_quantity }} {{ $resourceAllocation->quantity_unit }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Actual Quantity</label>
                            <p class="text-gray-900">{{ $resourceAllocation->actual_quantity }} {{ $resourceAllocation->quantity_unit }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project and Assignment -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Project & Assignment</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                            <p class="text-gray-900">{{ $resourceAllocation->project->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Activity</label>
                            <p class="text-gray-900">{{ $resourceAllocation->activity->title ?? 'Not assigned to any activity' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Allocated To</label>
                            <p class="text-gray-900">{{ $resourceAllocation->allocatedTo->name ?? 'Unassigned' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Allocated By</label>
                            <p class="text-gray-900">{{ $resourceAllocation->allocatedBy->name }}</p>
                        </div>
                    </div>

                    @if($resourceAllocation->approvedBy)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Approved By</label>
                            <p class="text-gray-900">{{ $resourceAllocation->approvedBy->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Approved At</label>
                            <p class="text-gray-900">{{ $resourceAllocation->approved_at ? $resourceAllocation->approved_at->format('M d, Y H:i') : 'Not approved' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Timeline</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Planned Start Date</label>
                            <p class="text-gray-900">{{ $resourceAllocation->formatted_allocation_start_date }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Planned End Date</label>
                            <p class="text-gray-900">{{ $resourceAllocation->formatted_allocation_end_date }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Actual Start Date</label>
                            <p class="text-gray-900">{{ $resourceAllocation->formatted_actual_start_date }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Actual End Date</label>
                            <p class="text-gray-900">{{ $resourceAllocation->formatted_actual_end_date }}</p>
                        </div>
                    </div>

                    @if($resourceAllocation->is_overdue)
                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Overdue</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>This allocation is {{ $resourceAllocation->days_overdue }} days overdue.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Cost Information -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Cost Information</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate</label>
                            <p class="text-gray-900">{{ $resourceAllocation->formatted_hourly_rate }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost</label>
                            <p class="text-gray-900">{{ $resourceAllocation->formatted_unit_cost }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost</label>
                            <p class="text-gray-900">{{ $resourceAllocation->formatted_total_estimated_cost }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Actual Cost</label>
                            <p class="text-gray-900">{{ $resourceAllocation->formatted_total_actual_cost }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Budget Allocated</label>
                            <p class="text-gray-900">{{ $resourceAllocation->formatted_budget_allocated }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Utilization Tracking -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Utilization Tracking</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Utilization Percentage</label>
                            <div class="w-full bg-gray-200 rounded-full h-6 mb-2">
                                <div class="h-6 rounded-full {{ $resourceAllocation->utilization_bar_color }}" 
                                     style="width: {{ $resourceAllocation->utilization_percentage }}%">
                                    <span class="text-xs text-white font-medium flex items-center justify-center h-full">
                                        {{ $resourceAllocation->utilization_percentage }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <p>
                                @if($resourceAllocation->is_overallocated)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Overallocated</span>
                                @elseif($resourceAllocation->is_underutilized)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Underutilized</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Normal</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($resourceAllocation->utilization_notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Utilization Notes</label>
                        <p class="text-gray-900">{{ $resourceAllocation->utilization_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Additional Information</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Critical Resource</label>
                            <p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $resourceAllocation->is_critical ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $resourceAllocation->is_critical ? 'Yes' : 'No' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Billable</label>
                            <p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $resourceAllocation->is_billable ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $resourceAllocation->is_billable ? 'Yes' : 'No' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    @if($resourceAllocation->tags && count($resourceAllocation->tags) > 0)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($resourceAllocation->tags as $tag)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($resourceAllocation->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <p class="text-gray-900">{{ $resourceAllocation->notes }}</p>
                    </div>
                    @endif

                    @if($resourceAllocation->completion_notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Completion Notes</label>
                        <p class="text-gray-900">{{ $resourceAllocation->completion_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Quick Actions</h6>
                </div>
                <div class="p-6 space-y-3">
                    @if($resourceAllocation->status === 'planned')
                        <form method="POST" action="{{ route('resource-allocations.mark-allocated', $resourceAllocation) }}">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Mark as Allocated
                            </button>
                        </form>
                    @elseif($resourceAllocation->status === 'allocated')
                        <form method="POST" action="{{ route('resource-allocations.mark-in-use', $resourceAllocation) }}">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h8a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z"></path>
                                </svg>
                                Mark as In Use
                            </button>
                        </form>
                    @endif

                    @if($resourceAllocation->status === 'in_use')
                        <button type="button" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center justify-center" onclick="showCompleteModal()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mark as Completed
                        </button>
                    @endif

                    <button type="button" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg flex items-center justify-center" onclick="showUtilizationModal()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Update Utilization
                    </button>

                    <button type="button" class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center justify-center" onclick="showQuantityModal()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Quantity
                    </button>

                    <button type="button" class="w-full bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg flex items-center justify-center" onclick="showCostModal()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Add Cost
                    </button>

                    @if(!$resourceAllocation->approved_by)
                        <form method="POST" action="{{ route('resource-allocations.approve', $resourceAllocation) }}">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Approve Allocation
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Resource Summary -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Resource Summary</h6>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="relative w-24 h-24 mx-auto mb-4">
                            <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="40" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                                <circle cx="50" cy="50" r="40" stroke="{{ $resourceAllocation->utilization_percentage >= 80 ? '#ef4444' : ($resourceAllocation->utilization_percentage >= 60 ? '#f59e0b' : '#10b981') }}" 
                                        stroke-width="8" fill="none" 
                                        stroke-dasharray="{{ 2 * 3.14159 * 40 }}" 
                                        stroke-dashoffset="{{ 2 * 3.14159 * 40 * (1 - $resourceAllocation->utilization_percentage / 100) }}"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-lg font-bold text-gray-900">{{ $resourceAllocation->utilization_percentage }}%</span>
                            </div>
                        </div>
                        <h4 class="font-semibold text-gray-900">Utilization</h4>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <h5 class="font-semibold text-teal-600">{{ $resourceAllocation->allocated_quantity }}</h5>
                            <p class="text-sm text-gray-500">Allocated</p>
                        </div>
                        <div>
                            <h5 class="font-semibold text-green-600">{{ $resourceAllocation->actual_quantity }}</h5>
                            <p class="text-sm text-gray-500">Actual</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <h6 class="font-semibold text-cyan-600">{{ $resourceAllocation->formatted_total_estimated_cost }}</h6>
                            <p class="text-sm text-gray-500">Estimated</p>
                        </div>
                        <div>
                            <h6 class="font-semibold text-yellow-600">{{ $resourceAllocation->formatted_total_actual_cost }}</h6>
                            <p class="text-sm text-gray-500">Actual</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form method="POST" action="{{ route('resource-allocations.mark-completed', $resourceAllocation) }}">
            @csrf
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Mark as Completed</h3>
                    <button type="button" onclick="hideCompleteModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <label for="completion_notes" class="block text-sm font-medium text-gray-700 mb-2">Completion Notes <span class="text-red-500">*</span></label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" id="completion_notes" name="completion_notes" rows="3" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideCompleteModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Mark Completed</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Utilization Modal -->
<div id="utilizationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form method="POST" action="{{ route('resource-allocations.update-utilization', $resourceAllocation) }}">
            @csrf
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Update Utilization</h3>
                    <button type="button" onclick="hideUtilizationModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <label for="utilization_percentage" class="block text-sm font-medium text-gray-700 mb-2">Utilization Percentage <span class="text-red-500">*</span></label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" id="utilization_percentage" name="utilization_percentage" 
                           min="0" max="100" value="{{ $resourceAllocation->utilization_percentage }}" required>
                </div>
                <div class="mb-4">
                    <label for="utilization_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" id="utilization_notes" name="utilization_notes" rows="3"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideUtilizationModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Update Utilization</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Quantity Modal -->
<div id="quantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form method="POST" action="{{ route('resource-allocations.add-quantity', $resourceAllocation) }}">
            @csrf
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Quantity</h3>
                    <button type="button" onclick="hideQuantityModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Additional Quantity <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" id="quantity" name="quantity" min="0.01" required>
                    <p class="text-sm text-gray-500 mt-1">Current: {{ $resourceAllocation->actual_quantity }} {{ $resourceAllocation->quantity_unit }}</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideQuantityModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Add Quantity</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Cost Modal -->
<div id="costModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form method="POST" action="{{ route('resource-allocations.add-cost', $resourceAllocation) }}">
            @csrf
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Cost</h3>
                    <button type="button" onclick="hideCostModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Additional Cost (Rs.) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" id="cost" name="cost" min="0" required>
                    <p class="text-sm text-gray-500 mt-1">Current: {{ $resourceAllocation->formatted_total_actual_cost }}</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideCostModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Add Cost</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showCompleteModal() {
    document.getElementById('completeModal').classList.remove('hidden');
}

function hideCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
}

function showUtilizationModal() {
    document.getElementById('utilizationModal').classList.remove('hidden');
}

function hideUtilizationModal() {
    document.getElementById('utilizationModal').classList.add('hidden');
}

function showQuantityModal() {
    document.getElementById('quantityModal').classList.remove('hidden');
}

function hideQuantityModal() {
    document.getElementById('quantityModal').classList.add('hidden');
}

function showCostModal() {
    document.getElementById('costModal').classList.remove('hidden');
}

function hideCostModal() {
    document.getElementById('costModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const modals = ['completeModal', 'utilizationModal', 'quantityModal', 'costModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
</script>
@endsection


