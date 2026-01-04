@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Resource Allocation</h1>
            <p class="text-gray-600 mt-2">Allocate resources to projects and activities</p>
        </div>
        <a href="{{ route('resource-allocations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Allocations
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Allocation Details</h6>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('resource-allocations.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('title') border-red-500 @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="resource_type" class="block text-sm font-medium text-gray-700 mb-2">Resource Type <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('resource_type') border-red-500 @enderror" 
                                        id="resource_type" name="resource_type" required onchange="toggleCostFields()">
                                    <option value="">Select Type</option>
                                    <option value="human" {{ old('resource_type') == 'human' ? 'selected' : '' }}>Human</option>
                                    <option value="equipment" {{ old('resource_type') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                    <option value="material" {{ old('resource_type') == 'material' ? 'selected' : '' }}>Material</option>
                                    <option value="financial" {{ old('resource_type') == 'financial' ? 'selected' : '' }}>Financial</option>
                                    <option value="other" {{ old('resource_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('resource_type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('description') border-red-500 @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('priority') border-red-500 @enderror" 
                                        id="priority" name="priority" required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                                @error('priority')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Project <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('project_id') border-red-500 @enderror" 
                                        id="project_id" name="project_id" required onchange="loadActivities()">
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="activity_id" class="block text-sm font-medium text-gray-700 mb-2">Activity</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('activity_id') border-red-500 @enderror" 
                                        id="activity_id" name="activity_id">
                                    <option value="">Select Activity</option>
                                </select>
                                @error('activity_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="allocated_to" class="block text-sm font-medium text-gray-700 mb-2">Allocated To</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('allocated_to') border-red-500 @enderror" 
                                        id="allocated_to" name="allocated_to">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('allocated_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('allocated_to')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="resource_name" class="block text-sm font-medium text-gray-700 mb-2">Resource Name <span class="text-red-500">*</span></label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('resource_name') border-red-500 @enderror" 
                                       id="resource_name" name="resource_name" value="{{ old('resource_name') }}" required>
                                @error('resource_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="resource_category" class="block text-sm font-medium text-gray-700 mb-2">Resource Category</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('resource_category') border-red-500 @enderror" 
                                       id="resource_category" name="resource_category" value="{{ old('resource_category') }}">
                                @error('resource_category')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="resource_specifications" class="block text-sm font-medium text-gray-700 mb-2">Resource Specifications</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('resource_specifications') border-red-500 @enderror" 
                                      id="resource_specifications" name="resource_specifications" rows="2">{{ old('resource_specifications') }}</textarea>
                            @error('resource_specifications')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="allocation_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('allocation_start_date') border-red-500 @enderror" 
                                       id="allocation_start_date" name="allocation_start_date" value="{{ old('allocation_start_date') }}">
                                @error('allocation_start_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="allocation_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('allocation_end_date') border-red-500 @enderror" 
                                       id="allocation_end_date" name="allocation_end_date" value="{{ old('allocation_end_date') }}">
                                @error('allocation_end_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <div>
                                <label for="allocated_quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('allocated_quantity') border-red-500 @enderror" 
                                       id="allocated_quantity" name="allocated_quantity" value="{{ old('allocated_quantity', 1) }}" required onchange="calculateCost()">
                                @error('allocated_quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="quantity_unit" class="block text-sm font-medium text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('quantity_unit') border-red-500 @enderror" 
                                       id="quantity_unit" name="quantity_unit" value="{{ old('quantity_unit', 'units') }}" required>
                                @error('quantity_unit')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="budget_allocated" class="block text-sm font-medium text-gray-700 mb-2">Budget Allocated</label>
                                <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('budget_allocated') border-red-500 @enderror" 
                                       id="budget_allocated" name="budget_allocated" value="{{ old('budget_allocated') }}">
                                @error('budget_allocated')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Cost Fields -->
                        <div id="cost-fields" class="mt-6" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate (Rs.)</label>
                                    <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('hourly_rate') border-red-500 @enderror" 
                                           id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" onchange="calculateCost()">
                                    @error('hourly_rate')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="unit_cost" class="block text-sm font-medium text-gray-700 mb-2">Unit Cost (Rs.)</label>
                                    <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('unit_cost') border-red-500 @enderror" 
                                           id="unit_cost" name="unit_cost" value="{{ old('unit_cost') }}" onchange="calculateCost()">
                                    @error('unit_cost')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Cost</label>
                                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md" id="estimated_cost_display">Rs. 0.00</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('notes') border-red-500 @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 @error('tags') border-red-500 @enderror" 
                                       id="tags" name="tags" value="{{ old('tags') }}" placeholder="Enter tags separated by commas">
                                @error('tags')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded" type="checkbox" id="is_critical" name="is_critical" value="1" {{ old('is_critical') ? 'checked' : '' }}>
                                <label for="is_critical" class="ml-2 block text-sm text-gray-900">
                                    Critical Resource
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded" type="checkbox" id="is_billable" name="is_billable" value="1" {{ old('is_billable', true) ? 'checked' : '' }}>
                                <label for="is_billable" class="ml-2 block text-sm text-gray-900">
                                    Billable Resource
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('resource-allocations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Cancel</a>
                            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create Allocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-teal-600">Quick Info</h6>
                </div>
                <div class="p-6">
                    <div class="mb-6">
                        <h6 class="font-semibold text-gray-900 mb-3">Resource Types</h6>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-2"></span><span class="font-medium text-blue-600">Human</span> - People, skills, expertise</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-cyan-500 rounded-full mr-2"></span><span class="font-medium text-cyan-600">Equipment</span> - Machinery, tools, devices</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-2"></span><span class="font-medium text-green-600">Material</span> - Raw materials, supplies</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mr-2"></span><span class="font-medium text-yellow-600">Financial</span> - Budget, funds, capital</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-gray-500 rounded-full mr-2"></span><span class="font-medium text-gray-600">Other</span> - Miscellaneous resources</li>
                        </ul>
                    </div>
                    
                    <div class="mb-6">
                        <h6 class="font-semibold text-gray-900 mb-3">Priority Levels</h6>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-2"></span><span class="font-medium text-green-600">Low</span> - Can be delayed</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mr-2"></span><span class="font-medium text-yellow-600">Medium</span> - Normal priority</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-2"></span><span class="font-medium text-red-600">High</span> - Important, urgent</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-gray-800 rounded-full mr-2"></span><span class="font-medium text-gray-800">Critical</span> - Blocking, must complete</li>
                        </ul>
                    </div>

                    <div class="mb-6">
                        <h6 class="font-semibold text-gray-900 mb-3">Allocation Status</h6>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-cyan-500 rounded-full mr-2"></span><span class="font-medium text-cyan-600">Planned</span> - Initial planning stage</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-yellow-500 rounded-full mr-2"></span><span class="font-medium text-yellow-600">Allocated</span> - Resource assigned</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-2"></span><span class="font-medium text-green-600">In Use</span> - Currently being used</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-gray-500 rounded-full mr-2"></span><span class="font-medium text-gray-600">Completed</span> - Work finished</li>
                            <li class="flex items-center"><span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-2"></span><span class="font-medium text-red-600">Cancelled</span> - Allocation cancelled</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCostFields() {
    const resourceType = document.getElementById('resource_type').value;
    const costFields = document.getElementById('cost-fields');
    const hourlyRateField = document.getElementById('hourly_rate');
    const unitCostField = document.getElementById('unit_cost');
    
    if (resourceType === 'human') {
        costFields.style.display = 'block';
        hourlyRateField.required = true;
        unitCostField.required = false;
    } else if (resourceType === 'equipment' || resourceType === 'material') {
        costFields.style.display = 'block';
        hourlyRateField.required = false;
        unitCostField.required = true;
    } else {
        costFields.style.display = 'none';
        hourlyRateField.required = false;
        unitCostField.required = false;
    }
    
    calculateCost();
}

function calculateCost() {
    const resourceType = document.getElementById('resource_type').value;
    const quantity = parseFloat(document.getElementById('allocated_quantity').value) || 0;
    const hourlyRate = parseFloat(document.getElementById('hourly_rate').value) || 0;
    const unitCost = parseFloat(document.getElementById('unit_cost').value) || 0;
    
    let estimatedCost = 0;
    
    if (resourceType === 'human') {
        estimatedCost = quantity * hourlyRate;
    } else if (resourceType === 'equipment' || resourceType === 'material') {
        estimatedCost = quantity * unitCost;
    }
    
    document.getElementById('estimated_cost_display').textContent = 'Rs. ' + estimatedCost.toFixed(2);
}

function loadActivities() {
    const projectId = document.getElementById('project_id').value;
    const activitySelect = document.getElementById('activity_id');
    
    // Clear existing options
    activitySelect.innerHTML = '<option value="">Select Activity</option>';
    
    if (projectId) {
        // In a real application, you would make an AJAX call to fetch activities
        // For now, we'll show a placeholder
        activitySelect.innerHTML += '<option value="">Loading activities...</option>';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCostFields();
    calculateCost();
});
</script>
@endsection


