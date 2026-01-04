@extends('layouts.app')

@section('title', 'Create Profitability Report')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Profitability Report</h1>
            <p class="text-gray-600">Add a new project profitability analysis</p>
        </div>
        <a href="{{ route('project-profitabilities.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
            Back to Reports
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('project-profitabilities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Project Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Project Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project <span class="text-red-500">*</span></label>
                        <select name="project_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('project_id') border-red-500 @enderror" required>
                            <option value="">Select a project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }} - {{ $project->client_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Period <span class="text-red-500">*</span></label>
                        <select name="period" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('period') border-red-500 @enderror" required>
                            <option value="">Select period</option>
                            <option value="monthly" {{ old('period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ old('period') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="yearly" {{ old('period') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                        @error('period')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('start_date') border-red-500 @enderror" required>
                            @error('start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date <span class="text-red-500">*</span></label>
                            <input type="date" name = "end_date" value="{{ old('end_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('end_date') border-red-500 @enderror" required>
                            @error('end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Days Completed <span class="text-red-500">*</span></label>
                            <input type="number" name="days_completed" value="{{ old('days_completed') }}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('days_completed') border-red-500 @enderror" required>
                            @error('days_completed')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total Days <span class="text-red-500">*</span></label>
                            <input type="number" name="total_days" value="{{ old('total_days') }}" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('total_days') border-red-500 @enderror" required>
                            @error('total_days')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="reviewed" {{ old('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="final" {{ old('status') == 'final' ? 'selected' : '' }}>Final</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('notes') border-red-500 @enderror" placeholder="Additional notes about this profitability report">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Revenue & Cost Breakdown -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Financial Details</h3>
                    
                    <!-- Revenue -->
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-bold text-green-800 mb-4">Revenue Breakdown</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contract Value</label>
                                <input type="number" name="contract_value" value="{{ old('contract_value') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Progress Billing</label>
                                <input type="number" name="progress_billing" value="{{ old('progress_billing') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Overrun Revenue</label>
                                <input type="number" name="overrun_revenue" value="{{ old('overrun_revenue') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div class="bg-white p-3 rounded border-l-4 border-green-500">
                                <label class="block text-sm font-medium text-gray-700 font-bold">Total Revenue</label>
                                <input type="number" name="total_revenue" value="{{ old('total_revenue') }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <!-- Costs -->
                    <div class="bg-red-50 p-4 rounded-lg">
                        <h4 class="font-bold text-red-800 mb-4">Cost Breakdown</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Material Costs</label>
                                <input type="number" name="material_costs" value="{{ old('material_costs') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Labor Costs</label>
                                <input type="number" name="labor_costs" value="{{ old('labor_costs') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Equipment Costs</label>
                                <input type="number" name="equipment_costs" value="{{ old('equipment_costs') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Transportation Costs</label>
                                <input type="number" name="transportation_costs" value="{{ old('transportation_costs') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Permits Costs</label>
                                <input type="number" name="permits_costs" value="{{ old('permits_costs') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Overhead Costs</label>
                                <input type="number" name="overhead_costs" value="{{ old('overhead_costs') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subcontractor Costs</label>
                                <input type="number" name="subcontractor_costs" value="{{ old('subcontractor_costs') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div class="bg-white p-3 rounded border-l-4 border-red-500">
                                <label class="block text-sm font-medium text-gray-700 font-bold">Total Costs</label>
                                <input type="number" name="total_costs" value="{{ old('total_costs') }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Financial Info -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-bold text-blue-800 mb-4">Additional Financial Info</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Change Order Amount</label>
                                <input type="number" name="change_order_amount" value="{{ old('change_order_amount') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Retention Amount</label>
                                <input type="number" name="retention_amount" value="{{ old('retention_amount') }}" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('project-profitabilities.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-300">
                    Cancel
                </a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg transition duration-300">
                    Create Report
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate total revenue
    const contractValue = document.querySelector('input[name="contract_value"]');
    const progressBilling = document.querySelector('input[name="progress_billing"]');
    const overrunRevenue = document.querySelector('input[name="overrun_revenue"]');
    const totalRevenue = document.querySelector('input[name="total_revenue"]');

    function calculateRevenue() {
        const contract = parseFloat(contractValue.value) || 0;
        const progress = parseFloat(progressBilling.value) || 0;
        const overrun = parseFloat(overrunRevenue.value) || 0;
        const total = contract + progress + overrun;
        totalRevenue.value = total.toFixed(2);
    }

    contractValue.addEventListener('input', calculateRevenue);
    progressBilling.addEventListener('input', calculateRevenue);
    overrunRevenue.addEventListener('input', calculateRevenue);

    // Auto-calculate total costs
    const costInputs = document.querySelectorAll('input[name="material_costs"], input[name="labor_costs"], input[name="equipment_costs"], input[name="transportation_costs"], input[name="permits_costs"], input[name="overhead_costs"], input[name="subcontractor_costs"]');
    const totalCosts = document.querySelector('input[name="total_costs"]');

    function calculateCosts() {
        let total = 0;
        costInputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        totalCosts.value = total.toFixed(2);
    }

    costInputs.forEach(input => {
        input.addEventListener('input', calculateCosts);
    });
});
</script>
@endsection
