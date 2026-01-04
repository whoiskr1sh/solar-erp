@extends('layouts.app')

@section('title', 'Edit Payment Milestone')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Payment Milestone</h1>
            <p class="text-gray-600">Update {{ $paymentMilestone->title }}</p>
        </div>
        <a href="{{ route('payment-milestones.show', $paymentMilestone) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
            Back to Milestone
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('payment-milestones.update', $paymentMilestone) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $paymentMilestone->title) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('title') border-red-500 @enderror" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $paymentMilestone->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Milestone Number:</label>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <h2 class="text-xl font-bold text-teal-600">{{ $paymentMilestone->milestone_number }}</h2>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                        <select name="project_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $paymentMilestone->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->project_name }} - {{ $project->client_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quotation</label>
                        <select name="quotation_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <option value="">Select Quotation</option>
                            @foreach($quotations as $quotation)
                                <option value="{{ $quotation->id }}" {{ old('quotation_id', $paymentMilestone->quotation_id) == $quotation->id ? 'selected' : '' }}>
                                    {{ $quotation->quotation_number }} - {{ $quotation->client_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Financial Information</h3>
                    
                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Milestone Amount :</label>
                            <input type="number" name="milestone_amount" step="0.01" value="{{ old('milestone_amount', $paymentMilestone->milestone_amount) }}" 
                                   class="w-full text-center px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                            @error('milestone_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Paid Amount :</label>
                            <input type="number" name="paid_amount" step="0.01" value="{{ old('paid_amount', $paymentMilestone->paid_amount) }}" 
                                   class="w-full text-center px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Currency :</label>
                            <select name="currency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                                <option value="INR" {{ old('currency', $paymentMilestone->currency) == 'INR' ? 'selected' : '' }}>INR</option>
                                <option value="USD" {{ old('currency', $paymentMilestone->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('currency', $paymentMilestone->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ old('currency', $paymentMilestone->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Milestone Type :</label>
                            <select name="milestone_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                                <option value="advance" {{ old('milestone_type', $paymentMilestone->milestone_type) == 'advance' ? 'selected' : '' }}>Advance</option>
                                <option value="progress" {{ old('milestone_type', $paymentMilestone->milestone_type) == 'progress' ? 'selected' : '' }}>Progress</option>
                                <option value="completion" {{ old('milestone_type', $paymentMilestone->milestone_type) == 'completion' ? 'selected' : '' }}>Completion</option>
                                <option value="retention" {{ old('milestone_type', $paymentMilestone->milestone_type) == 'retention' ? 'selected' : '' }}>Retention</option>
                                <option value="final" {{ old('milestone_type', $paymentMilestone->milestone_type) == 'final' ? 'selected' : '' }}>Final</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Milestone Percentage :</label>
                            <input type="range" name="milestone_percentage" min="0" max="100" value="{{ old('milestone_percentage', $paymentMilestone->milestone_percentage) }}" class="w-full" oninput="this.nextElementSibling.textContent = this.value + '%'">
                            <div class="text-sm text-gray-600">{{ old('milestone_percentage', $paymentMilestone->milestone_percentage) }}%</div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Schedule Information</h3>
                    
                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Planned Date :</label>
                            <input type="date" name="planned_date" value="{{ old('planned_date', $paymentMilestone->planned_date->format('Y-m-d')) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Due Date :</label>
                            <input type="date" name="due_date" value="{{ old('due_date', $paymentMilestone->due_date->format('Y-m-d')) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Payment Date :</label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', $paymentMilestone->payment_date ? $paymentMilestone->payment_date->format('Y-m-d') : '') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Assigned To :</label>
                            <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $paymentMilestone->assigned_to) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->designation }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Status & Payment Information -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Status & Payment</h3>
                    
                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status :</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                                <option value="pending" {{ old('status', $paymentMilestone->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $paymentMilestone->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $paymentMilestone->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="paid" {{ old('status', $paymentMilestone->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ old('status', $paymentMilestone->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="cancelled" {{ old('status', $paymentMilestone->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Payment Status :</label>
                            <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                                <option value="pending" {{ old('payment_status', $paymentMilestone->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ old('payment_status', $paymentMilestone->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partial" {{ old('payment_status', $paymentMilestone->payment_status) == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="overdue" {{ old('payment_status', $paymentMilestone->payment_status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="cancelled" {{ old('payment_status', $paymentMilestone->payment_status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Payment Method :</label>
                            <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Select Payment Method</option>
                                <option value="cash" {{ old('payment_method', $paymentMilestone->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="cheque" {{ old('payment_method', $paymentMilestone->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="bank_transfer" {{ old('payment_method', $paymentMilestone->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="online" {{ old('payment_method', $paymentMilestone->payment_method) == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="upi" {{ old('payment_method', $paymentMilestone->payment_method) == 'upi' ? 'selected' : '' }}>UPI</option>
                                <option value="card" {{ old('payment_method', $paymentMilestone->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Payment Reference :</label>
                            <input type="text" name="payment_reference" value="{{ old('payment_reference', $paymentMilestone->payment_reference) }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2">Notes & Additional Information</h3>
                    
                    <div class="bg-white rounded-lg border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-2 p-6 pb-2">Payment Notes :</label>
                        <textarea name="payment_notes" rows="3" class="w-full px-6 pb-6 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('payment_notes', $paymentMilestone->payment_notes) }}</textarea>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-2 p-6 pb-2">Milestone Notes :</label>
                        <textarea name="milestone_notes" rows="3" class="w-full px-6 pb-6 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('milestone_notes', $paymentMilestone->milestone_notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('payment-milestones.show', $paymentMilestone) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-lg transition duration-300">
                    Cancel
                    </a>
                    <button type="submit" 
                            class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg transition duration-300">
                    Update Milestone
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
