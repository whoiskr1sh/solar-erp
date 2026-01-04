@extends('layouts.app')

@section('title', 'Edit Material Request')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Material Request</h1>
            <p class="text-gray-600">Update material request information</p>
        </div>
        <a href="{{ route('material-requests.show', $materialRequest) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
            Back to Request
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('material-requests.update', $materialRequest) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Request Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Request Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $materialRequest->title) }}" required
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
                            <option value="{{ $project->id }}" {{ old('project_id', $materialRequest->project_id) == $project->id ? 'selected' : '' }}>
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
                        <option value="low" {{ old('priority', $materialRequest->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $materialRequest->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $materialRequest->priority) === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $materialRequest->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
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
                        <option value="raw_materials" {{ old('category', $materialRequest->category) === 'raw_materials' ? 'selected' : '' }}>Raw Materials</option>
                        <option value="tools_equipment" {{ old('category', $materialRequest->category) === 'tools_equipment' ? 'selected' : '' }}>Tools & Equipment</option>
                        <option value="consumables" {{ old('category', $materialRequest->category) === 'consumables' ? 'selected' : '' }}>Consumables</option>
                        <option value="safety_items" {{ old('category', $materialRequest->category) === 'safety_items' ? 'selected' : '' }}>Safety Items</option>
                        <option value="electrical" {{ old('category', $materialRequest->category) === 'electrical' ? 'selected' : '' }}>Electrical</option>
                        <option value="mechanical" {{ old('category', $materialRequest->category) === 'mechanical' ? 'selected' : '' }}>Mechanical</option>
                        <option value="other" {{ old('category', $materialRequest->category) === 'other' ? 'selected' : '' }}>Other</option>
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
                        <option value="purchase" {{ old('request_type', $materialRequest->request_type) === 'purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="rental" {{ old('request_type', $materialRequest->request_type) === 'rental' ? 'selected' : '' }}>Rental</option>
                        <option value="transfer" {{ old('request_type', $materialRequest->request_type) === 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="emergency" {{ old('request_type', $materialRequest->request_type) === 'emergency' ? 'selected' : '' }}>Emergency</option>
                    </select>
                    @error('request_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Required Date -->
                <div>
                    <label for="required_date" class="block text-sm font-medium text-gray-700 mb-2">Required Date *</label>
                    <input type="date" id="required_date" name="required_date" value="{{ old('required_date', $materialRequest->required_date ? $materialRequest->required_date->format('Y-m-d') : '') }}" required
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
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Request Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('description', $materialRequest->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Justification -->
                    <div>
                        <label for="justification" class="block text-sm font-medium text-gray-700 mb-2">Justification</label>
                        <textarea id="justification" name="justification" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('justification', $materialRequest->justification) }}</textarea>
                        @error('justification')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Options -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Urgency Reason -->
                <div>
                    <label for="urgency_reason" class="block text-sm font-medium text-gray-700 mb-2">Urgency Reason</label>
                    <select id="urgency_reason" name="urgency_reason"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="normal" {{ old('urgency_reason', $materialRequest->urgency_reason) === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="delay_risk" {{ old('urgency_reason', $materialRequest->urgency_reason) === 'delay_risk' ? 'selected' : '' }}>Delay Risk</option>
                        <option value="deadline_critical" {{ old('urgency_reason', $materialRequest->urgency_reason) === 'deadline_critical' ? 'selected' : '' }}>Deadline Critical</option>
                        <option value="equipment_failure" {{ old('urgency_reason', $materialRequest->urgency_reason) === 'equipment_failure' ? 'selected' : '' }}>Equipment Failure</option>
                        <option value="weather_dependent" {{ old('urgency_reason', $materialRequest->urgency_reason) === 'weather_dependent' ? 'selected' : '' }}>Weather Dependent</option>
                    </select>
                    @error('urgency_reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                    <select id="assigned_to" name="assigned_to"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Select Assignee (Optional)</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $materialRequest->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-8">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('notes', $materialRequest->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('material-requests.show', $materialRequest) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-300">
                    Cancel
                </a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition duration-300">
                    Update Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



