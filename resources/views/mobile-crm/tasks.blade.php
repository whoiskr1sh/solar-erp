@extends('layouts.app')

@section('content')
<div class="p-4">
    <!-- Mobile Header -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('mobile-crm.index') }}" class="mr-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">My Tasks</h1>
                    <p class="text-sm text-gray-600">{{ $tasks->total() }} total tasks</p>
                </div>
            </div>
            <a href="{{ route('tasks.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white p-2 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex space-x-1">
            <button class="flex-1 bg-teal-600 text-white px-3 py-2 rounded-lg text-sm font-medium">All</button>
            <button class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium">Pending</button>
            <button class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium">In Progress</button>
            <button class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium">Completed</button>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="space-y-3">
        @forelse($tasks as $task)
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <h3 class="text-lg font-medium text-gray-900">{{ $task->title }}</h3>
                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $task->status_badge }}">
                            {{ ucfirst($task->status) }}
                        </span>
                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $task->priority_badge }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </div>
                    
                    @if($task->description)
                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($task->description, 100) }}</p>
                    @endif

                    @if($task->project)
                    <p class="text-sm text-gray-600 mb-1">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        {{ $task->project->name }}
                    </p>
                    @endif

                    <div class="flex items-center text-xs text-gray-500">
                        @if($task->due_date)
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Due: {{ $task->due_date->format('M d, Y') }}
                        @if($task->is_overdue)
                        <span class="ml-2 text-red-600 font-medium">Overdue</span>
                        @endif
                        @endif
                        
                        @if($task->estimated_hours)
                        <span class="mx-2">•</span>
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $task->estimated_hours }}h estimated
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col space-y-2 ml-4">
                    <a href="{{ route('tasks.show', $task) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 rounded text-sm text-center transition-colors duration-200">
                        View
                    </a>
                    <a href="{{ route('tasks.edit', $task) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm text-center transition-colors duration-200">
                        Edit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tasks found</h3>
            <p class="text-gray-600 mb-4">You don't have any tasks assigned yet.</p>
            <a href="{{ route('tasks.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg">Create Task</a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tasks->hasPages())
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection
