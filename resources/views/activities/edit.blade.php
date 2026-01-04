@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Activity</h1>
            <p class="text-gray-600">Update activity #{{ $activity->activity_code }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('activities.show', $activity) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                View Activity
            </a>
            <a href="{{ route('activities.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Activities
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('activities.update', $activity) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $activity->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Activity title">
                        @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Detailed description of the activity">{{ old('description', $activity->description) }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select id="type" name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Type</option>
                            <option value="task" {{ old('type', $activity->type) == 'task' ? 'selected' : '' }}>Task</option>
                            <option value="milestone" {{ old('type', $activity->type) == 'milestone' ? 'selected' : '' }}>Milestone</option>
                            <option value="meeting" {{ old('type', $activity->type) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                            <option value="delivery" {{ old('type', $activity->type) == 'delivery' ? 'selected' : '' }}>Delivery</option>
                            <option value="review" {{ old('type', $activity->type) == 'review' ? 'selected' : '' }}>Review</option>
                            <option value="other" {{ old('type', $activity->type) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select id="priority" name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Priority</option>
                            <option value="low" {{ old('priority', $activity->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $activity->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $activity->priority) == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority', $activity->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="planned" {{ old('status', $activity->status) == 'planned' ? 'selected' : '' }}>Planned</option>
                            <option value="in_progress" {{ old('status', $activity->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $activity->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="on_hold" {{ old('status', $activity->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="cancelled" {{ old('status', $activity->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Project *</label>
                        <select id="project_id" name="project_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $activity->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                        <select id="assigned_to" name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $activity->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Scheduling -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Scheduling</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="planned_start_date" class="block text-sm font-medium text-gray-700 mb-2">Planned Start Date</label>
                        <input type="datetime-local" id="planned_start_date" name="planned_start_date" value="{{ old('planned_start_date', $activity->planned_start_date ? $activity->planned_start_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('planned_start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="planned_end_date" class="block text-sm font-medium text-gray-700 mb-2">Planned End Date</label>
                        <input type="datetime-local" id="planned_end_date" name="planned_end_date" value="{{ old('planned_end_date', $activity->planned_end_date ? $activity->planned_end_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('planned_end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="actual_start_date" class="block text-sm font-medium text-gray-700 mb-2">Actual Start Date</label>
                        <input type="datetime-local" id="actual_start_date" name="actual_start_date" value="{{ old('actual_start_date', $activity->actual_start_date ? $activity->actual_start_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('actual_start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="actual_end_date" class="block text-sm font-medium text-gray-700 mb-2">Actual End Date</label>
                        <input type="datetime-local" id="actual_end_date" name="actual_end_date" value="{{ old('actual_end_date', $activity->actual_end_date ? $activity->actual_end_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('actual_end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Progress & Effort -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Progress & Effort</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="progress_percentage" class="block text-sm font-medium text-gray-700 mb-2">Progress Percentage</label>
                        <input type="number" id="progress_percentage" name="progress_percentage" value="{{ old('progress_percentage', $activity->progress_percentage) }}" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('progress_percentage')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="estimated_hours" class="block text-sm font-medium text-gray-700 mb-2">Estimated Hours</label>
                        <input type="number" id="estimated_hours" name="estimated_hours" value="{{ old('estimated_hours', $activity->estimated_hours) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="0">
                        @error('estimated_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="actual_hours" class="block text-sm font-medium text-gray-700 mb-2">Actual Hours</label>
                        <input type="number" id="actual_hours" name="actual_hours" value="{{ old('actual_hours', $activity->actual_hours) }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="0">
                        @error('actual_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost (Rs.)</label>
                        <input type="number" id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', $activity->estimated_cost) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="0.00">
                        @error('estimated_cost')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="actual_cost" class="block text-sm font-medium text-gray-700 mb-2">Actual Cost (Rs.)</label>
                        <input type="number" id="actual_cost" name="actual_cost" value="{{ old('actual_cost', $activity->actual_cost) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="0.00">
                        @error('actual_cost')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="progress_notes" class="block text-sm font-medium text-gray-700 mb-2">Progress Notes</label>
                    <textarea id="progress_notes" name="progress_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Update on progress...">{{ old('progress_notes', $activity->progress_notes) }}</textarea>
                    @error('progress_notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mt-6">
                    <label for="completion_notes" class="block text-sm font-medium text-gray-700 mb-2">Completion Notes</label>
                    <textarea id="completion_notes" name="completion_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Notes about completion...">{{ old('completion_notes', $activity->completion_notes) }}</textarea>
                    @error('completion_notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deliverables & Criteria -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Deliverables & Criteria</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="deliverables" class="block text-sm font-medium text-gray-700 mb-2">Deliverables</label>
                        <textarea id="deliverables" name="deliverables" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="What will be delivered as part of this activity">{{ old('deliverables', $activity->deliverables) }}</textarea>
                        @error('deliverables')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="acceptance_criteria" class="block text-sm font-medium text-gray-700 mb-2">Acceptance Criteria</label>
                        <textarea id="acceptance_criteria" name="acceptance_criteria" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Criteria for accepting this activity as complete">{{ old('acceptance_criteria', $activity->acceptance_criteria) }}</textarea>
                        @error('acceptance_criteria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Additional notes or comments">{{ old('notes', $activity->notes) }}</textarea>
                        @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags', $activity->tags ? implode(', ', $activity->tags) : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="tag1, tag2, tag3 (comma separated)">
                        @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex space-x-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_milestone" value="1" {{ old('is_milestone', $activity->is_milestone) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ml-2 text-sm text-gray-700">Mark as Milestone</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="is_billable" value="1" {{ old('is_billable', $activity->is_billable) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ml-2 text-sm text-gray-700">Billable Activity</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('activities.show', $activity) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                    Update Activity
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-set end date when start date changes
document.getElementById('planned_start_date').addEventListener('change', function() {
    const startDate = new Date(this.value);
    const endDateInput = document.getElementById('planned_end_date');
    
    if (startDate && !endDateInput.value) {
        // Set end date to 1 day after start date
        startDate.setDate(startDate.getDate() + 1);
        endDateInput.value = startDate.toISOString().slice(0, 16);
    }
});

// Validate end date is after start date
document.getElementById('planned_end_date').addEventListener('change', function() {
    const startDate = document.getElementById('planned_start_date').value;
    const endDate = this.value;
    
    if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
        alert('End date must be after start date');
        this.value = '';
    }
});

// Validate actual end date is after actual start date
document.getElementById('actual_end_date').addEventListener('change', function() {
    const startDate = document.getElementById('actual_start_date').value;
    const endDate = this.value;
    
    if (startDate && endDate && new Date(endDate) <= new Date(startDate)) {
        alert('Actual end date must be after actual start date');
        this.value = '';
    }
});
</script>
@endsection


