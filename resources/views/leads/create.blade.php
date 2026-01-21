@extends('layouts.app')
@section('title', 'Add New Lead')
@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Lead</h1>
            <p class="text-gray-600">Create a new lead record</p>
        </div>
        <a href="{{ route('leads.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Leads
        </a>
    </div>

    <form method="POST" action="{{ route('leads.store') }}" class="space-y-6" enctype="multipart/form-data">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                    <input type="text" name="company" value="{{ old('company') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('company')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="interested" {{ old('status') == 'interested' ? 'selected' : '' }}>Interested</option>
                        <option value="partially_interested" {{ old('status') == 'partially_interested' ? 'selected' : '' }}>Partially Interested</option>
                        <option value="not_interested" {{ old('status') == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                        <option value="not_reachable" {{ old('status') == 'not_reachable' ? 'selected' : '' }}>Not Reachable</option>
                        <option value="not_answered" {{ old('status') == 'not_answered' ? 'selected' : '' }}>Not Answered</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source *</label>
                    <select name="source" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Select Source</option>
                        <option value="website" {{ old('source') == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="referral" {{ old('source') == 'referral' ? 'selected' : '' }}>Referral</option>
                        <option value="social_media" {{ old('source') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                        <option value="cold_call" {{ old('source') == 'cold_call' ? 'selected' : '' }}>Cold Call</option>
                        <option value="email" {{ old('source') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="trade_show" {{ old('source') == 'trade_show' ? 'selected' : '' }}>Trade Show</option>
                        <option value="advertisement" {{ old('source') == 'advertisement' ? 'selected' : '' }}>Advertisement</option>
                    </select>
                    @error('source')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lead Stage *</label>
                    <select name="lead_stage" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="new" {{ old('lead_stage', 'new') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="quotation_sent" {{ old('lead_stage') == 'quotation_sent' ? 'selected' : '' }}>Quotation Sent</option>
                        <option value="site_survey_done" {{ old('lead_stage') == 'site_survey_done' ? 'selected' : '' }}>Site Survey Done</option>
                        <option value="solar_documents_collected" {{ old('lead_stage') == 'solar_documents_collected' ? 'selected' : '' }}>Solar Documents Collected</option>
                        <option value="loan_documents_collected" {{ old('lead_stage') == 'loan_documents_collected' ? 'selected' : '' }}>Loan Documents Collected</option>
                    </select>
                    @error('lead_stage')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Follow-up Fields (always visible) -->
            <div id="followUpFields" class="mt-4">
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <h4 class="text-sm font-medium text-yellow-800 mb-3">Follow-up Information</h4>
                    <div class="mb-2">
                        <p class="text-xs text-yellow-700">
                            <strong>Note:</strong> For INTERESTED and PARTIALLY INTERESTED leads, follow-up date will be automatically set to 10 days from today. You can modify it if needed.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="follow_up_date" class="block text-sm font-medium text-gray-700 mb-1">Follow-up Date <span class="text-red-600">*</span></label>
                            <input type="date" id="follow_up_date" name="follow_up_date" value="{{ old('follow_up_date') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label for="follow_up_notes" class="block text-sm font-medium text-gray-700 mb-1">Follow-up Notes <span class="text-red-600">*</span></label>
                            <textarea id="follow_up_notes" name="follow_up_notes" rows="2" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Add notes for follow-up call or message...">{{ old('follow_up_notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                    <input type="text" name="state" id="state" value="{{ old('state') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('state')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pincode <span class="text-red-500">*</span></label>
                    <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}" required maxlength="6" pattern="[0-9]{6}" title="Please enter exactly 6 digits" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" oninput="this.value = this.value.replace(/[^0-9]/g, ''); fetchLocationFromPincode(this.value)" placeholder="123456">
                    <div id="pincode-loading" class="hidden text-xs text-blue-600 mt-1">
                        <i class="fas fa-spinner fa-spin mr-1"></i>Fetching location...
                    </div>
                    @error('pincode')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Business Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                    <select name="industry" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Select Industry</option>
                        <option value="residential" {{ old('industry') == 'residential' ? 'selected' : '' }}>Residential</option>
                        <option value="commercial" {{ old('industry') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="industrial" {{ old('industry') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                        <option value="agricultural" {{ old('industry') == 'agricultural' ? 'selected' : '' }}>Agricultural</option>
                        <option value="government" {{ old('industry') == 'government' ? 'selected' : '' }}>Government</option>
                        <option value="education" {{ old('industry') == 'education' ? 'selected' : '' }}>Education</option>
                    </select>
                    @error('industry')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Value (₹)</label>
                    <input type="number" name="estimated_value" value="{{ old('estimated_value') }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('estimated_value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected Close Date</label>
                    <input type="date" name="expected_close_date" value="{{ old('expected_close_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('expected_close_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @if(auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('PROJECT MANAGER'))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To <span class="text-gray-500 text-xs">(Optional)</span></label>
                    <select name="assigned_user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_user_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>
        </div>

        <!-- Additional Information (container only, no main heading) -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="space-y-6">
                <!-- Documentation (including Electricity Bill & Cancelled Cheque) -->
                <div class="pt-4">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Documentation</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Electricity Bill (Attachment) <span class="text-red-500">*</span></label>
                            <input type="file" name="electricity_bill" accept=".jpg,.jpeg,.png,.pdf" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('electricity_bill')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cancelled Cheque (Attachment) <span class="text-red-500">*</span></label>
                            <input type="file" name="cancelled_cheque" accept=".jpg,.jpeg,.png,.pdf" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('cancelled_cheque')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Aadhar</label>
                            <input type="file" name="aadhar_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('aadhar_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PAN</label>
                            <input type="file" name="pan_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('pan_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Other Document Name</label>
                                <input type="text" name="other_document_name" value="{{ old('other_document_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="e.g. Property Tax Receipt">
                                @error('other_document_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Other Document</label>
                                <input type="file" name="other_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                                @error('other_document')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Passport Photo</label>
                            <input type="file" name="passport_photo" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('passport_photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Site Photos -->
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Site Photos</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pre Installation Site Photo</label>
                            <input type="file" name="site_photo_pre_installation" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('site_photo_pre_installation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Post Installation Site Photo</label>
                            <input type="file" name="site_photo_post_installation" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @error('site_photo_post_installation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information (Notes & Requirements) moved to last -->
                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Additional Notes & Requirements</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Add any additional notes about this lead...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
                            <textarea name="requirements" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Describe the lead's requirements...">{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('leads.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">Cancel</a>
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">Create Lead</button>
        </div>
    </form>
</div>

<script>
function fetchLocationFromPincode(pincode) {
    // Only fetch if pincode is exactly 6 digits
    if (pincode.length !== 6) {
        document.getElementById('pincode-loading').classList.add('hidden');
        return;
    }
    
    // Show loading indicator
    document.getElementById('pincode-loading').classList.remove('hidden');
    
    // Use a free pincode API (you can replace with any other API)
    fetch(`https://api.postalpincode.in/pincode/${pincode}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('pincode-loading').classList.add('hidden');
            
            if (data && data[0] && data[0].Status === 'Success' && data[0].PostOffice && data[0].PostOffice.length > 0) {
                const postOffice = data[0].PostOffice[0];
                
                // Auto-fill city and state
                document.getElementById('city').value = postOffice.District || '';
                document.getElementById('state').value = postOffice.State || '';
                
                // Show success message
                showPincodeMessage('Location found and filled automatically!', 'success');
            } else {
                showPincodeMessage('Pincode not found. Please enter manually.', 'error');
            }
        })
        .catch(error => {
            document.getElementById('pincode-loading').classList.add('hidden');
            showPincodeMessage('Unable to fetch location. Please enter manually.', 'error');
            console.error('Pincode API error:', error);
        });
}

function showPincodeMessage(message, type) {
    // Remove existing message
    const existingMessage = document.getElementById('pincode-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.id = 'pincode-message';
    messageDiv.className = `text-xs mt-1 ${type === 'success' ? 'text-green-600' : 'text-red-600'}`;
    messageDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-1"></i>${message}`;
    
    // Insert after pincode input
    const pincodeInput = document.getElementById('pincode');
    pincodeInput.parentNode.insertBefore(messageDiv, pincodeInput.nextSibling);
    
    // Auto-remove message after 3 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 3000);
}
</script>
@endsection


