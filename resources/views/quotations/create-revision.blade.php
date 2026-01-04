@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Revised Quotation</h1>
            <p class="text-gray-600">Create revision #{{ $nextRevisionNumber }} of {{ $originalQuotation->quotation_number }}</p>
        </div>
        <a href="{{ route('quotations.show', $quotation) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Quotation
        </a>
    </div>

    <!-- Original Quotation Info -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Creating Revision</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p><strong>Original Quotation:</strong> {{ $originalQuotation->quotation_number }}</p>
                    <p><strong>Revision Number:</strong> {{ $nextRevisionNumber }}</p>
                    <p><strong>New Quotation Number:</strong> {{ $revisionQuotationNumber }}</p>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('quotations.store-revision', $quotation) }}" class="space-y-6">
        @csrf
        
        <!-- Quotation Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quotation Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quotation Number *</label>
                    <input type="text" name="quotation_number" value="{{ old('quotation_number', $revisionQuotationNumber) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <p class="text-xs text-gray-500 mt-1">Revision number will be appended</p>
                    @error('quotation_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quotation Date *</label>
                    <input type="date" name="quotation_date" value="{{ old('quotation_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('quotation_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quotation Type *</label>
                    <select name="quotation_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="solar_chakki" {{ old('quotation_type', $originalQuotation->quotation_type) == 'solar_chakki' ? 'selected' : '' }}>Solar Chakki</option>
                        <option value="solar_street_light" {{ old('quotation_type', $originalQuotation->quotation_type) == 'solar_street_light' ? 'selected' : '' }}>Solar Street Light</option>
                        <option value="commercial" {{ old('quotation_type', $originalQuotation->quotation_type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="subsidy_quotation" {{ old('quotation_type', $originalQuotation->quotation_type) == 'subsidy_quotation' ? 'selected' : '' }}>Subsidy Quotation</option>
                    </select>
                    @error('quotation_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valid Until *</label>
                    <input type="date" name="valid_until" value="{{ old('valid_until', $originalQuotation->valid_until->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('valid_until')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Follow-up Date</label>
                    <input type="date" name="follow_up_date" value="{{ old('follow_up_date', $originalQuotation->follow_up_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('follow_up_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Client Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client *</label>
                    <select name="client_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Select Client</option>
                        @foreach($clients as $lead)
                            <option value="{{ $lead->id }}" {{ old('client_id', $originalQuotation->client_id) == $lead->id ? 'selected' : '' }}>
                                {{ $lead->name }} - {{ $lead->company ?: 'No company' }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                    <select name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Select Project (Optional)</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $originalQuotation->project_id) == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quotation Items</h3>
            <div id="items-container">
                <div class="item-row grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item Description</label>
                        <input type="text" name="items[0][description]" placeholder="Item description" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" name="items[0][quantity]" value="1" min="1" step="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rate (₹)</label>
                        <input type="number" name="items[0][rate]" value="0" min="0" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₹)</label>
                        <input type="number" name="items[0][amount]" value="0" min="0" step="0.01" required readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="removeItem(this)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm">Remove</button>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addItem()" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">Add Item</button>
        </div>

        <!-- Pricing -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal (₹)</label>
                    <input type="number" name="subtotal" value="{{ old('subtotal', $originalQuotation->subtotal) }}" min="0" step="0.01" required readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    @error('subtotal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tax Amount (₹)</label>
                    <input type="number" name="tax_amount" value="{{ old('tax_amount', $originalQuotation->tax_amount) }}" min="0" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('tax_amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount (₹)</label>
                    <input type="number" name="total_amount" value="{{ old('total_amount', $originalQuotation->total_amount) }}" min="0" step="0.01" required readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                    @error('total_amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Terms and Conditions</h3>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Additional notes for the quotation...">{{ old('notes', $originalQuotation->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                    <textarea name="terms_conditions" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Terms and conditions for this quotation...">{{ old('terms_conditions', $originalQuotation->terms_conditions) }}</textarea>
                    @error('terms_conditions')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('quotations.show', $quotation) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">Cancel</a>
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">Create Revision</button>
        </div>
    </form>
</div>

<script>
let itemIndex = 1;

function addItem() {
    const container = document.getElementById('items-container');
    const newItem = document.createElement('div');
    newItem.className = 'item-row grid grid-cols-1 md:grid-cols-5 gap-4 mb-4';
    newItem.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Item Description</label>
            <input type="text" name="items[${itemIndex}][description]" placeholder="Item description" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
            <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" step="1" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rate (₹)</label>
            <input type="number" name="items[${itemIndex}][rate]" value="0" min="0" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₹)</label>
            <input type="number" name="items[${itemIndex}][amount]" value="0" min="0" step="0.01" required readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
        </div>
        <div class="flex items-end">
            <button type="button" onclick="removeItem(this)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm">Remove</button>
        </div>
    `;
    container.appendChild(newItem);
    itemIndex++;
    attachItemListeners(newItem);
}

function removeItem(button) {
    button.closest('.item-row').remove();
    calculateTotal();
}

function attachItemListeners(itemRow) {
    const quantityInput = itemRow.querySelector('input[name*="[quantity]"]');
    const rateInput = itemRow.querySelector('input[name*="[rate]"]');
    const amountInput = itemRow.querySelector('input[name*="[amount]"]');
    
    function updateAmount() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const amount = quantity * rate;
        amountInput.value = amount.toFixed(2);
        calculateTotal();
    }
    
    quantityInput.addEventListener('input', updateAmount);
    rateInput.addEventListener('input', updateAmount);
}

function calculateTotal() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const amount = parseFloat(row.querySelector('input[name*="[amount]"]').value) || 0;
        subtotal += amount;
    });
    
    document.querySelector('input[name="subtotal"]').value = subtotal.toFixed(2);
    
    const taxAmount = parseFloat(document.querySelector('input[name="tax_amount"]').value) || 0;
    const totalAmount = subtotal + taxAmount;
    document.querySelector('input[name="total_amount"]').value = totalAmount.toFixed(2);
}

// Add event listeners for calculation
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.item-row').forEach(row => {
        attachItemListeners(row);
    });
    
    document.querySelector('input[name="tax_amount"]').addEventListener('input', calculateTotal);
});
</script>
@endsection

