@extends('layouts.app')

@section('title', 'Edit Lead')

@section('content')
<div class="w-full space-y-6">
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
            <h1 class="text-3xl font-bold text-gray-900">Edit Lead</h1>
            <p class="mt-2 text-gray-600">Update lead information for {{ $lead->name }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('leads.show', $lead) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Lead
            </a>
            <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Leads
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('leads.update', $lead) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $lead->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Company -->
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                    <input type="text" id="company" name="company" value="{{ old('company', $lead->company) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $lead->phone) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Consumer No -->
                <div>
                    <label for="consumer_number" class="block text-sm font-medium text-gray-700 mb-2">Consumer No <span class="text-red-500">*</span></label>
                    <input type="text" id="consumer_number" name="consumer_number" value="{{ old('consumer_number', $lead->consumer_number) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $lead->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Source -->
                <div>
                    <label for="source" class="block text-sm font-medium text-gray-700 mb-2">Source *</label>
                    <select id="source" name="source" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Source</option>
                        <option value="website" {{ old('source', $lead->source) == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="indiamart" {{ old('source', $lead->source) == 'indiamart' ? 'selected' : '' }}>IndiaMart</option>
                        <option value="justdial" {{ old('source', $lead->source) == 'justdial' ? 'selected' : '' }}>JustDial</option>
                        <option value="meta_ads" {{ old('source', $lead->source) == 'meta_ads' ? 'selected' : '' }}>Meta Ads</option>
                        <option value="referral" {{ old('source', $lead->source) == 'referral' ? 'selected' : '' }}>Referral</option>
                        <option value="cold_call" {{ old('source', $lead->source) == 'cold_call' ? 'selected' : '' }}>Cold Call</option>
                        <option value="other" {{ old('source', $lead->source) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500" onchange="toggleFollowUpFields()">
                        <option value="">Select Status</option>
                        <option value="interested" {{ old('status', $lead->status) == 'interested' ? 'selected' : '' }}>Interested</option>
                        <option value="not_interested" {{ old('status', $lead->status) == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                        <option value="partially_interested" {{ old('status', $lead->status) == 'partially_interested' ? 'selected' : '' }}>Partially Interested</option>
                        <option value="not_reachable" {{ old('status', $lead->status) == 'not_reachable' ? 'selected' : '' }}>Not Reachable</option>
                        <option value="not_answered" {{ old('status', $lead->status) == 'not_answered' ? 'selected' : '' }}>Not Answered</option>
                    </select>
                </div>

                <!-- Lead Stage -->
                <div>
                    <label for="lead_stage" class="block text-sm font-medium text-gray-700 mb-2">Lead Stage</label>
                    <select id="lead_stage" name="lead_stage" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Lead Stage</option>
                        <option value="quotation_sent" {{ old('lead_stage', $lead->lead_stage) == 'quotation_sent' ? 'selected' : '' }}>Quotation Sent</option>
                        <option value="site_survey_done" {{ old('lead_stage', $lead->lead_stage) == 'site_survey_done' ? 'selected' : '' }}>Site Survey Done</option>
                        <option value="solar_documents_collected" {{ old('lead_stage', $lead->lead_stage) == 'solar_documents_collected' ? 'selected' : '' }}>Solar Documents Collected</option>
                        <option value="loan_documents_collected" {{ old('lead_stage', $lead->lead_stage) == 'loan_documents_collected' ? 'selected' : '' }}>Loan Documents Collected</option>
                    </select>
                    @error('lead_stage')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Follow-up Fields (shown for interested, partially_interested, not_reachable, not_answered) -->
                <div id="followUpFields" style="display: none;" class="md:col-span-2">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <h4 class="text-sm font-medium text-yellow-800 mb-3">Follow-up Information</h4>
                        <div class="mb-2">
                            <p class="text-xs text-yellow-700">
                                @if(in_array($lead->status, ['interested', 'partially_interested']))
                                    <strong>Note:</strong> For INTERESTED and PARTIALLY INTERESTED leads, follow-up date will be automatically set to 10 days from today. You can modify it if needed.
                                @else
                                    Follow-up is required for this status.
                                @endif
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date</label>
                                <input type="date" id="follow_up_date" name="follow_up_date" value="{{ old('follow_up_date', $lead->follow_up_date ? $lead->follow_up_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            </div>
                            <div>
                                <label for="follow_up_notes" class="block text-sm font-medium text-gray-700 mb-2">Follow-up Notes</label>
                                <textarea id="follow_up_notes" name="follow_up_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500" placeholder="Add notes for follow-up call or message...">{{ old('follow_up_notes', $lead->follow_up_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select id="priority" name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Priority</option>
                        <option value="low" {{ old('priority', $lead->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $lead->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $lead->priority) == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $lead->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="assigned_user_id" class="block text-sm font-medium text-gray-700 mb-2">Assigned To <span class="text-gray-500 text-xs">(Optional)</span></label>
                    <select id="assigned_user_id" name="assigned_user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_user_id', $lead->assigned_user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Estimated Value -->
                <div>
                    <label for="estimated_value" class="block text-sm font-medium text-gray-700 mb-2">Estimated Value (₹)</label>
                    <input type="number" id="estimated_value" name="estimated_value" value="{{ old('estimated_value', $lead->estimated_value) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Expected Close Date -->
                <div>
                    <label for="expected_close_date" class="block text-sm font-medium text-gray-700 mb-2">Expected Close Date</label>
                    <input type="date" id="expected_close_date" name="expected_close_date" value="{{ old('expected_close_date', $lead->expected_close_date ? $lead->expected_close_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea id="address" name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500" placeholder="Enter address...">{{ old('address', $lead->address) }}</textarea>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500" placeholder="Enter notes...">{{ old('notes', $lead->notes) }}</textarea>
                </div>

                <!-- Documentation -->
                <div class="md:col-span-2 border-t border-gray-200 pt-4 mt-2">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Documentation</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Electricity Bill (Attachment)</label>
                            @if($lead->electricity_bill_path)
                                <p class="text-xs text-gray-600 mb-1">Current: <a href="{{ Storage::url($lead->electricity_bill_path) }}" target="_blank" class="text-teal-600 underline">View</a></p>
                            @endif
                            <input type="file" name="electricity_bill" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            @error('electricity_bill')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cancelled Cheque (Attachment)</label>
                            @if($lead->cancelled_cheque_path)
                                <p class="text-xs text-gray-600 mb-1">Current: <a href="{{ Storage::url($lead->cancelled_cheque_path) }}" target="_blank" class="text-teal-600 underline">View</a></p>
                            @endif
                            <input type="file" name="cancelled_cheque" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            @error('cancelled_cheque')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Aadhar</label>
                            @if($lead->aadhar_path)
                                <p class="text-xs text-gray-600 mb-1">Current: <a href="{{ Storage::url($lead->aadhar_path) }}" target="_blank" class="text-teal-600 underline">View</a></p>
                            @endif
                            <input type="file" name="aadhar_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            @error('aadhar_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PAN</label>
                            @if($lead->pan_path)
                                <p class="text-xs text-gray-600 mb-1">Current: <a href="{{ Storage::url($lead->pan_path) }}" target="_blank" class="text-teal-600 underline">View</a></p>
                            @endif
                            <input type="file" name="pan_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            @error('pan_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Other Document Name</label>
                            <input type="text" name="other_document_name" value="{{ old('other_document_name', $lead->other_document_name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500" placeholder="e.g. Property Tax Receipt">
                            @error('other_document_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Other Document</label>
                            @if($lead->other_document_path)
                                <p class="text-xs text-gray-600 mb-1">Current: <a href="{{ Storage::url($lead->other_document_path) }}" target="_blank" class="text-teal-600 underline">View</a></p>
                            @endif
                            <input type="file" name="other_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            @error('other_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Passport Photo</label>
                            @if($lead->passport_photo_path)
                                <p class="text-xs text-gray-600 mb-1">Current: <a href="{{ Storage::url($lead->passport_photo_path) }}" target="_blank" class="text-teal-600 underline">View</a></p>
                            @endif
                            <input type="file" name="passport_photo" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            @error('passport_photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Site Photos -->
                <div class="md:col-span-2 border-t border-gray-200 pt-4 mt-2">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Site Photos</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pre Installation Site Photo</label>
                            @if($lead->site_photo_pre_installation_path)
                                <p class="text-xs text-gray-600 mb-1">Current: <a href="{{ Storage::url($lead->site_photo_pre_installation_path) }}" target="_blank" class="text-teal-600 underline">View</a></p>
                            @endif
                            <input type="file" name="site_photo_pre_installation" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            @error('site_photo_pre_installation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Post Installation Site Photo</label>
                            @if($lead->site_photo_post_installation_path)
                                <p class="text-xs text-gray-600 mb-1">Current: <a href="{{ Storage::url($lead->site_photo_post_installation_path) }}" target="_blank" class="text-teal-600 underline">View</a></p>
                            @endif
                            <input type="file" name="site_photo_post_installation" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                            @error('site_photo_post_installation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('leads.show', $lead) }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700">
                    Update Lead
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFollowUpFields() {
    const statusSelect = document.getElementById('status');
    const followUpFields = document.getElementById('followUpFields');
    const followUpDateInput = document.getElementById('follow_up_date');
    const needsFollowUp = ['interested', 'partially_interested', 'not_reachable', 'not_answered'];
    
    if (needsFollowUp.includes(statusSelect.value)) {
        followUpFields.style.display = 'block';
        
        // Auto-set follow-up date to 10 days from now for INTERESTED and PARTIALLY INTERESTED
        if (['interested', 'partially_interested'].includes(statusSelect.value)) {
            const today = new Date();
            today.setDate(today.getDate() + 10);
            const dateString = today.toISOString().split('T')[0];
            if (!followUpDateInput.value) {
                followUpDateInput.value = dateString;
            }
        }
    } else {
        followUpFields.style.display = 'none';
    }
}

// Check on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFollowUpFields();
});
</script>
@endsection
