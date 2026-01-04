@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Costing</h1>
            <p class="text-gray-600">Update costing details for {{ $costing->project_name }}</p>
        </div>
        <a href="{{ route('costing.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            Back to Costings
        </a>
    </div>

    <form method="POST" action="{{ route('costing.update', $costing) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Project Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Project Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="project_name" class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                    <input type="text" id="project_name" name="project_name" value="{{ old('project_name', $costing->project_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('project_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Link to Project</label>
                    <select id="project_id" name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Project (Optional)</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', $costing->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label for="project_description" class="block text-sm font-medium text-gray-700 mb-2">Project Description</label>
                    <textarea id="project_description" name="project_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('project_description', $costing->project_description) }}</textarea>
                </div>
                
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $costing->location) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label for="validity_date" class="block text-sm font-medium text-gray-700 mb-2">Validity Date</label>
                    <input type="date" id="validity_date" name="validity_date" value="{{ old('validity_date', $costing->validity_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>
        </div>

        <!-- Client Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name *</label>
                    <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $costing->client_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('client_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="client_email" class="block text-sm font-medium text-gray-700 mb-2">Client Email</label>
                    <input type="email" id="client_email" name="client_email" value="{{ old('client_email', $costing->client_email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-2">Client Phone</label>
                    <input type="text" id="client_phone" name="client_phone" value="{{ old('client_phone', $costing->client_phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>
        </div>

        <!-- Cost Breakdown -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cost Breakdown</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="material_cost" class="block text-sm font-medium text-gray-700 mb-2">Material Cost (₹) *</label>
                    <input type="number" id="material_cost" name="material_cost" value="{{ old('material_cost', $costing->material_cost) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('material_cost')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="labor_cost" class="block text-sm font-medium text-gray-700 mb-2">Labor Cost (₹) *</label>
                    <input type="number" id="labor_cost" name="labor_cost" value="{{ old('labor_cost', $costing->labor_cost) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('labor_cost')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="equipment_cost" class="block text-sm font-medium text-gray-700 mb-2">Equipment Cost (₹) *</label>
                    <input type="number" id="equipment_cost" name="equipment_cost" value="{{ old('equipment_cost', $costing->equipment_cost) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('equipment_cost')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="transportation_cost" class="block text-sm font-medium text-gray-700 mb-2">Transportation Cost (₹) *</label>
                    <input type="number" id="transportation_cost" name="transportation_cost" value="{{ old('transportation_cost', $costing->transportation_cost) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('transportation_cost')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="overhead_cost" class="block text-sm font-medium text-gray-700 mb-2">Overhead Cost (₹) *</label>
                    <input type="number" id="overhead_cost" name="overhead_cost" value="{{ old('overhead_cost', $costing->overhead_cost) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('overhead_cost')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">Discount (₹)</label>
                    <input type="number" id="discount" name="discount" value="{{ old('discount', $costing->discount) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>
        </div>

        <!-- Profit & Tax -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Profit & Tax</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="profit_margin" class="block text-sm font-medium text-gray-700 mb-2">Profit Margin (%) *</label>
                    <input type="number" id="profit_margin" name="profit_margin" value="{{ old('profit_margin', $costing->profit_margin) }}" step="0.01" min="0" max="100" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('profit_margin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%) *</label>
                    <input type="number" id="tax_rate" name="tax_rate" value="{{ old('tax_rate', $costing->tax_rate) }}" step="0.01" min="0" max="100" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('tax_rate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Status & Additional Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Status & Additional Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="draft" {{ old('status', $costing->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ old('status', $costing->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $costing->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $costing->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency *</label>
                    <select id="currency" name="currency" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="INR" {{ old('currency', $costing->currency) == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                        <option value="USD" {{ old('currency', $costing->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ old('currency', $costing->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                    </select>
                    @error('currency')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('notes', $costing->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Cost Summary -->
        <div class="bg-teal-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cost Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Material Cost:</span>
                        <span class="text-sm font-medium" id="material-cost-display">₹ {{ number_format($costing->material_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Labor Cost:</span>
                        <span class="text-sm font-medium" id="labor-cost-display">₹ {{ number_format($costing->labor_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Equipment Cost:</span>
                        <span class="text-sm font-medium" id="equipment-cost-display">₹ {{ number_format($costing->equipment_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Transportation Cost:</span>
                        <span class="text-sm font-medium" id="transportation-cost-display">₹ {{ number_format($costing->transportation_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Overhead Cost:</span>
                        <span class="text-sm font-medium" id="overhead-cost-display">₹ {{ number_format($costing->overhead_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Discount:</span>
                        <span class="text-sm font-medium text-red-600" id="discount-display">-₹ {{ number_format($costing->discount, 2) }}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Subtotal:</span>
                        <span class="text-sm font-medium" id="subtotal-display">₹ {{ number_format($costing->material_cost + $costing->labor_cost + $costing->equipment_cost + $costing->transportation_cost + $costing->overhead_cost - $costing->discount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Profit:</span>
                        <span class="text-sm font-medium" id="profit-display">₹ {{ number_format(($costing->material_cost + $costing->labor_cost + $costing->equipment_cost + $costing->transportation_cost + $costing->overhead_cost - $costing->discount) * ($costing->profit_margin / 100), 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tax:</span>
                        <span class="text-sm font-medium" id="tax-display">₹ {{ number_format((($costing->material_cost + $costing->labor_cost + $costing->equipment_cost + $costing->transportation_cost + $costing->overhead_cost - $costing->discount) + (($costing->material_cost + $costing->labor_cost + $costing->equipment_cost + $costing->transportation_cost + $costing->overhead_cost - $costing->discount) * ($costing->profit_margin / 100))) * ($costing->tax_rate / 100), 2) }}</span>
                    </div>
                    <hr class="border-gray-300">
                    <div class="flex justify-between">
                        <span class="text-lg font-semibold text-gray-900">Total Cost:</span>
                        <span class="text-lg font-bold text-teal-600" id="total-cost-display">₹ {{ number_format($costing->total_cost, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('costing.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Cancel
            </a>
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                Update Costing
            </button>
        </div>
    </form>
</div>

<script>
function updateCostSummary() {
    const materialCost = parseFloat(document.getElementById('material_cost').value) || 0;
    const laborCost = parseFloat(document.getElementById('labor_cost').value) || 0;
    const equipmentCost = parseFloat(document.getElementById('equipment_cost').value) || 0;
    const transportationCost = parseFloat(document.getElementById('transportation_cost').value) || 0;
    const overheadCost = parseFloat(document.getElementById('overhead_cost').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const profitMargin = parseFloat(document.getElementById('profit_margin').value) || 0;
    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
    
    // Update individual cost displays
    document.getElementById('material-cost-display').textContent = '₹ ' + materialCost.toFixed(2);
    document.getElementById('labor-cost-display').textContent = '₹ ' + laborCost.toFixed(2);
    document.getElementById('equipment-cost-display').textContent = '₹ ' + equipmentCost.toFixed(2);
    document.getElementById('transportation-cost-display').textContent = '₹ ' + transportationCost.toFixed(2);
    document.getElementById('overhead-cost-display').textContent = '₹ ' + overheadCost.toFixed(2);
    document.getElementById('discount-display').textContent = '-₹ ' + discount.toFixed(2);
    
    // Calculate subtotal
    const subtotal = materialCost + laborCost + equipmentCost + transportationCost + overheadCost - discount;
    document.getElementById('subtotal-display').textContent = '₹ ' + subtotal.toFixed(2);
    
    // Calculate profit
    const profit = subtotal * (profitMargin / 100);
    document.getElementById('profit-display').textContent = '₹ ' + profit.toFixed(2);
    
    // Calculate tax
    const taxableAmount = subtotal + profit;
    const tax = taxableAmount * (taxRate / 100);
    document.getElementById('tax-display').textContent = '₹ ' + tax.toFixed(2);
    
    // Calculate total
    const total = taxableAmount + tax;
    document.getElementById('total-cost-display').textContent = '₹ ' + total.toFixed(2);
}

// Add event listeners to all cost input fields
document.addEventListener('DOMContentLoaded', function() {
    const costInputs = ['material_cost', 'labor_cost', 'equipment_cost', 'transportation_cost', 'overhead_cost', 'discount', 'profit_margin', 'tax_rate'];
    
    costInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', updateCostSummary);
        }
    });
    
    // Initial calculation
    updateCostSummary();
});
</script>
@endsection
