@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-6 gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Leave Management</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Manage employee leave requests</p>
                </div>
                <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2 sm:gap-3">
                    <a href="{{ route('hr.leave-management.export', array_merge(['role' => request()->route('role')], request()->query())) }}" class="bg-gray-100 text-gray-700 px-3 sm:px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors text-sm sm:text-base">
                        <i class="fas fa-download mr-2"></i><span class="hidden sm:inline">Export</span>
                    </a>
                    @if(auth()->user()->email !== 'hr.manager@solarerp.com' && auth()->user()->name !== 'Anita Desai')
                    <button onclick="openCreateModal()" class="bg-teal-600 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors text-sm sm:text-base">
                        <i class="fas fa-plus mr-2"></i>New Leave Request
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages handled globally by toast -->

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6">
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-3 sm:p-4 md:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600 text-xs sm:text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-3 md:ml-4 flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Requests</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900">{{ $stats['total_requests'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-3 sm:p-4 md:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-xs sm:text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-3 md:ml-4 flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Pending Requests</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900">{{ $stats['pending_requests'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-3 sm:p-4 md:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xs sm:text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-3 md:ml-4 flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Approved Requests</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900">{{ $stats['approved_requests'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-3 sm:p-4 md:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-xs sm:text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-2 sm:ml-3 md:ml-4 flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Rejected Requests</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900">{{ $stats['rejected_requests'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border p-4 sm:p-5 md:p-6 mb-4 sm:mb-6">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" 
                           placeholder="Employee ID...">
                </div>
                
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                    <select name="leave_type" class="w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">All Types</option>
                        <option value="Sick Leave" {{ request('leave_type') == 'Sick Leave' ? 'selected' : '' }}>Sick Leave</option>
                        <option value="Personal Leave" {{ request('leave_type') == 'Personal Leave' ? 'selected' : '' }}>Personal Leave</option>
                        <option value="Annual Leave" {{ request('leave_type') == 'Annual Leave' ? 'selected' : '' }}>Annual Leave</option>
                    </select>
                </div>
                
                <div class="flex items-end space-x-2 sm:space-x-3">
                    <button type="submit" class="flex-1 sm:flex-none bg-teal-600 text-white px-3 sm:px-4 py-2 text-sm sm:text-base rounded-lg hover:bg-teal-700 transition-colors">
                        <i class="fas fa-search mr-1"></i><span class="hidden sm:inline">Filter</span>
                    </button>
                    <a href="{{ route('hr.leave-management', ['role' => request()->route('role') ?? strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex-1 sm:flex-none bg-gray-100 text-gray-700 px-3 sm:px-4 py-2 text-sm sm:text-base rounded-lg hover:bg-gray-200 transition-colors text-center">
                        <i class="fas fa-times mr-1"></i><span class="hidden sm:inline">Clear</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Leave Requests Table -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            @if($leaveRequests->count() > 0)
            <!-- Desktop/Tablet Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 md:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee Name</th>
                            <th class="px-3 md:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave Type</th>
                            <th class="px-3 md:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                            <th class="px-3 md:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                            <th class="px-3 md:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                            <th class="px-3 md:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 md:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Reason</th>
                            <th class="px-3 md:px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($leaveRequests as $leaveRequest)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 md:px-4 lg:px-6 py-3 md:py-4">
                                <div class="text-xs md:text-sm font-medium text-gray-900">{{ $leaveRequest->employee->name ?? $leaveRequest->employee_id }}</div>
                                @if($leaveRequest->employee)
                                <div class="text-xs text-gray-500">ID: {{ $leaveRequest->employee_id }}</div>
                                @endif
                            </td>
                            <td class="px-3 md:px-4 lg:px-6 py-3 md:py-4 whitespace-nowrap">
                                <div class="text-xs md:text-sm text-gray-900">{{ $leaveRequest->leave_type }}</div>
                            </td>
                            <td class="px-3 md:px-4 lg:px-6 py-3 md:py-4 whitespace-nowrap">
                                <div class="text-xs md:text-sm text-gray-900">{{ $leaveRequest->start_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-3 md:px-4 lg:px-6 py-3 md:py-4 whitespace-nowrap">
                                <div class="text-xs md:text-sm text-gray-900">{{ $leaveRequest->end_date->format('M d, Y') }}</div>
                            </td>
                            <td class="px-3 md:px-4 lg:px-6 py-3 md:py-4 whitespace-nowrap">
                                <div class="text-xs md:text-sm text-gray-900">{{ $leaveRequest->total_days }}</div>
                            </td>
                            <td class="px-3 md:px-4 lg:px-6 py-3 md:py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $leaveRequest->status_badge }}">
                                    {{ ucfirst($leaveRequest->status) }}
                                </span>
                            </td>
                            <td class="px-3 md:px-4 lg:px-6 py-3 md:py-4 whitespace-nowrap hidden lg:table-cell">
                                <div class="text-xs md:text-sm text-gray-900">{{ Str::limit($leaveRequest->reason, 30) }}</div>
                            </td>
                            <td class="px-3 md:px-4 lg:px-6 py-3 md:py-4 whitespace-nowrap text-xs md:text-sm font-medium">
                                <div class="flex items-center gap-1 md:gap-2 flex-wrap">
                                    @if(auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('HR MANAGER'))
                                    @if($leaveRequest->status == 'pending')
                                    <form action="{{ route('hr.leave-request.approve', $leaveRequest->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2 md:px-3 py-1.5 md:py-1 rounded-md bg-green-500 text-white hover:bg-green-600 transition-colors text-xs md:text-sm touch-manipulation">
                                            <i class="fas fa-check md:mr-1"></i><span class="hidden xl:inline">Approve</span>
                                        </button>
                                    </form>
                                    <button type="button" data-leave-id="{{ $leaveRequest->id }}" class="reject-btn px-2 md:px-3 py-1.5 md:py-1 rounded-md bg-red-500 text-white hover:bg-red-600 transition-colors text-xs md:text-sm touch-manipulation">
                                        <i class="fas fa-times md:mr-1"></i><span class="hidden xl:inline">Reject</span>
                                    </button>
                                    @else
                                    <button type="button" data-leave-id="{{ $leaveRequest->id }}" class="edit-btn px-2 md:px-3 py-1.5 md:py-1 rounded-md bg-blue-500 text-white hover:bg-blue-600 transition-colors text-xs md:text-sm touch-manipulation">
                                        <i class="fas fa-edit md:mr-1"></i><span class="hidden xl:inline">Edit</span>
                                    </button>
                                    @endif
                                    <button type="button" data-leave-id="{{ $leaveRequest->id }}" class="delete-btn px-2 md:px-3 py-1.5 md:py-1 rounded-md border border-red-500 text-red-600 hover:bg-red-50 transition-colors text-xs md:text-sm touch-manipulation">
                                        <i class="fas fa-trash md:mr-1"></i><span class="hidden xl:inline">Delete</span>
                                    </button>
                                    @else
                                    <span class="text-gray-400 text-sm">No actions available</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($leaveRequests as $leaveRequest)
                <div class="p-3 sm:p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1 min-w-0 pr-2">
                            <div class="text-sm sm:text-base font-semibold text-gray-900 truncate">{{ $leaveRequest->employee->name ?? $leaveRequest->employee_id }}</div>
                            @if($leaveRequest->employee)
                            <div class="text-xs text-gray-500">ID: {{ $leaveRequest->employee_id }}</div>
                            @endif
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full flex-shrink-0 {{ $leaveRequest->status_badge }}">
                            {{ ucfirst($leaveRequest->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 sm:gap-3 mb-3 text-xs sm:text-sm">
                        <div>
                            <span class="text-gray-500 block mb-0.5">Leave Type:</span>
                            <span class="text-gray-900 font-medium">{{ $leaveRequest->leave_type }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-0.5">Days:</span>
                            <span class="text-gray-900 font-medium">{{ $leaveRequest->total_days }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-0.5">Start:</span>
                            <span class="text-gray-900 font-medium">{{ $leaveRequest->start_date->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-0.5">End:</span>
                            <span class="text-gray-900 font-medium">{{ $leaveRequest->end_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <span class="text-gray-500 text-xs sm:text-sm block mb-1">Reason:</span>
                        <p class="text-gray-900 text-xs sm:text-sm leading-relaxed">{{ Str::limit($leaveRequest->reason, 80) }}</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-2 pt-3 border-t border-gray-200">
                        @if(auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('HR MANAGER'))
                        @if($leaveRequest->status == 'pending')
                        <form action="{{ route('hr.leave-request.approve', $leaveRequest->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-3 py-2.5 rounded-md bg-green-500 text-white hover:bg-green-600 transition-colors text-sm touch-manipulation">
                                <i class="fas fa-check mr-1"></i>Approve
                            </button>
                        </form>
                        <button type="button" data-leave-id="{{ $leaveRequest->id }}" class="reject-btn flex-1 px-3 py-2.5 rounded-md bg-red-500 text-white hover:bg-red-600 transition-colors text-sm touch-manipulation">
                            <i class="fas fa-times mr-1"></i>Reject
                        </button>
                        @else
                        <button type="button" data-leave-id="{{ $leaveRequest->id }}" class="edit-btn flex-1 px-3 py-2.5 rounded-md bg-blue-500 text-white hover:bg-blue-600 transition-colors text-sm touch-manipulation">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                        @endif
                        <button type="button" data-leave-id="{{ $leaveRequest->id }}" class="delete-btn flex-1 px-3 py-2.5 rounded-md border border-red-500 text-red-600 hover:bg-red-50 transition-colors text-sm touch-manipulation">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                        @else
                        <span class="text-gray-400 text-xs sm:text-sm text-center py-2">No actions available</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Pagination -->
            @if($leaveRequests->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $leaveRequests->links() }}
            </div>
            @endif
            @else
            <!-- Not Found Message -->
            <div class="text-center py-12">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Not Found</h3>
                    <p class="text-sm text-gray-500 mb-4">No leave requests found.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Leave Request Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center p-3 sm:p-4" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 sticky top-0 bg-white">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">New Leave Request</h3>
        </div>
        <form action="{{ route('hr.leave-request.store') }}" method="POST" class="p-4 sm:p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type <span class="text-red-500">*</span></label>
                    <select name="leave_type" required class="w-full px-3 py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Leave Type</option>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Personal Leave">Personal Leave</option>
                        <option value="Annual Leave">Annual Leave</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" onchange="updateEndDateMin()">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason <span class="text-red-500">*</span></label>
                    <textarea name="reason" required rows="4" class="w-full px-3 py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Please provide a reason for your leave request..."></textarea>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 sm:space-x-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()" class="w-full sm:w-auto px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm sm:text-base touch-manipulation">
                    Cancel
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm sm:text-base touch-manipulation">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Leave Request Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center p-3 sm:p-4" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 sticky top-0 bg-white">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Reject Leave Request</h3>
        </div>
        <form id="rejectForm" method="POST" class="p-4 sm:p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea name="rejection_reason" id="rejection_reason" rows="3" required class="w-full px-3 py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a reason for rejection..."></textarea>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Please provide a reason why this leave request is being rejected.</p>
                @error('rejection_reason')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 sm:space-x-3">
                <button type="button" onclick="closeRejectModal()" class="w-full sm:w-auto px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm sm:text-base touch-manipulation">
                    Cancel
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm sm:text-base touch-manipulation">
                    Reject Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Leave Request Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center p-3 sm:p-4" style="display: none;">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 sticky top-0 bg-white">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Delete Leave Request</h3>
        </div>
        <form id="deleteForm" method="POST" class="p-4 sm:p-6">
            @csrf
            @method('DELETE')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Deletion (Optional)</label>
                <textarea name="reason" rows="3" class="w-full px-3 py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a reason for deletion..."></textarea>
                <p class="text-xs sm:text-sm text-gray-500 mt-2">Note: This deletion request will be sent to admin for approval.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 sm:space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="w-full sm:w-auto px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm sm:text-base touch-manipulation">
                    Cancel
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm sm:text-base touch-manipulation">
                    Submit Deletion Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    const modal = document.getElementById('createModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function updateEndDateMin() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    if (startDate && endDate && startDate.value) {
        endDate.min = startDate.value;
    }
}

function closeCreateModal() {
    const modal = document.getElementById('createModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function openRejectModal(id) {
    const form = document.getElementById('rejectForm');
    const modal = document.getElementById('rejectModal');
    if (form && modal) {
        form.action = `/hr/leave-request/${id}/reject`;
        modal.style.display = 'flex';
    }
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function openEditModal(id) {
    window.location.href = `/hr/leave-request/${id}/edit`;
}

function openDeleteModal(id) {
    const form = document.getElementById('deleteForm');
    const modal = document.getElementById('deleteModal');
    if (form && modal) {
        form.action = `/hr/leave-request/${id}`;
        modal.style.display = 'flex';
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modals when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const createModal = document.getElementById('createModal');
    const rejectModal = document.getElementById('rejectModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (createModal) {
        createModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });
    }
    
    if (rejectModal) {
        rejectModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    }
    
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    }
    
    // Event delegation for action buttons
    // Reject buttons
    document.querySelectorAll('.reject-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const leaveId = this.getAttribute('data-leave-id');
            if (leaveId) {
                openRejectModal(parseInt(leaveId));
            }
        });
    });
    
    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const leaveId = this.getAttribute('data-leave-id');
            if (leaveId) {
                openEditModal(parseInt(leaveId));
            }
        });
    });
    
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const leaveId = this.getAttribute('data-leave-id');
            if (leaveId) {
                openDeleteModal(parseInt(leaveId));
            }
        });
    });
});
</script>
@endsection




