@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-6">
        <div class="col-12">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Create Purchase Order</h1>
                        <p class="text-blue-100 text-lg">Add a new purchase order to the system</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('purchase-manager.purchase-orders.index') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="row">
        <div class="col-12">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('purchase-manager.purchase-orders.store') }}" method="POST">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">Vendor *</label>
                            <select name="vendor_id" id="vendor_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }} - {{ $vendor->email }}
                                </option>
                                @endforeach
                            </select>
                            @error('vendor_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="order_date" class="block text-sm font-medium text-gray-700 mb-2">Order Date *</label>
                            <input type="date" name="order_date" id="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('order_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date *</label>
                            <input type="date" name="expected_delivery_date" id="expected_delivery_date" value="{{ old('expected_delivery_date') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('expected_delivery_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Purchase Order Items</h3>
                            <button type="button" id="add-item" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Item
                            </button>
                        </div>

                        <div id="items-container">
                            <!-- Item rows will be added dynamically -->
                            <div class="item-row grid grid-cols-1 md:grid-cols-5 gap-4 mb-4 p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                                    <select name="items[0][product_id]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->unit_price }}">
                                            {{ $product->name }} - ₹{{ number_format($product->unit_price, 2) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                                    <input type="number" name="items[0][quantity]" min="1" step="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="1">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price *</label>
                                    <input type="number" name="items[0][unit_price]" min="0" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Price</label>
                                    <input type="number" name="items[0][total_price]" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" placeholder="0.00">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="remove-item bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @error('items')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Section -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <div class="flex justify-end">
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900">
                                    Total: ₹<span id="grand-total">0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('purchase-manager.purchase-orders.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Create Purchase Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    
    // Add item functionality
    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('items-container');
        const template = container.querySelector('.item-row').cloneNode(true);
        
        // Update the template with new index
        template.querySelectorAll('select, input').forEach(element => {
            const name = element.getAttribute('name');
            if (name) {
                element.setAttribute('name', name.replace('[0]', '[' + itemIndex + ']'));
            }
        });
        
        // Clear values
        template.querySelectorAll('input, select').forEach(element => {
            element.value = '';
        });
        
        container.appendChild(template);
        itemIndex++;
        
        // Add event listeners to new row
        addItemEventListeners(template);
    });
    
    // Remove item functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            const itemRow = e.target.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                itemRow.remove();
                calculateTotal();
            }
        }
    });
    
    // Add event listeners to existing items
    document.querySelectorAll('.item-row').forEach(row => {
        addItemEventListeners(row);
    });
    
    function addItemEventListeners(row) {
        const quantityInput = row.querySelector('input[name*="[quantity]"]');
        const unitPriceInput = row.querySelector('input[name*="[unit_price]"]');
        const totalPriceInput = row.querySelector('input[name*="[total_price]"]');
        const productSelect = row.querySelector('select[name*="[product_id]"]');
        
        // Product selection handler
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.dataset.price) {
                unitPriceInput.value = selectedOption.dataset.price;
                calculateItemTotal(row);
            }
        });
        
        // Quantity and unit price change handlers
        quantityInput.addEventListener('input', () => calculateItemTotal(row));
        unitPriceInput.addEventListener('input', () => calculateItemTotal(row));
    }
    
    function calculateItemTotal(row) {
        const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
        const unitPrice = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
        const total = quantity * unitPrice;
        
        row.querySelector('input[name*="[total_price]"]').value = total.toFixed(2);
        calculateTotal();
    }
    
    function calculateTotal() {
        let grandTotal = 0;
        document.querySelectorAll('input[name*="[total_price]"]').forEach(input => {
            grandTotal += parseFloat(input.value) || 0;
        });
        
        document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
    }
});
</script>
@endsection





