@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit RFQ</h1>
                    <p class="text-gray-600 mt-1">Update RFQ details and items</p>
                </div>
                <a href="{{ route('rfqs.show', $rfq) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Details
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form method="POST" action="{{ route('rfqs.update', $rfq) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                        <select name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Project (Optional)</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $rfq->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RFQ Date</label>
                        <input type="date" name="rfq_date" value="{{ old('rfq_date', $rfq->rfq_date->format('Y-m-d')) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('rfq_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quotation Due Date</label>
                        <input type="date" name="quotation_due_date" value="{{ old('quotation_due_date', $rfq->quotation_due_date->format('Y-m-d')) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('quotation_due_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Budget</label>
                        <input type="number" name="estimated_budget" step="0.01" min="0" value="{{ old('estimated_budget', $rfq->estimated_budget) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                               placeholder="0.00">
                        @error('estimated_budget')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="draft" {{ old('status', $rfq->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent" {{ old('status', $rfq->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="received" {{ old('status', $rfq->status) == 'received' ? 'selected' : '' }}>Received</option>
                            <option value="evaluated" {{ old('status', $rfq->status) == 'evaluated' ? 'selected' : '' }}>Evaluated</option>
                            <option value="awarded" {{ old('status', $rfq->status) == 'awarded' ? 'selected' : '' }}>Awarded</option>
                            <option value="cancelled" {{ old('status', $rfq->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                              placeholder="Describe the RFQ requirements...">{{ old('description', $rfq->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                              placeholder="Additional notes...">{{ old('notes', $rfq->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Items -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Items</h3>
                <div id="items-container">
                    @foreach($rfq->items as $index => $item)
                        <div class="item-row border border-gray-200 rounded-lg p-4 mb-4">
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                                    <input type="text" name="items[{{ $index }}][item_name]" value="{{ old('items.'.$index.'.item_name', $item->item_name) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                           placeholder="Enter item name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                    <input type="number" name="items[{{ $index }}][quantity]" min="1" value="{{ old('items.'.$index.'.quantity', $item->quantity) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                    <select name="items[{{ $index }}][unit]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                        <option value="pcs" {{ old('items.'.$index.'.unit', $item->unit) == 'pcs' ? 'selected' : '' }}>Pieces</option>
                                        <option value="kg" {{ old('items.'.$index.'.unit', $item->unit) == 'kg' ? 'selected' : '' }}>Kilograms</option>
                                        <option value="meters" {{ old('items.'.$index.'.unit', $item->unit) == 'meters' ? 'selected' : '' }}>Meters</option>
                                        <option value="sqft" {{ old('items.'.$index.'.unit', $item->unit) == 'sqft' ? 'selected' : '' }}>Square Feet</option>
                                        <option value="liters" {{ old('items.'.$index.'.unit', $item->unit) == 'liters' ? 'selected' : '' }}>Liters</option>
                                        <option value="tons" {{ old('items.'.$index.'.unit', $item->unit) == 'tons' ? 'selected' : '' }}>Tons</option>
                                        <option value="other" {{ old('items.'.$index.'.unit', $item->unit) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <input type="text" name="items[{{ $index }}][description]" value="{{ old('items.'.$index.'.description', $item->description) }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                           placeholder="Item description">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Specifications</label>
                                <textarea name="items[{{ $index }}][specifications]" rows="2" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                          placeholder="Technical specifications...">{{ old('items.'.$index.'.specifications', $item->specifications) }}</textarea>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                                <input type="text" name="items[{{ $index }}][remarks]" value="{{ old('items.'.$index.'.remarks', $item->remarks) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                       placeholder="Additional remarks">
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="button" class="remove-item bg-red-100 text-red-700 px-3 py-1 rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash mr-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <button type="button" id="add-item" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Item
                </button>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('rfqs.show', $rfq) }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Update RFQ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = {{ $rfq->items->count() }};
    
    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const newItem = document.createElement('div');
        newItem.className = 'item-row border border-gray-200 rounded-lg p-4 mb-4';
        newItem.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                    <input type="text" name="items[${itemCount}][item_name]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                           placeholder="Enter item name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" name="items[${itemCount}][quantity]" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                           placeholder="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                    <select name="items[${itemCount}][unit]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="pcs">Pieces</option>
                        <option value="kg">Kilograms</option>
                        <option value="meters">Meters</option>
                        <option value="sqft">Square Feet</option>
                        <option value="liters">Liters</option>
                        <option value="tons">Tons</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <input type="text" name="items[${itemCount}][description]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                           placeholder="Item description">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Specifications</label>
                <textarea name="items[${itemCount}][specifications]" rows="2" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                          placeholder="Technical specifications..."></textarea>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                <input type="text" name="items[${itemCount}][remarks]" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                       placeholder="Additional remarks">
            </div>
            <div class="mt-4 flex justify-end">
                <button type="button" class="remove-item bg-red-100 text-red-700 px-3 py-1 rounded-lg hover:bg-red-200 transition-colors">
                    <i class="fas fa-trash mr-1"></i>Remove
                </button>
            </div>
        `;
        
        container.appendChild(newItem);
        itemCount++;
        
        // Add remove functionality
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            newItem.remove();
        });
    });
    
    // Add remove functionality to existing items
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.item-row').remove();
        });
    });
});
</script>
@endsection








