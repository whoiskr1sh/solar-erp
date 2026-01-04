@extends('layouts.app')

@section('title', 'Task Details')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
            <p class="mt-2 text-gray-600">
                @if($task->project)
                    Project: {{ $task->project->name }}
                @else
                    Standalone Task
                @endif
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Task
            </a>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Tasks
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Task Details -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Task Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $task->status_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $task->priority_badge }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                    <p class="text-sm text-gray-900">{{ $task->assignedUser->name ?? 'Unassigned' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Created By</label>
                    <p class="text-sm text-gray-900">{{ $task->creator->name ?? 'Unknown' }}</p>
                </div>

                @if($task->project)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                    <p class="text-sm text-gray-900">{{ $task->project->name }}</p>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Created At</label>
                    <p class="text-sm text-gray-900">{{ $task->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>

                @if($task->start_date)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <p class="text-sm text-gray-900">{{ $task->start_date->format('M d, Y') }}</p>
                </div>
                @endif

                @if($task->due_date)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                    <p class="text-sm text-gray-900">
                        {{ $task->due_date->format('M d, Y') }}
                        @if($task->isOverdue())
                            <span class="ml-2 text-red-600 font-medium">(Overdue)</span>
                        @elseif($task->isDueToday())
                            <span class="ml-2 text-yellow-600 font-medium">(Due Today)</span>
                        @endif
                    </p>
                </div>
                @endif

                @if($task->completed_date)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Completed Date</label>
                    <p class="text-sm text-gray-900">{{ $task->completed_date->format('M d, Y \a\t g:i A') }}</p>
                </div>
                @endif

                @if($task->estimated_hours)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Hours</label>
                    <p class="text-sm text-gray-900">{{ $task->estimated_hours }} hours</p>
                </div>
                @endif

                @if($task->actual_hours)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Actual Hours</label>
                    <p class="text-sm text-gray-900">{{ $task->actual_hours }} hours</p>
                </div>
                @endif
            </div>

            @if($task->description)
            <div class="mt-6">
                <h4 class="text-md font-medium text-gray-900 mb-2">Description</h4>
                <div class="prose max-w-none">
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    @if($task->status !== 'completed')
                    <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="w-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Mark as Completed
                        </button>
                    </form>
                    @endif

                    @if($task->status !== 'in_progress' && $task->status !== 'completed')
                    <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="w-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Start Task
                        </button>
                    </form>
                    @endif

                    @if($task->status !== 'pending' && $task->status !== 'completed')
                    <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="w-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="pending">
                        <button type="submit" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Mark as Pending
                        </button>
                    </form>
                    @endif

                    @if($task->status === 'completed')
                    <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="w-full">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Reopen Task
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Task Statistics -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Task Statistics</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Created:</span>
                        <span class="text-sm text-gray-900">{{ $task->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Last Updated:</span>
                        <span class="text-sm text-gray-900">{{ $task->updated_at->diffForHumans() }}</span>
                    </div>

                    @if($task->due_date)
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Days Remaining:</span>
                        <span class="text-sm text-gray-900">
                            @if($task->isOverdue())
                                <span class="text-red-600">{{ $task->due_date->diffInDays(now()) }} days overdue</span>
                            @elseif($task->isDueToday())
                                <span class="text-yellow-600">Due today</span>
                            @else
                                {{ $task->due_date->diffInDays(now()) }} days
                            @endif
                        </span>
                    </div>
                    @endif

                    @if($task->estimated_hours && $task->actual_hours)
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-700">Progress:</span>
                        <span class="text-sm text-gray-900">
                            @php
                                $progress = ($task->actual_hours / $task->estimated_hours) * 100;
                            @endphp
                            {{ number_format($progress, 1) }}%
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Delete Task -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Danger Zone</h3>
                
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                        Delete Task
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
