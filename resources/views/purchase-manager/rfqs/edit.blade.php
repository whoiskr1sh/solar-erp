@extends('layouts.app')

@section('title', 'Edit RFQ')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit RFQ</h1>
                        <p class="mt-2 text-gray-600">Update RFQ information</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('purchase-manager.rfqs.show', $rfq) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>View RFQ
                        </a>
                        <a href="{{ route('purchase-manager.rfqs.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to RFQs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form action="{{ route('purchase-manager.rfqs.update', $rfq) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="rfq_number" class="block text-sm font-medium text-gray-700 mb-2">RFQ Number</label>
                            <input type="text" name="rfq_number" id="rfq_number" value="{{ old('rfq_number', $rfq->rfq_number) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Auto-generated if left empty">
                            @error('rfq_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">Vendor *</label>
                            <select name="vendor_id" id="vendor_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id', $rfq->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }} - {{ $vendor->company_name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                            @error('vendor_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                            <select name="project_id" id="project_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $rfq->project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }} - {{ $project->project_code }}</option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="draft" {{ old('status', $rfq->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="sent" {{ old('status', $rfq->status) === 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="received" {{ old('status', $rfq->status) === 'received' ? 'selected' : '' }}>Received</option>
                                <option value="evaluated" {{ old('status', $rfq->status) === 'evaluated' ? 'selected' : '' }}>Evaluated</option>
                                <option value="awarded" {{ old('status', $rfq->status) === 'awarded' ? 'selected' : '' }}>Awarded</option>
                                <option value="cancelled" {{ old('status', $rfq->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Important Dates</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="rfq_date" class="block text-sm font-medium text-gray-700 mb-2">RFQ Date *</label>
                            <input type="date" name="rfq_date" id="rfq_date" value="{{ old('rfq_date', $rfq->rfq_date ? $rfq->rfq_date->format('Y-m-d') : '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('rfq_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="quotation_due_date" class="block text-sm font-medium text-gray-700 mb-2">Quotation Due Date *</label>
                            <input type="date" name="quotation_due_date" id="quotation_due_date" value="{{ old('quotation_due_date', $rfq->quotation_due_date ? $rfq->quotation_due_date->format('Y-m-d') : '') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('quotation_due_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-2">Valid Until</label>
                            <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until', $rfq->valid_until ? $rfq->valid_until->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('valid_until')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Financial Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="estimated_budget" class="block text-sm font-medium text-gray-700 mb-2">Estimated Budget (₹)</label>
                            <input type="number" name="estimated_budget" id="estimated_budget" value="{{ old('estimated_budget', $rfq->estimated_budget) }}" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('estimated_budget')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Terms and Conditions</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="RFQ description...">{{ old('description', $rfq->description) }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                            <textarea name="terms_conditions" id="terms_conditions" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Terms and conditions...">{{ old('terms_conditions', $rfq->terms_conditions) }}</textarea>
                            @error('terms_conditions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="delivery_terms" class="block text-sm font-medium text-gray-700 mb-2">Delivery Terms</label>
                            <textarea name="delivery_terms" id="delivery_terms" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Delivery terms...">{{ old('delivery_terms', $rfq->delivery_terms) }}</textarea>
                            @error('delivery_terms')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                            <textarea name="payment_terms" id="payment_terms" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Payment terms...">{{ old('payment_terms', $rfq->payment_terms) }}</textarea>
                            @error('payment_terms')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('purchase-manager.rfqs.show', $rfq) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>Update RFQ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection





