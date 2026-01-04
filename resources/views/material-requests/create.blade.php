@extends('layouts.app')

@section('title', 'Create Material Request')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Material Request</h1>
            <p class="text-gray-600">Submit a new material request for approval</p>
        </div>
        <a href="{{ route('material-requests.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
            Back to Requests
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('material-requests.store') }}" method="POST">
            @csrf
            
            <!-- Request Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Request Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project -->
                <div>
                    <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                    <select id="project_id" name="project_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Select Project (Optional)</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select id="priority" name="priority" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }} selected>Medium</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select id="category" name="category" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="raw_materials" {{ old('category') === 'raw_materials' ? 'selected' : '' }}>Raw Materials</option>
                        <option value="tools_equipment" {{ old('category') === 'tools_equipment' ? 'selected' : '' }}>Tools & Equipment</option>
                        <option value="consumables" {{ old('category') === 'consumables' ? 'selected' : '' }}>Consumables</option>
                        <option value="safety_items" {{ old('category') === 'safety_items' ? 'selected' : '' }}>Safety Items</option>
                        <option value="electrical" {{ old('category') === 'electrical' ? 'selected' : '' }}>Electrical</option>
                        <option value="mechanical" {{ old('category') === 'mechanical' ? 'selected' : '' }}>Mechanical</option>
                        <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Request Type -->
                <div>
                    <label for="request_type" class="block text-sm font-medium text-gray-700 mb-2">Request Type *</label>
                    <select id="request_type" name="request_type" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="purchase" {{ old('request_type') === 'purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="rental" {{ old('request_type') === 'rental' ? 'selected' : '' }}>Rental</option>
                        <option value="transfer" {{ old('request_type') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="emergency" {{ old('request_type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                    </select>
                    @error('request_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Required Date -->
                <div>
                    <label for="required_date" class="block text-sm font-medium text-gray-700 mb-2">Required Date *</label>
                    <input type="date" id="required_date" name="required_date" value="{{ old('required_date', now()->addDays(7)->format('Y-m-d')) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    @error('required_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description & Justification -->
            <div class="mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Request Description *</label>
                        <textarea id="description" name="description" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Justification -->
                    <div>
                        <label for="justification" class="block text-sm font-medium text-gray-700 mb-2">Justification *</label>
                        <textarea id="justification" name="justification" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('justification') }}</textarea>
                        @error('justification')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Material Items Section -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Material Items</h3>
                
                <div id="materials-container">
                    <div class="material-item bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Item Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                                <input type="text" name="materials[0][item_name]" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                                <input type="number" name="materials[0][quantity]" min="1" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>

                            <!-- Unit Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price (₹) *</label>
                                <input type="number" name="materials[0][unit_price]" min="0" step="0.01" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>

                            <!-- Action -->
                            <div class="flex items-end">
                                <button type="button" onclick="removeMaterial(this)" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                                    Remove
                                </button>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="materials[0][description]" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"></textarea>
                        </div>

                        <!-- Specification -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Specification</label>
                            <input type="text" name="materials[0][specification]"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                placeholder="e.g., Model number, size, color, etc.">
                        </div>
                    </div>
                </div>

                <!-- Add Material Button -->
                <button type="button" onclick="addMaterial()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-300">
                    Add Another Material
                </button>
            </div>

            <!-- Additional Options -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Urgency Reason -->
                <div>
                    <label for="urgency_reason" class="block text-sm font-medium text-gray-700 mb-2">Urgency Reason</label>
                    <select id="urgency_reason" name="urgency_reason"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="normal" {{ old('urgency_reason') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="delay_risk" {{ old('urgency_reason') === 'delay_risk' ? 'selected' : '' }}>Delay Risk</option>
                        <option value="deadline_critical" {{ old('urgency_reason') === 'deadline_critical' ? 'selected' : '' }}>Deadline Critical</option>
                        <option value="equipment_failure" {{ old('urgency_reason') === 'equipment_failure' ? 'selected' : '' }}>Equipment Failure</option>
                        <option value="weather_dependent" {{ old('urgency_reason') === 'weather_dependent' ? 'selected' : '' }}>Weather Dependent</option>
                    </select>
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                    <select id="assigned_to" name="assigned_to"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Select Assignee (Optional)</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-8">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('notes') }}</textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3">
                <button type="button" onclick="window.history.back()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-300">
                    Cancel
                </button>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition duration-300">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let materialIndex = 1;

function addMaterial() {
    const container = document.getElementById('materials-container');
    const template = `
        <div class="material-item bg-gray-50 rounded-lg p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Item Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                    <input type="text" name="materials[${materialIndex}][item_name]" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" name="materials[${materialIndex}][quantity]" min="1" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Unit Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price (₹) *</label>
                    <input type="number" name="materials[${materialIndex}][unit_price]" min="0" step="0.01" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Action -->
                <div class="flex items-end">
                    <button type="button" onclick="removeMaterial(this)" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        Remove
                    </button>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="materials[${materialIndex}][description]" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"></textarea>
            </div>

            <!-- Specification -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Specification</label>
                <input type="text" name="materials[${materialIndex}][specification]"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                    placeholder="e.g., Model number, size, color, etc.">
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', template);
    materialIndex++;
}

function removeMaterial(button) {
    button.closest('.material-item').remove();
}
</script>
@endsection
