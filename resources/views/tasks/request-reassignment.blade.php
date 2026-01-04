@extends('layouts.app')

@section('title', 'Request Task Reassignment')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Request Task Reassignment</h1>
            <p class="mt-2 text-gray-600">Request approval to reassign this task to another team member</p>
        </div>
        <a href="{{ route('project-manager.tasks.show', $task) }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Task
        </a>
    </div>

    @if(isset($hasPendingRequest) && $hasPendingRequest)
        <!-- Pending Request Alert -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        You already have a pending reassignment request for this task.
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Please wait for approval or rejection before creating a new request.</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Info Alert -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Task: {{ $task->title }}
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        @if(isset($isSubCoordinator) && $isSubCoordinator)
                            <p>This request will be sent to <strong>Sales Manager</strong> for approval.</p>
                        @elseif(isset($isSalesManager) && $isSalesManager)
                            <p>This request will be sent to <strong>Admin</strong> for approval.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Request Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('tasks.request-reassignment.store', $task) }}" @if(isset($hasPendingRequest) && $hasPendingRequest) onsubmit="return false;" @endif>
            @csrf
            
            <div class="space-y-6">
                <!-- Task Info -->
                <div class="bg-gray-50 rounded-md p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Task Information</h4>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Task Title:</span>
                            <span class="font-medium">{{ $task->title }}</span>
                        </div>
                        @if($task->project)
                        <div class="flex justify-between">
                            <span>Project:</span>
                            <span class="font-medium">{{ $task->project->name }}</span>
                        </div>
                        @endif
                        @if($task->assignedUser)
                        <div class="flex justify-between">
                            <span>Currently Assigned To:</span>
                            <span class="font-medium">{{ $task->assignedUser->name }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span class="font-medium capitalize">{{ str_replace('_', ' ', $task->status) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Assign To -->
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">
                        Assign Task To <span class="text-red-500">*</span>
                    </label>
                    <select name="assigned_to" id="assigned_to" required @if(isset($hasPendingRequest) && $hasPendingRequest) disabled @endif class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('assigned_to') border-red-500 @enderror @if(isset($hasPendingRequest) && $hasPendingRequest) bg-gray-100 cursor-not-allowed @endif">
                        <option value="">Select Team Member</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                                @if($user->hasRole('SALES MANAGER'))
                                    (SALES MANAGER)
                                @elseif($user->hasRole('SUPER ADMIN'))
                                    (ADMIN)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @if(isset($isSubCoordinator) && $isSubCoordinator)
                        <p class="text-xs text-gray-500 mt-1">You can only request reassignment to Sales Manager.</p>
                    @elseif(isset($isSalesManager) && $isSalesManager)
                        <p class="text-xs text-gray-500 mt-1">You can only request reassignment to Admin.</p>
                    @endif
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Reassignment <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="5" required @if(isset($hasPendingRequest) && $hasPendingRequest) disabled @endif placeholder="Please provide a reason for requesting reassignment (e.g., workload, unavailable, etc.)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('reason') border-red-500 @enderror @if(isset($hasPendingRequest) && $hasPendingRequest) bg-gray-100 cursor-not-allowed @endif">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">This reason will be reviewed by the approver.</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('project-manager.tasks.show', $task) }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" @if(isset($hasPendingRequest) && $hasPendingRequest) disabled @endif class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white @if(isset($hasPendingRequest) && $hasPendingRequest) bg-gray-400 cursor-not-allowed @else bg-teal-600 hover:bg-teal-700 @endif">
                    @if(isset($hasPendingRequest) && $hasPendingRequest)
                        Request Pending
                    @else
                        Submit Request
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

