@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Escalation</h1>
            <p class="text-gray-600">Update escalation #{{ $escalation->escalation_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('escalations.show', $escalation) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                View Escalation
            </a>
            <a href="{{ route('escalations.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Escalations
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('escalations.update', $escalation) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $escalation->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Brief description of the escalation">
                        @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea id="description" name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Detailed description of the escalation">{{ old('description', $escalation->description) }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select id="type" name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Type</option>
                            <option value="complaint" {{ old('type', $escalation->type) == 'complaint' ? 'selected' : '' }}>Complaint</option>
                            <option value="issue" {{ old('type', $escalation->type) == 'issue' ? 'selected' : '' }}>Issue</option>
                            <option value="request" {{ old('type', $escalation->type) == 'request' ? 'selected' : '' }}>Request</option>
                            <option value="incident" {{ old('type', $escalation->type) == 'incident' ? 'selected' : '' }}>Incident</option>
                            <option value="other" {{ old('type', $escalation->type) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select id="priority" name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Priority</option>
                            <option value="low" {{ old('priority', $escalation->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $escalation->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $escalation->priority) == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority', $escalation->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select id="category" name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Category</option>
                            <option value="technical" {{ old('category', $escalation->category) == 'technical' ? 'selected' : '' }}>Technical</option>
                            <option value="billing" {{ old('category', $escalation->category) == 'billing' ? 'selected' : '' }}>Billing</option>
                            <option value="service" {{ old('category', $escalation->category) == 'service' ? 'selected' : '' }}>Service</option>
                            <option value="support" {{ old('category', $escalation->category) == 'support' ? 'selected' : '' }}>Support</option>
                            <option value="general" {{ old('category', $escalation->category) == 'general' ? 'selected' : '' }}>General</option>
                        </select>
                        @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="open" {{ old('status', $escalation->status) == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ old('status', $escalation->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ old('status', $escalation->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ old('status', $escalation->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="cancelled" {{ old('status', $escalation->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                        <select id="assigned_to" name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $escalation->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="escalated_to" class="block text-sm font-medium text-gray-700 mb-2">Escalated To</label>
                        <select id="escalated_to" name="escalated_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('escalated_to', $escalation->escalated_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('escalated_to')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $escalation->customer_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Customer name">
                        @error('customer_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Customer Email</label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email', $escalation->customer_email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="customer@example.com">
                        @error('customer_email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Customer Phone</label>
                        <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $escalation->customer_phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="+91 9876543210">
                        @error('customer_phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Related Entities -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Related Entities</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="related_lead_id" class="block text-sm font-medium text-gray-700 mb-2">Related Lead</label>
                        <select id="related_lead_id" name="related_lead_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Lead</option>
                            @foreach($leads as $lead)
                            <option value="{{ $lead->id }}" {{ old('related_lead_id', $escalation->related_lead_id) == $lead->id ? 'selected' : '' }}>{{ $lead->company }}</option>
                            @endforeach
                        </select>
                        @error('related_lead_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="related_project_id" class="block text-sm font-medium text-gray-700 mb-2">Related Project</label>
                        <select id="related_project_id" name="related_project_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Project</option>
                            @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('related_project_id', $escalation->related_project_id) == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('related_project_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="related_invoice_id" class="block text-sm font-medium text-gray-700 mb-2">Related Invoice</label>
                        <select id="related_invoice_id" name="related_invoice_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Invoice</option>
                            @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" {{ old('related_invoice_id', $escalation->related_invoice_id) == $invoice->id ? 'selected' : '' }}>{{ $invoice->invoice_number }}</option>
                            @endforeach
                        </select>
                        @error('related_invoice_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="related_quotation_id" class="block text-sm font-medium text-gray-700 mb-2">Related Quotation</label>
                        <select id="related_quotation_id" name="related_quotation_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Quotation</option>
                            @foreach($quotations as $quotation)
                            <option value="{{ $quotation->id }}" {{ old('related_quotation_id', $escalation->related_quotation_id) == $quotation->id ? 'selected' : '' }}>{{ $quotation->quotation_number }}</option>
                            @endforeach
                        </select>
                        @error('related_quotation_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="related_commission_id" class="block text-sm font-medium text-gray-700 mb-2">Related Commission</label>
                        <select id="related_commission_id" name="related_commission_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select Commission</option>
                            @foreach($commissions as $commission)
                            <option value="{{ $commission->id }}" {{ old('related_commission_id', $escalation->related_commission_id) == $commission->id ? 'selected' : '' }}>{{ $commission->commission_number }}</option>
                            @endforeach
                        </select>
                        @error('related_commission_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                        <input type="datetime-local" id="due_date" name="due_date" value="{{ old('due_date', $escalation->due_date ? $escalation->due_date->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="resolution_notes" class="block text-sm font-medium text-gray-700 mb-2">Resolution Notes</label>
                        <textarea id="resolution_notes" name="resolution_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Resolution notes (if resolved)">{{ old('resolution_notes', $escalation->resolution_notes) }}</textarea>
                        @error('resolution_notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="internal_notes" class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                        <textarea id="internal_notes" name="internal_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Internal notes (not visible to customer)">{{ old('internal_notes', $escalation->internal_notes) }}</textarea>
                        @error('internal_notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <input type="text" id="tags" name="tags" value="{{ old('tags', $escalation->tags ? implode(', ', $escalation->tags) : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="tag1, tag2, tag3 (comma separated)">
                        @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex space-x-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_urgent" value="1" {{ old('is_urgent', $escalation->is_urgent) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ml-2 text-sm text-gray-700">Mark as Urgent</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="requires_response" value="1" {{ old('requires_response', $escalation->requires_response) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            <span class="ml-2 text-sm text-gray-700">Requires Response</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('escalations.show', $escalation) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                    Update Escalation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


