@extends('layouts.app')

@section('title', 'My To-Do List')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My To-Do List</h1>
            <button onclick="openAddTodoModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Task
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($hasIncompleteTodos)
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                <strong>Note:</strong> You have incomplete tasks. Please complete them before adding new tasks.
            </div>
        @endif

        @if($completedWithoutRemarks > 0)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>You have {{ $completedWithoutRemarks }} completed task(s) without remarks. Please add remarks for completed tasks.</span>
            </div>
        @endif

        @if($notCompletedWithoutReason > 0)
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>You have {{ $notCompletedWithoutReason }} not completed task(s) without reason. Please provide a reason for not completing them.</span>
            </div>
        @endif

        @if($todayTodos->count() > 0)
            <div class="space-y-4">
                @foreach($todayTodos as $todo)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $todo->is_carried_over ? 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-300' : '' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    @if($todo->is_carried_over)
                                        <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded">Carried Over</span>
                                    @endif
                                    @if($todo->assigned_by)
                                        <span class="bg-purple-500 text-white text-xs px-2 py-1 rounded">Assigned</span>
                                    @endif
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($todo->priority === 'high') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200
                                        @elseif($todo->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200
                                        @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200
                                        @endif">
                                        {{ ucfirst($todo->priority) }}
                                    </span>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($todo->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200
                                        @elseif($todo->status === 'not_completed') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200
                                        @elseif($todo->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $todo->title }}</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    Created on: {{ $todo->created_at->format('d M Y, h:i A') }}
                                </p>
                                @if($todo->updated_at && $todo->updated_at->ne($todo->created_at))
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                        Last updated: {{ $todo->updated_at->format('d M Y, h:i A') }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                        Last updated: {{ $todo->created_at->format('d M Y, h:i A') }}
                                    </p>
                                @endif
                                @if($todo->description)
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $todo->description }}</p>
                                @endif
                                @if($todo->assigned_by)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        Assigned by: {{ $todo->assignedBy->name ?? 'Unknown' }}
                                    </p>
                                @endif
                                @if($todo->remarks)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        <strong>Remarks:</strong> {{ $todo->remarks }}
                                    </p>
                                @endif
                                @if($todo->not_completed_reason)
                                    <p class="text-sm text-red-600 dark:text-red-400 mb-2">
                                        <strong>Reason:</strong> {{ $todo->not_completed_reason }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex gap-2 ml-4 flex-shrink-0">
                                @php
                                    $canEditStatus = $todo->status !== 'completed' || ($todo->status === 'completed' && (empty($todo->remarks)));
                                @endphp

                                @if($canEditStatus)
                                    <button onclick="openStatusModal({{ $todo->id }}, '{{ $todo->status }}')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                                        Edit Status
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <p class="mt-4 text-gray-500 dark:text-gray-400">No tasks found. Add your first task to get started!</p>
            </div>
        @endif
    </div>
</div>

<!-- Add Todo Modal -->
<div id="addTodoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Add New Task</h2>
            <button onclick="closeAddTodoModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('todos.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Enter task title...">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Enter task description (optional)..."></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAddTodoModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">Add Task</button>
            </div>
        </form>
    </div>
</div>

@include('todos.partials.status-modal')

<script>
function openAddTodoModal() {
    document.getElementById('addTodoModal').classList.remove('hidden');
}

function closeAddTodoModal() {
    document.getElementById('addTodoModal').classList.add('hidden');
}

function openStatusModal(todoId, currentStatus = null) {
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    const statusSelect = document.getElementById('statusSelect');
    const reasonDiv = document.getElementById('notCompletedReason');
    const reasonField = document.getElementById('not_completed_reason');
    
    if (modal && form) {
        modal.setAttribute('data-todo-id', todoId);
        form.action = '{{ route("todos.update-status", ":id") }}'.replace(':id', todoId);
        
        // Pre-populate status if provided
        if (currentStatus && statusSelect) {
            statusSelect.value = currentStatus;
            // Trigger change event to show/hide reason field
            const event = new Event('change', { bubbles: true });
            statusSelect.dispatchEvent(event);
        }
        
        // Reset form fields
        const remarksField = document.getElementById('remarksField');
        if (remarksField) remarksField.value = '';
        if (reasonField) {
            reasonField.value = '';
            reasonField.required = false;
        }
        
        modal.classList.remove('hidden');
    }
}

function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    if (modal) {
        modal.classList.add('hidden');
        const form = document.getElementById('statusForm');
        if (form) {
            form.reset();
        }
        const reasonDiv = document.getElementById('notCompletedReason');
        if (reasonDiv) {
            reasonDiv.style.display = 'none';
            reasonDiv.classList.add('hidden');
        }
        const reasonField = document.getElementById('not_completed_reason');
        if (reasonField) {
            reasonField.required = false;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('statusSelect');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const reasonDiv = document.getElementById('notCompletedReason');
            const reasonField = document.getElementById('not_completed_reason');
            if (reasonDiv && reasonField) {
                if (this.value === 'not_completed') {
                    reasonDiv.style.display = 'block';
                    reasonDiv.classList.remove('hidden');
                    reasonField.required = true;
                } else {
                    reasonDiv.style.display = 'none';
                    reasonDiv.classList.add('hidden');
                    reasonField.required = false;
                    reasonField.value = '';
                }
            }
        });
    }
});
</script>
@endsection
