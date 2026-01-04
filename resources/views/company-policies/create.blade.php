@extends('layouts.app')

@section('title', 'Create Company Policy')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Company Policy</h1>
            <p class="text-gray-600">Create a new company policy or procedure</p>
        </div>
        <a href="{{ route('company-policies.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
            Back to Policies
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('company-policies.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Policy Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Policy Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                               placeholder="Enter policy title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Brief description of the policy">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            <option value="hr_policies" {{ old('category') == 'hr_policies' ? 'selected' : '' }}>HR Policies</option>
                            <option value="safety_policies" {{ old('category') == 'safety_policies' ? 'selected' : '' }}>Safety Policies</option>
                            <option value="it_policies" {{ old('category') == 'it_policies' ? 'selected' : '' }}>IT Policies</option>
                            <option value="financial_policies" {{ old('category') == 'financial_policies' ? 'selected' : '' }}>Financial Policies</option>
                            <option value="operational_policies" {{ old('category') == 'operational_policies' ? 'selected' : '' }}>Operational Policies</option>
                            <option value="quality_policies" {{ old('category') == 'quality_policies' ? 'selected' : '' }}>Quality Policies</option>
                            <option value="environmental_policies" {{ old('category') == 'environmental_policies' ? 'selected' : '' }}>Environmental Policies</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="priority" name="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                            <option value="">Select Priority</option>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Version -->
                    <div>
                        <label for="version" class="block text-sm font-medium text-gray-700 mb-2">
                            Version
                        </label>
                        <input type="text" id="version" name="version" value="{{ old('version', '1.0') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('version') border-red-500 @enderror"
                               placeholder="e.g., 1.0">
                        @error('version')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Effective Date -->
                    <div>
                        <label for="effective_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Effective Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="effective_date" name="effective_date" value="{{ old('effective_date', date('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('effective_date') border-red-500 @enderror">
                        @error('effective_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Review Date -->
                    <div>
                        <label for="review_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Review Date
                        </label>
                        <input type="date" id="review_date" name="review_date" value="{{ old('review_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('review_date') border-red-500 @enderror">
                        @error('review_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Expiry Date
                        </label>
                        <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('expiry_date') border-red-500 @enderror">
                        @error('expiry_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mandatory Policy -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" id="is_mandatory" name="is_mandatory" value="1" {{ old('is_mandatory') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_mandatory" class="ml-2 block text-sm text-gray-900">
                                Mandatory Policy
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Check if this policy is mandatory for all employees</p>
                    </div>

                    <!-- Requires Acknowledgment -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" id="requires_acknowledgment" name="requires_acknowledgment" value="1" {{ old('requires_acknowledgment') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="requires_acknowledgment" class="ml-2 block text-sm text-gray-900">
                                Requires Employee Acknowledgment
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Check if employees must acknowledge this policy</p>
                    </div>

                    <!-- Acknowledgment Instructions -->
                    <div>
                        <label for="acknowledgment_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            Acknowledgment Instructions
                        </label>
                        <textarea id="acknowledgment_instructions" name="acknowledgment_instructions" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('acknowledgment_instructions') border-red-500 @enderror"
                                  placeholder="Instructions for employee acknowledgment">{{ old('acknowledgment_instructions') }}</textarea>
                        @error('acknowledgment_instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Policy Content -->
            <div class="mt-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Policy Content <span class="text-red-500">*</span>
                </label>
                <textarea id="content" name="content" rows="15" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                          placeholder="Enter the detailed policy content here...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('company-policies.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg transition duration-300">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-300">
                    Create Policy
                </button>
            </div>
        </form>
    </div>
</div>
@endsection





