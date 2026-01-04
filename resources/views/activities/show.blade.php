@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $activity->title }}</h1>
            <p class="text-gray-600">Activity #{{ $activity->activity_code }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('activities.edit', $activity) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Edit Activity
            </a>
            <a href="{{ route('activities.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Activities
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $activity->type_badge }}">
                            {{ ucfirst($activity->type) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $activity->priority_badge }}">
                            {{ ucfirst($activity->priority) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $activity->status_badge }}">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Milestone</label>
                        @if($activity->is_milestone)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Yes</span>
                        @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                        @endif
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $activity->description ?? 'No description provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Project Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Project Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                        <p class="text-gray-900">{{ $activity->project->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                        <p class="text-gray-900">{{ $activity->assignedTo->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                        <p class="text-gray-900">{{ $activity->creator->name }}</p>
                    </div>
                    @if($activity->approver)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                        <p class="text-gray-900">{{ $activity->approver->name }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Planned Start</label>
                        <p class="text-gray-900">{{ $activity->formatted_planned_start_date }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Planned End</label>
                        <p class="text-gray-900">{{ $activity->formatted_planned_end_date }}</p>
                        @if($activity->is_overdue)
                        <p class="text-sm text-red-600">Overdue by {{ $activity->days_overdue }} days</p>
                        @elseif($activity->is_due_soon)
                        <p class="text-sm text-yellow-600">Due in {{ $activity->days_remaining }} days</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Actual Start</label>
                        <p class="text-gray-900">{{ $activity->formatted_actual_start_date }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Actual End</label>
                        <p class="text-gray-900">{{ $activity->formatted_actual_end_date }}</p>
                    </div>
                </div>
            </div>

            <!-- Progress Tracking -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Tracking</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-700">Progress</label>
                            <span class="text-sm text-gray-600">{{ $activity->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full {{ $activity->progress_bar_color }}" style="width: {{ $activity->progress_percentage }}%"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Hours</label>
                            <p class="text-gray-900">{{ $activity->estimated_hours }} hours</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Actual Hours</label>
                            <p class="text-gray-900">{{ $activity->actual_hours }} hours</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Cost</label>
                            <p class="text-gray-900">{{ $activity->formatted_estimated_cost }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Actual Cost</label>
                            <p class="text-gray-900">{{ $activity->formatted_actual_cost }}</p>
                        </div>
                    </div>
                    
                    @if($activity->progress_notes)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Progress Notes</label>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $activity->progress_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Deliverables & Criteria -->
            @if($activity->deliverables || $activity->acceptance_criteria)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Deliverables & Criteria</h3>
                <div class="space-y-4">
                    @if($activity->deliverables)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deliverables</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $activity->deliverables }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($activity->acceptance_criteria)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Acceptance Criteria</label>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $activity->acceptance_criteria }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Completion Notes -->
            @if($activity->completion_notes)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Completion Notes</h3>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $activity->completion_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Additional Notes -->
            @if($activity->notes)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Notes</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $activity->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($activity->status === 'planned')
                    <form method="POST" action="{{ route('activities.mark-in-progress', $activity) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm">
                            Mark In Progress
                        </button>
                    </form>
                    @endif
                    
                    @if($activity->status === 'in_progress')
                    <button onclick="showCompleteModal()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                        Mark Completed
                    </button>
                    <button onclick="showProgressModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        Update Progress
                    </button>
                    @endif
                    
                    @if($activity->status === 'completed')
                    <div class="text-center text-green-600 text-sm font-medium">
                        ✓ Activity Completed
                    </div>
                    @endif
                    
                    <button onclick="showHoursModal()" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                        Add Hours
                    </button>
                    
                    <button onclick="showCostModal()" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm">
                        Add Cost
                    </button>
                    
                    @if(!$activity->approved_by)
                    <form method="POST" action="{{ route('activities.approve', $activity) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm">
                            Approve Activity
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Activity Details -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Activity Details</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Billable</span>
                        @if($activity->is_billable)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                        @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Created</span>
                        <span class="text-sm text-gray-900">{{ $activity->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ $activity->updated_at->format('M d, Y') }}</span>
                    </div>
                    
                    @if($activity->approved_at)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Approved</span>
                        <span class="text-sm text-gray-900">{{ $activity->approved_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tags -->
            @if($activity->tags && count($activity->tags) > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($activity->tags as $tag)
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Complete Modal -->
<div id="completeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mark as Completed</h3>
                <form method="POST" action="{{ route('activities.mark-completed', $activity) }}">
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

<!-- Progress Modal -->
<div id="progressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Progress</h3>
                <form method="POST" action="{{ route('activities.update-progress', $activity) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="progress_percentage" class="block text-sm font-medium text-gray-700 mb-2">Progress Percentage *</label>
                        <input type="number" id="progress_percentage" name="progress_percentage" min="0" max="100" value="{{ $activity->progress_percentage }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    <div class="mb-4">
                        <label for="progress_notes" class="block text-sm font-medium text-gray-700 mb-2">Progress Notes</label>
                        <textarea id="progress_notes" name="progress_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Update on progress..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideProgressModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Update Progress
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hours Modal -->
<div id="hoursModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Hours</h3>
                <form method="POST" action="{{ route('activities.add-hours', $activity) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="hours" class="block text-sm font-medium text-gray-700 mb-2">Hours Worked *</label>
                        <input type="number" id="hours" name="hours" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Enter hours">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideHoursModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                            Add Hours
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cost Modal -->
<div id="costModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Cost</h3>
                <form method="POST" action="{{ route('activities.add-cost', $activity) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">Cost (Rs.) *</label>
                        <input type="number" id="cost" name="cost" min="0" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Enter cost">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideCostModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg">
                            Add Cost
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showCompleteModal() {
    document.getElementById('completeModal').classList.remove('hidden');
}

function hideCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    document.getElementById('completion_notes').value = '';
}

function showProgressModal() {
    document.getElementById('progressModal').classList.remove('hidden');
}

function hideProgressModal() {
    document.getElementById('progressModal').classList.add('hidden');
    document.getElementById('progress_notes').value = '';
}

function showHoursModal() {
    document.getElementById('hoursModal').classList.remove('hidden');
}

function hideHoursModal() {
    document.getElementById('hoursModal').classList.add('hidden');
    document.getElementById('hours').value = '';
}

function showCostModal() {
    document.getElementById('costModal').classList.remove('hidden');
}

function hideCostModal() {
    document.getElementById('costModal').classList.add('hidden');
    document.getElementById('cost').value = '';
}
</script>
@endsection


