@extends('layouts.app')

@section('title', 'Edit Material Consumption')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Material Consumption</h1>
            <p class="text-gray-600">Update material consumption record</p>
        </div>
        <a href="{{ route('material-consumptions.show', $materialConsumption) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
            Back to Consumption
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('material-consumptions.update', $materialConsumption) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Material <span class="text-red-500">*</span></label>
                        <select name="material_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('material_id') border-red-500 @enderror">
                            <option value="">Select Material</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" {{ old('material_id', $materialConsumption->material_id) == $material->id ? 'selected' : '' }}>
                                    {{ $material->item_name }} (Available: {{ $material->remaining_quantity }})
                                </option>
                            @endforeach
                        </select>
                        @error('material_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Material Request <span class="text-red-500">*</span></label>
                        <select name="material_request_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('material_request_id') border-red-500 @enderror">
                            <option value="">Select Material Request</option>
                            @foreach(\App\Models\MaterialRequest::where('status', 'approved')->get() as $request)
                                <option value="{{ $request->id }}" {{ old('material_request_id', $materialConsumption->material_request_id) == $request->id ? 'selected' : '' }}>
                                    {{ $request->request_number }} - {{ $request->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('material_request_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                        <select name="project_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('project_id') border-red-500 @enderror">
                            <option value="">Select Project (Optional)</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $materialConsumption->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Activity Type <span class="text-red-500">*</span></label>
                        <select name="activity_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('activity_type') border-red-500 @enderror">
                            <option value="">Select Activity Type</option>
                            <option value="installation" {{ old('activity_type', $materialConsumption->activity_type) == 'installation' ? 'selected' : '' }}>Installation</option>
                            <option value="maintenance" {{ old('activity_type', $materialConsumption->activity_type) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="repair" {{ old('activity_type', $materialConsumption->activity_type) == 'repair' ? 'selected' : '' }}>Repair</option>
                            <option value="testing" {{ old('activity_type', $materialConsumption->activity_type) == 'testing' ? 'selected' : '' }}>Testing</option>
                            <option value="demo" {{ old('activity_type', $materialConsumption->activity_type) == 'demo' ? 'selected' : '' }}>Demo</option>
                            <option value="training" {{ old('activity_type', $materialConsumption->activity_type) == 'training' ? 'selected' : '' }}>Training</option>
                        </select>
                        @error('activity_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Work Phase <span class="text-red-500">*</span></label>
                        <select name="work_phase" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('work_phase') border-red-500 @enderror">
                            <option value="">Select Work Phase</option>
                            <option value="preparation" {{ old('work_phase', $materialConsumption->work_phase) == 'preparation' ? 'selected' : '' }}>Preparation</option>
                            <option value="foundation" {{ old('work_phase', $materialConsumption->work_phase) == 'foundation' ? 'selected' : '' }}>Foundation</option>
                            <option value="structure" {{ old('work_phase', $materialConsumption->work_phase) == 'structure' ? 'selected' : '' }}>Structure</option>
                            <option value="electrical" {{ old('work_phase', $materialConsumption->work_phase) == 'electrical' ? 'selected' : '' }}>Electrical</option>
                            <option value="commissioning" {{ old('work_phase', $materialConsumption->work_phase) == 'commissioning' ? 'selected' : '' }}>Commissioning</option>
                            <option value="maintenance" {{ old('work_phase', $materialConsumption->work_phase) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="other" {{ old('work_phase', $materialConsumption->work_phase) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('work_phase')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Work Location</label>
                        <input type="text" name="work_location" value="{{ old('work_location', $materialConsumption->work_location) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('work_location') border-red-500 @enderror" placeholder="e.g., Site A, Building B, Floor 2">
                        @error('work_location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Quantity & Cost Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Quantity & Cost</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Consumed <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity_consumed" value="{{ old('quantity_consumed', $materialConsumption->quantity_consumed) }}" min="1" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('quantity_consumed') border-red-500 @enderror">
                            @error('quantity_consumed')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit of Measurement</label>
                            <input type="text" name="unit_of_measurement" value="{{ old('unit_of_measurement', $materialConsumption->unit_of_measurement) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('unit_of_measurement') border-red-500 @enderror" placeholder="e.g., pieces, kg, meters">
                            @error('unit_of_measurement')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Consumption % <span class="text-red-500">*</span></label>
                            <input type="number" name="consumption_percentage" value="{{ old('consumption_percentage', $materialConsumption->consumption_percentage) }}" min="0" max="100" step="0.1" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('consumption_percentage') border-red-500 @enderror">
                            @error('consumption_percentage')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Wastage % <span class="text-red-500">*</span></label>
                            <input type="number" name="wastage_percentage" value="{{ old('wastage_percentage', $materialConsumption->wastage_percentage) }}" min="0" max="100" step="0.1" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('wastage_percentage') border-red-500 @enderror">
                            @error('wastage_percentage')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Return % <span class="text-red-500">*</span></label>
                            <input type="number" name="return_percentage" value="{{ old('return_percentage', $materialConsumption->return_percentage) }}" min="0" max="100" step="0.1" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('return_percentage') border-red-500 @enderror">
                            @error('return_percentage')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost <span class="text-red-500">*</span></label>
                        <input type="number" name="unit_cost" value="{{ old('unit_cost', $materialConsumption->unit_cost) }}" step="0.01" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('unit_cost') border-red-500 @enderror" placeholder="0.00">
                        @error('unit_cost')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cost Center</label>
                        <input type="text" name="cost_center" value="{{ old('cost_center', $materialConsumption->cost_center) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('cost_center') border-red-500 @enderror" placeholder="e.g., Project A, Department B">
                        @error('cost_center')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status & Quality -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Status & Quality</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quality Status <span class="text-red-500">*</span></label>
                            <select name="quality_status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('quality_status') border-red-500 @enderror">
                                <option value="">Select Quality Status</option>
                                <option value="good" {{ old('quality_status', $materialConsumption->quality_status) == 'good' ? 'selected' : '' }}>Good</option>
                                <option value="damaged" {{ old('quality_status', $materialConsumption->quality_status) == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                <option value="defective" {{ old('quality_status', $materialConsumption->quality_status) == 'defective' ? 'selected' : '' }}>Defective</option>
                                <option value="expired" {{ old('quality_status', $materialConsumption->quality_status) == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('quality_status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Consumption Status <span class="text-red-500">*</span></label>
                            <select name="consumption_status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('consumption_status') border-red-500 @enderror">
                                <option value="">Select Status</option>
                                <option value="draft" {{ old('consumption_status', $materialConsumption->consumption_status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="in_progress" {{ old('consumption_status', $materialConsumption->consumption_status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('consumption_status', $materialConsumption->consumption_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="partial" {{ old('consumption_status', $materialConsumption->consumption_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="damaged" {{ old('consumption_status', $materialConsumption->consumption_status) == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                <option value="returned" {{ old('consumption_status', $materialConsumption->consumption_status) == 'returned' ? 'selected' : '' }}>Returned</option>
                            </select>
                            @error('consumption_status')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Consumption Date <span class="text-red-500">*</span></label>
                        <input type="date" name="consumption_date" value="{{ old('consumption_date', $materialConsumption->consumption_date ? $materialConsumption->consumption_date->format('Y-m-d') : '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('consumption_date') border-red-500 @enderror">
                        @error('consumption_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                            <input type="time" name="start_time" value="{{ old('start_time', $materialConsumption->start_time) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('start_time') border-red-500 @enderror">
                            @error('start_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                            <input type="time" name="end_time" value="{{ old('end_time', $materialConsumption->end_time) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('end_time') border-red-500 @enderror">
                            @error('end_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- People & Documentation -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">People & Documentation</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Consumed By <span class="text-red-500">*</span></label>
                        <select name="consumed_by" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('consumed_by') border-red-500 @enderror">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('consumed_by', $materialConsumption->consumed_by) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->designation }})
                                </option>
                            @endforeach
                        </select>
                        @error('consumed_by')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supervised By</label>
                        <select name="supervised_by" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('supervised_by') border-red-500 @enderror">
                            <option value="">Select Supervisor (Optional)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('supervised_by', $materialConsumption->supervised_by) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->designation }})
                                </option>
                            @endforeach
                        </select>
                        @error('supervised_by')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Documentation Type</label>
                        <select name="documentation_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('documentation_type') border-red-500 @enderror">
                            <option value="">Select Documentation Type</option>
                            <option value="receipt" {{ old('documentation_type', $materialConsumption->documentation_type) == 'receipt' ? 'selected' : '' }}>Receipt</option>
                            <option value="photo" {{ old('documentation_type', $materialConsumption->documentation_type) == 'photo' ? 'selected' : '' }}>Photo</option>
                            <option value="video" {{ old('documentation_type', $materialConsumption->documentation_type) == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="report" {{ old('documentation_type', $materialConsumption->documentation_type) == 'report' ? 'selected' : '' }}>Report</option>
                        </select>
                        @error('documentation_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Documentation Path</label>
                        <input type="text" name="documentation_path" value="{{ old('documentation_path', $materialConsumption->documentation_path) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('documentation_path') border-red-500 @enderror" placeholder="File path or URL">
                        @error('documentation_path')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description & Notes -->
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Description & Notes</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Activity Description <span class="text-red-500">*</span></label>
                        <textarea name="activity_description" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('activity_description') border-red-500 @enderror" placeholder="Describe the activity and how the material was used...">{{ old('activity_description', $materialConsumption->activity_description) }}</textarea>
                        @error('activity_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('notes') border-red-500 @enderror" placeholder="Additional notes or observations...">{{ old('notes', $materialConsumption->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quality Observations</label>
                        <textarea name="quality_observations" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('quality_observations') border-red-500 @enderror" placeholder="Quality observations and any issues noted...">{{ old('quality_observations', $materialConsumption->quality_observations) }}</textarea>
                        @error('quality_observations')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('material-consumptions.show', $materialConsumption) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition duration-300">
                    Cancel
                    </a>
                    <button type="submit" 
                            class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg transition duration-300">
                    Update Consumption
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-calculate percentages to ensure they don't exceed 100%
document.addEventListener('DOMContentLoaded', function() {
    const consumptionInput = document.querySelector('input[name="consumption_percentage"]');
    const wastageInput = document.querySelector('input[name="wastage_percentage"]');
    const returnInput = document.querySelector('input[name="return_percentage"]');
    
    function validatePercentages() {
        const consumption = parseFloat(consumptionInput.value) || 0;
        const wastage = parseFloat(wastageInput.value) || 0;
        const returnPct = parseFloat(returnInput.value) || 0;
        const total = consumption + wastage + returnPct;
        
        if (total > 100) {
            // Show warning
            const warning = document.getElementById('percentage-warning');
            if (!warning) {
                const warningDiv = document.createElement('div');
                warningDiv.id = 'percentage-warning';
                warningDiv.className = 'text-red-500 text-sm mt-1';
                warningDiv.textContent = 'Total percentages exceed 100%. Please adjust values.';
                consumptionInput.parentNode.appendChild(warningDiv);
            }
        } else {
            const warning = document.getElementById('percentage-warning');
            if (warning) {
                warning.remove();
            }
        }
    }
    
    [consumptionInput, wastageInput, returnInput].forEach(input => {
        input.addEventListener('input', validatePercentages);
    });
});
</script>
@endsection
