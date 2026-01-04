@extends('layouts.app')

@section('title', 'Edit Budget')

@section('content')
<div class="w-full max-w-7xl mx-auto p-6 campaign-bg">
    <div class="bg-white rounded-lg shadow-md p-8 mb-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Budget</h1>
            <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                <h2 class="text-xl font-bold text-teal-600">{{ $budget->budget_number }}</h2>
                <p class="text-gray-600">{{ $budget->title }}</p>
            </div>
        </div>

        <div class="border-b border-gray-200 mb-6"></div>

        <!-- Edit Form -->
        <form method="POST" action="{{ route('budgets.update', $budget) }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-6 h-6 bg-teal-600 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Basic Information
                </h3>
                
                <div class="space-y-4">
                    <!-- Title & Description -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Budget Title :</label>
                                <input type="text" id="title" name="title" 
                                       value="{{ $budget->title }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                       placeholder="Enter budget title">
                                @error('title')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                                <label for="budget_category_id" class="block text-sm font-bold text-gray-700 mb-2">Budget Category :</label>
                                <select id="budget_category_id" name="budget_category_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    <option value="" disabled>Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $budget->budget_category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('budget_category_id')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                                <label for="project_id" class="block text-sm font-bold text-gray-700 mb-2">Project :</label>
                                <select id="project_id" name="project_id" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    <option value="" disabled>Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ $budget->project_id == $project->id ? 'selected' : '' }}>
                                            {{ $project->project_name }} - {{ $project->client_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                                <label for="currency" class="block text-sm font-bold text-gray-700 mb-2">Currency :</label>
                                <select id="currency" name="currency" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    <option value="INR" {{ $budget->currency == 'INR' ? 'selected' : '' }}>INR</option>
                                    <option value="USD" {{ $budget->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="EUR" {{ $budget->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="GBP" {{ $budget->currency == 'GBP' ? 'selected' : '' }}>GBP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Budget Description :</label>
                        <textarea id="description" name="description" rows="3" 
                                  placeholder="Enter budget description"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ $budget->description }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Budget Period & Duration -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-6 h-6 bg-teal-600 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </span>
                    Budget Period & Duration
                </h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label for="budget_period" class="block text-sm font-bold text-gray-700 mb-2">Budget Period :</label>
                            <select id="budget_period" name="budget_period" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="monthly" {{ $budget->budget_period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ $budget->budget_period == 'quarterly' ? 'selected' : '' }}>Weekly</option>
                                <option value="quarterly" {{ $budget->budget_period == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="yearly" {{ $budget->budget_period == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ $budget->budget_period == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label for="start_date" class="block text-sm font-bold text-gray-700 mb-2">Start Date :</label>
                            <input type="date" id="start_date" name="start_date" 
                                   value="{{ $budget->start_date->format('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label for="end_date" class="block text-sm font-bold text-gray-700 mb-2">End Date :</label>
                            <input type="date" id="end_date" name="end_date" 
                                   value="{{ $budget->end_date->format('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-red-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actual Amount -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-6 h-6 bg-teal-600 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Budget Amount
                </h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <div class="text-center p-6 bg-white rounded-lg border border-gray-200">
                            <label for="budget_amount" class="block text-sm font-bold text-gray-700 mb-2">Budget Amount :</label>
                            <input type="number" id="budget_amount" name="budget_amount" 
                                   value="{{ $budget->budget_amount }}" 
                                   placeholder="0.00"
                                   class="w-full text-center px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            @error('budget_amount')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-center p-6 bg-white rounded-lg border border-gray-200">
                            <label for="actual_amount" class="block text-sm font-bold text-gray-700 mb-2">Actual Amount :</label>
                            <input type="number" id="actual_amount" name="actual_amount" 
                                   value="{{ $budget->actual_amount }}" 
                                   placeholder="0.00"
                                   class="w-full text-center px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status & Approval -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-6 h-6 bg-teal-600 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Status & Approval
                </h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Status :</label>
                            <select id="status" name="status" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="draft" {{ $budget->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ $budget->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $budget->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $budget->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ $budget->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label for="is_approved" class="block text-sm font-bold text-gray-700 mb-2">Approval Status:</label>
                            <div class="flex items-center justify-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_approved" value="1" {{ $budget->is_approved ? 'checked' : ''}}>
                                    <span class="ml-2 text-sm text-gray-700">Approved</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_approved" value="0" {{ !$budget->is_approved ? 'checked' : ''}}>
                                    <span class="ml-2 text-sm text-gray-700">Not Approved</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <span class="w-6 h-6 bg-teal-600 rounded-full flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </span>
                    Additional Notes
                </h3>
                
                <div class="bg-white rounded-lg border border-gray-200">
                    <label for="notes" class="block text-sm font-bold text-gray-700 mb-2 p-6 pb-2">Notes:</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-6 pb-6 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                              placeholder="Enter any additional budget notes">{{ $budget->notes }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                <div class="flex justify-center space-x-4">
                    <button type="submit" 
                            class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg transition duration-300">
                        Update Budget
                    </button>
                    <a href="{{ route('budgets.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition duration-300">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
