@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Commission</h1>
            <p class="text-gray-600">Update commission record for {{ $commission->channelPartner->company_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('commissions.show', $commission) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                View Commission
            </a>
            <a href="{{ route('commissions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Commissions
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('commissions.update', $commission) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Commission Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="channel_partner_id" class="block text-sm font-medium text-gray-700 mb-2">Channel Partner *</label>
                    <select id="channel_partner_id" name="channel_partner_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Channel Partner</option>
                        @foreach($channelPartners as $partner)
                        <option value="{{ $partner->id }}" {{ old('channel_partner_id', $commission->channel_partner_id) == $partner->id ? 'selected' : '' }}>
                            {{ $partner->company_name }} ({{ $partner->commission_rate }}%)
                        </option>
                        @endforeach
                    </select>
                    @error('channel_partner_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="reference_type" class="block text-sm font-medium text-gray-700 mb-2">Reference Type *</label>
                    <select id="reference_type" name="reference_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Reference Type</option>
                        <option value="project" {{ old('reference_type', $commission->reference_type) == 'project' ? 'selected' : '' }}>Project</option>
                        <option value="invoice" {{ old('reference_type', $commission->reference_type) == 'invoice' ? 'selected' : '' }}>Invoice</option>
                        <option value="quotation" {{ old('reference_type', $commission->reference_type) == 'quotation' ? 'selected' : '' }}>Quotation</option>
                        <option value="manual" {{ old('reference_type', $commission->reference_type) == 'manual' ? 'selected' : '' }}>Manual Entry</option>
                    </select>
                    @error('reference_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div id="project_field" style="display: {{ old('reference_type', $commission->reference_type) == 'project' ? 'block' : 'none' }};">
                    <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                    <select id="project_id" name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project_id', $commission->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="invoice_field" style="display: {{ old('reference_type', $commission->reference_type) == 'invoice' ? 'block' : 'none' }};">
                    <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-2">Invoice</label>
                    <select id="invoice_id" name="invoice_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Invoice</option>
                        @foreach($invoices as $invoice)
                        <option value="{{ $invoice->id }}" {{ old('invoice_id', $commission->invoice_id) == $invoice->id ? 'selected' : '' }}>{{ $invoice->invoice_number }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="quotation_field" style="display: {{ old('reference_type', $commission->reference_type) == 'quotation' ? 'block' : 'none' }};">
                    <label for="quotation_id" class="block text-sm font-medium text-gray-700 mb-2">Quotation</label>
                    <select id="quotation_id" name="quotation_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Quotation</option>
                        @foreach($quotations as $quotation)
                        <option value="{{ $quotation->id }}" {{ old('quotation_id', $commission->quotation_id) == $quotation->id ? 'selected' : '' }}>{{ $quotation->quotation_number }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                    <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number', $commission->reference_number) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <p class="text-xs text-gray-500 mt-1">Custom reference number if not linked to existing records</p>
                </div>
            </div>
        </div>

        <!-- Financial Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="base_amount" class="block text-sm font-medium text-gray-700 mb-2">Base Amount (Rs.) *</label>
                    <input type="number" id="base_amount" name="base_amount" value="{{ old('base_amount', $commission->base_amount) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('base_amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%) *</label>
                    <input type="number" id="commission_rate" name="commission_rate" value="{{ old('commission_rate', $commission->commission_rate) }}" step="0.01" min="0" max="100" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('commission_rate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date', $commission->due_date ? $commission->due_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div class="md:col-span-2">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Commission Calculation</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Base Amount:</span>
                                <span id="display_base_amount" class="font-medium text-gray-900">Rs. {{ number_format($commission->base_amount, 2) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Commission Rate:</span>
                                <span id="display_commission_rate" class="font-medium text-gray-900">{{ $commission->commission_rate }}%</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-600">Commission Amount:</span>
                                <span id="display_commission_amount" class="font-medium text-teal-600 text-lg">Rs. {{ number_format($commission->commission_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information (Read-only) -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Paid Amount</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        Rs. {{ number_format($commission->paid_amount, 2) }}
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pending Amount</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        Rs. {{ number_format($commission->pending_amount, 2) }}
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $commission->payment_status_badge }}">
                            {{ ucfirst($commission->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Describe the commission details...">{{ old('description', $commission->description) }}</textarea>
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Additional notes...">{{ old('notes', $commission->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('commissions.show', $commission) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Cancel
            </a>
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                Update Commission
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const referenceTypeSelect = document.getElementById('reference_type');
    const projectField = document.getElementById('project_field');
    const invoiceField = document.getElementById('invoice_field');
    const quotationField = document.getElementById('quotation_field');
    const baseAmountInput = document.getElementById('base_amount');
    const commissionRateInput = document.getElementById('commission_rate');
    const displayBaseAmount = document.getElementById('display_base_amount');
    const displayCommissionRate = document.getElementById('display_commission_rate');
    const displayCommissionAmount = document.getElementById('display_commission_amount');

    // Show/hide reference fields based on type
    function toggleReferenceFields() {
        const value = referenceTypeSelect.value;
        
        // Hide all fields
        projectField.style.display = 'none';
        invoiceField.style.display = 'none';
        quotationField.style.display = 'none';
        
        // Show relevant field
        if (value === 'project') {
            projectField.style.display = 'block';
        } else if (value === 'invoice') {
            invoiceField.style.display = 'block';
        } else if (value === 'quotation') {
            quotationField.style.display = 'block';
        }
    }

    // Calculate commission amount
    function calculateCommission() {
        const baseAmount = parseFloat(baseAmountInput.value) || 0;
        const commissionRate = parseFloat(commissionRateInput.value) || 0;
        const commissionAmount = baseAmount * (commissionRate / 100);
        
        displayBaseAmount.textContent = 'Rs. ' + baseAmount.toLocaleString('en-IN', {minimumFractionDigits: 2});
        displayCommissionRate.textContent = commissionRate + '%';
        displayCommissionAmount.textContent = 'Rs. ' + commissionAmount.toLocaleString('en-IN', {minimumFractionDigits: 2});
    }

    // Event listeners
    referenceTypeSelect.addEventListener('change', toggleReferenceFields);
    baseAmountInput.addEventListener('input', calculateCommission);
    commissionRateInput.addEventListener('input', calculateCommission);

    // Auto-populate commission rate from channel partner
    document.getElementById('channel_partner_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const partnerText = selectedOption.text;
            const rateMatch = partnerText.match(/\((\d+(?:\.\d+)?)%\)/);
            if (rateMatch) {
                commissionRateInput.value = rateMatch[1];
                calculateCommission();
            }
        }
    });

    // Initialize
    toggleReferenceFields();
    calculateCommission();
});
</script>
@endsection
