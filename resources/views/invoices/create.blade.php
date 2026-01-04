@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Invoice</h1>
            <p class="mt-2 text-gray-600">Generate a new invoice for your client</p>
        </div>
        <a href="{{ route('invoices.index') }}" class="mt-4 sm:mt-0 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Back to Invoices
        </a>
    </div>

    <!-- Invoice Form -->
    <div class="bg-white rounded-lg shadow-corporate border border-corporate-200 p-6">
        <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
            @csrf
            
            <!-- Invoice Header -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">Invoice Number *</label>
                    <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', 'INV-' . str_pad(\App\Models\Invoice::count() + 1, 6, '0', STR_PAD_LEFT)) }}" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">Invoice Date *</label>
                    <input type="date" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                    <select id="client_id" name="client_id" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} {{ $client->company ? '(' . $client->company . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                    <select id="project_id" name="project_id" class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Items</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate (₹)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (₹)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="itemsTableBody">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="text" name="items[0][description]" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" placeholder="Item description">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" name="items[0][quantity]" value="1" min="1" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" onchange="calculateAmount(this)">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" name="items[0][rate]" step="0.01" min="0" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" onchange="calculateAmount(this)">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" name="items[0][amount]" step="0.01" min="0" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" readonly>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <button type="button" onclick="addItem()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Item
                    </button>
                </div>
            </div>

            <!-- Totals -->
            <div class="mb-6 border-t pt-6">
                <div class="flex justify-end">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                            <span class="text-sm text-gray-900" id="subtotalDisplay">₹0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-700">Tax (18%):</span>
                            <span class="text-sm text-gray-900" id="taxDisplay">₹0.00</span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="text-lg font-bold text-gray-900">Total:</span>
                            <span class="text-lg font-bold text-gray-900" id="totalDisplay">₹0.00</span>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden fields for totals -->
                <input type="hidden" name="subtotal" id="subtotal" value="0">
                <input type="hidden" name="tax_amount" id="tax_amount" value="0">
                <input type="hidden" name="total_amount" id="total_amount" value="0">
            </div>

            <!-- Notes and Terms -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                </div>

                <div>
                    <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                    <textarea id="terms_conditions" name="terms_conditions" rows="4" class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" placeholder="Payment terms and conditions...">{{ old('terms_conditions') }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('invoices.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                    Create Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let itemIndex = 1;

function addItem() {
    const tbody = document.getElementById('itemsTableBody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="text" name="items[${itemIndex}][description]" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" placeholder="Item description">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" onchange="calculateAmount(this)">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" name="items[${itemIndex}][rate]" step="0.01" min="0" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" onchange="calculateAmount(this)">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" name="items[${itemIndex}][amount]" step="0.01" min="0" required class="w-full px-3 py-2 border border-corporate-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500" readonly>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </td>
    `;
    tbody.appendChild(newRow);
    itemIndex++;
}

function removeItem(button) {
    const row = button.closest('tr');
    row.remove();
    calculateTotals();
}

function calculateAmount(input) {
    const row = input.closest('tr');
    const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
    const rate = parseFloat(row.querySelector('input[name*="[rate]"]').value) || 0;
    const amount = quantity * rate;
    
    row.querySelector('input[name*="[amount]"]').value = amount.toFixed(2);
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    const rows = document.querySelectorAll('#itemsTableBody tr');
    
    rows.forEach(row => {
        const amount = parseFloat(row.querySelector('input[name*="[amount]"]').value) || 0;
        subtotal += amount;
    });
    
    const tax = subtotal * 0.18; // 18% tax
    const total = subtotal + tax;
    
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('tax_amount').value = tax.toFixed(2);
    document.getElementById('total_amount').value = total.toFixed(2);
    
    document.getElementById('subtotalDisplay').textContent = '₹' + subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2});
    document.getElementById('taxDisplay').textContent = '₹' + tax.toLocaleString('en-IN', {minimumFractionDigits: 2});
    document.getElementById('totalDisplay').textContent = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
}

// Initialize calculations
document.addEventListener('DOMContentLoaded', function() {
    calculateTotals();
});
</script>
@endsection
