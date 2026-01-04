@extends('layouts.app')

@section('title', 'Edit Site Expense')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Site Expense</h1>
            <p class="text-gray-600 dark:text-gray-400">Update site expense information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('site-expenses.show', $siteExpense) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                View Expense
            </a>
            <a href="{{ route('site-expenses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Site Expenses
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('site-expenses.update', $siteExpense) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b pb-2">Basic Information</h3>
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $siteExpense->title) }}" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description', $siteExpense->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project</label>
                        <select id="project_id" name="project_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('project_id') border-red-500 @enderror">
                            <option value="">No Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $siteExpense->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="site_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Site Location</label>
                        <input type="text" id="site_location" name="site_location" value="{{ old('site_location', $siteExpense->site_location) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('site_location') border-red-500 @enderror">
                        @error('site_location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expense_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                        <select id="expense_category" name="expense_category" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('expense_category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            <option value="material" {{ old('expense_category', $siteExpense->expense_category) == 'material' ? 'selected' : '' }}>Material</option>
                            <option value="labor" {{ old('expense_category', $siteExpense->expense_category) == 'labor' ? 'selected' : '' }}>Labor</option>
                            <option value="transport" {{ old('expense_category', $siteExpense->expense_category) == 'transport' ? 'selected' : '' }}>Transport</option>
                            <option value="equipment" {{ old('expense_category', $siteExpense->expense_category) == 'equipment' ? 'selected' : '' }}>Equipment</option>
                            <option value="misc" {{ old('expense_category', $siteExpense->expense_category) == 'misc' ? 'selected' : '' }}>Miscellaneous</option>
                            <option value="other" {{ old('expense_category', $siteExpense->expense_category) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('expense_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b pb-2">Financial Details</h3>
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount *</label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount', $siteExpense->amount) }}" step="0.01" min="0.01" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('amount') border-red-500 @enderror">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expense_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expense Date *</label>
                        <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date', $siteExpense->expense_date->format('Y-m-d')) }}" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('expense_date') border-red-500 @enderror">
                        @error('expense_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method *</label>
                        <select id="payment_method" name="payment_method" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('payment_method') border-red-500 @enderror">
                            <option value="cash" {{ old('payment_method', $siteExpense->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card" {{ old('payment_method', $siteExpense->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="transfer" {{ old('payment_method', $siteExpense->payment_method) == 'transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cheque" {{ old('payment_method', $siteExpense->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vendor_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vendor Name</label>
                        <input type="text" id="vendor_name" name="vendor_name" value="{{ old('vendor_name', $siteExpense->vendor_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('vendor_name') border-red-500 @enderror">
                        @error('vendor_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="receipt_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Receipt Number</label>
                        <input type="text" id="receipt_number" name="receipt_number" value="{{ old('receipt_number', $siteExpense->receipt_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('receipt_number') border-red-500 @enderror">
                        @error('receipt_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="receipt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Receipt</label>
                        @if($siteExpense->receipt_path)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current: <a href="{{ Storage::url($siteExpense->receipt_path) }}" target="_blank" class="text-blue-600 hover:underline">View Receipt</a></p>
                        @endif
                        <input type="file" id="receipt" name="receipt" accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('receipt') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Supported format: JPG, PNG, PDF (Max 2MB)</p>
                        @error('receipt')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Notes</label>
                <textarea id="notes" name="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white @error('notes') border-red-500 @enderror">{{ old('notes', $siteExpense->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('site-expenses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-300">
                    Cancel
                </a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition duration-300">
                    Update Site Expense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection






