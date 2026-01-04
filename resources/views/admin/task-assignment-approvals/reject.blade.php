@extends('layouts.app')

@section('title', 'Reject Task Assignment')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reject Task Assignment</h1>
            <p class="mt-2 text-gray-600">Provide a reason for rejecting this task assignment request</p>
        </div>
        <a href="{{ route('admin.task-assignment-approvals.show', $approval->id) }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <!-- Approval Info -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment Details</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Task</label>
                <p class="text-sm text-gray-900">{{ $approval->task->title }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Requested By</label>
                <p class="text-sm text-gray-900">{{ $approval->requester->name }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                <p class="text-sm text-gray-900">{{ $approval->assignedTo->name }}</p>
            </div>
        </div>
    </div>

    <!-- Reject Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('admin.task-assignment-approvals.reject.post', $approval->id) }}">
            @csrf
            
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Rejection Reason <span class="text-red-500">*</span>
                </label>
                <textarea name="rejection_reason" id="rejection_reason" rows="5" required placeholder="Please provide a reason for rejecting this task assignment..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('rejection_reason') border-red-500 @enderror">{{ old('rejection_reason') }}</textarea>
                @error('rejection_reason')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">This reason will be sent to the requester.</p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.task-assignment-approvals.show', $approval->id) }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    Reject Assignment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

