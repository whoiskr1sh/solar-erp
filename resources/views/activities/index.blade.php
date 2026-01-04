@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Activity Planning & Tracking</h1>
            <p class="text-gray-600">Manage project activities and track progress</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('activities.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                Create Activity
            </a>
            <button onclick="exportActivities()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Export CSV
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Activities</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">In Progress</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Overdue</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Activity code, title..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Status</option>
                    <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Types</option>
                    <option value="task" {{ request('type') == 'task' ? 'selected' : '' }}>Task</option>
                    <option value="milestone" {{ request('type') == 'milestone' ? 'selected' : '' }}>Milestone</option>
                    <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                    <option value="delivery" {{ request('type') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                    <option value="review" {{ request('type') == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                <select name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-6 flex space-x-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                    Apply Filters
                </button>
                <a href="{{ route('activities.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Activities Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-10 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="w-28 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="w-16 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="w-12 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="w-16 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="w-20 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="w-20 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned</th>
                        <th class="w-16 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start</th>
                        <th class="w-16 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End</th>
                        <th class="w-16 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="w-20 px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-2 whitespace-nowrap text-xs font-medium text-gray-900 truncate" title="{{ $activity->activity_code }}">
                            {{ $activity->activity_code }}
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900 truncate" title="{{ $activity->title }}">
                            {{ Str::limit($activity->title, 20) }}
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $activity->type_badge }}">
                                {{ ucfirst($activity->type) }}
                            </span>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $activity->priority_badge }}">
                                {{ ucfirst($activity->priority) }}
                            </span>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $activity->status_badge }}">
                                {{ ucfirst($activity->status) }}
                            </span>
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900 truncate" title="{{ $activity->project->name ?? 'N/A' }}">
                            {{ Str::limit($activity->project->name ?? 'N/A', 15) }}
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900 truncate" title="{{ $activity->assignedTo->name ?? 'Unassigned' }}">
                            {{ Str::limit($activity->assignedTo->name ?? 'Unassigned', 12) }}
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900">
                            {{ $activity->planned_start_date ? $activity->planned_start_date->format('M d') : 'Not set' }}
                        </td>
                        <td class="px-2 py-2 text-xs text-gray-900">
                            {{ $activity->planned_end_date ? $activity->planned_end_date->format('M d') : 'Not set' }}
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 bg-gray-200 rounded-full h-1.5 mr-1">
                                    <div class="h-1.5 rounded-full {{ $activity->progress_bar_color }}" style="width: {{ $activity->progress_percentage }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600">{{ $activity->progress_percentage }}%</span>
                            </div>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap text-xs font-medium">
                            <div class="flex space-x-1">
                                <a href="{{ route('activities.show', $activity) }}" class="text-teal-600 hover:text-teal-900 text-xs">View</a>
                                <a href="{{ route('activities.edit', $activity) }}" class="text-blue-600 hover:text-blue-900 text-xs">Edit</a>
                                @if($activity->status === 'planned')
                                <form method="POST" action="{{ route('activities.mark-in-progress', $activity) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 text-xs">Start</button>
                                </form>
                                @endif
                                @if($activity->status === 'in_progress')
                                <button onclick="showCompleteModal({{ $activity->id }})" class="text-green-600 hover:text-green-900 text-xs">Complete</button>
                                @endif
                                <form method="POST" action="{{ route('activities.destroy', $activity) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this activity? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                            No activities found. <a href="{{ route('activities.create') }}" class="text-teal-600 hover:text-teal-900">Create your first activity</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mark as Completed</h3>
                <form method="POST" id="completeForm">
                    @csrf
                    <div class="mb-4">
                        <label for="completion_notes" class="block text-sm font-medium text-gray-700 mb-2">Completion Notes *</label>
                        <textarea id="completion_notes" name="completion_notes" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Describe how the activity was completed..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideCompleteModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Mark Completed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function exportActivities() {
    const search = document.querySelector('input[name="search"]').value;
    const status = document.querySelector('select[name="status"]').value;
    const priority = document.querySelector('select[name="priority"]').value;
    const type = document.querySelector('select[name="type"]').value;
    const projectId = document.querySelector('select[name="project_id"]').value;
    const assignedTo = document.querySelector('select[name="assigned_to"]').value;
    
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (status) params.append('status', status);
    if (priority) params.append('priority', priority);
    if (type) params.append('type', type);
    if (projectId) params.append('project_id', projectId);
    if (assignedTo) params.append('assigned_to', assignedTo);
    
    const exportUrl = '{{ route("activities.export") }}' + (params.toString() ? '?' + params.toString() : '');
    
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'activities_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function showCompleteModal(activityId) {
    document.getElementById('completeForm').action = '{{ route("activities.mark-completed", ":id") }}'.replace(':id', activityId);
    document.getElementById('completeModal').classList.remove('hidden');
}

function hideCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    document.getElementById('completion_notes').value = '';
}
</script>
@endsection
