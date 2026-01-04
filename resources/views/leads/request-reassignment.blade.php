@extends('layouts.app')

@section('title', 'Request Lead Reassignment')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Request Lead Reassignment</h1>
            <p class="mt-2 text-gray-600">Request approval to reassign your leads to another team member</p>
        </div>
        <a href="{{ route('leads.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Leads
        </a>
    </div>

    @if(isset($hasPendingRequest) && $hasPendingRequest)
        <!-- Pending Request Alert -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        You already have a pending reassignment request.
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Please wait for approval or rejection before creating a new request.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(!isset($hasPendingRequest) || !$hasPendingRequest)
        @if(isset($assignedLeadsCount) && $assignedLeadsCount === 0)
            <!-- No Leads Viewed Alert -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            You have not viewed any lead contact numbers yet.
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>To request reassignment, you need to view at least one lead's contact number first. Go to the leads list and click on a lead's contact number to view it.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Request Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="POST" action="{{ route('leads.reassignment-requests.store') }}" id="reassignmentForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Assign Leads To -->
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">
                        Assign Leads To <span class="text-red-500">*</span>
                    </label>
                    <select name="assigned_to" id="assigned_to" required @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) disabled @endif class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('assigned_to') border-red-500 @enderror @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) bg-gray-100 cursor-not-allowed @endif">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                                @php
                                    $userRoles = $user->roles->pluck('name')->toArray();
                                    if (!empty($userRoles)) {
                                        echo ' (' . implode(', ', $userRoles) . ')';
                                    }
                                @endphp
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">This request will be reviewed by @if(isset($isSalesManager) && $isSalesManager) Admin @else Sales Manager @endif.</p>
                </div>

                <!-- Lead Selection Type -->
                @if($assignedLeadsCount > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Select Leads to Reassign <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <!-- Select by Count -->
                        <div class="flex items-center">
                            <input type="radio" name="selection_type" id="selection_type_count" value="count" {{ old('selection_type', 'count') == 'count' ? 'checked' : '' }} @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) disabled @endif class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                            <label for="selection_type_count" class="ml-2 block text-sm text-gray-700">
                                Select by Count
                            </label>
                        </div>
                        <div id="count_selection" class="ml-6 {{ old('selection_type', 'count') == 'count' ? '' : 'hidden' }}">
                            <label for="leads_count" class="block text-sm text-gray-600 mb-1">Number of Leads</label>
                            <input type="number" name="leads_count" id="leads_count" min="1" max="{{ $assignedLeadsCount }}" value="{{ old('leads_count', $assignedLeadsCount) }}" @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) disabled @endif class="w-32 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('leads_count') border-red-500 @enderror @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) bg-gray-100 cursor-not-allowed @endif">
                            <p class="text-xs text-gray-500 mt-1">Maximum: {{ $assignedLeadsCount }} lead(s)</p>
                            @error('leads_count')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Select Specific Leads -->
                        <div class="flex items-center">
                            <input type="radio" name="selection_type" id="selection_type_specific" value="specific" {{ old('selection_type') == 'specific' ? 'checked' : '' }} @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) disabled @endif class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                            <label for="selection_type_specific" class="ml-2 block text-sm text-gray-700">
                                Select Specific Leads
                            </label>
                        </div>
                        <div id="specific_selection" class="ml-6 {{ old('selection_type') == 'specific' ? '' : 'hidden' }}">
                            <div class="mb-2">
                                <input type="text" id="lead_search" placeholder="Search leads by name, phone, or company..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) disabled @endif>
                            </div>
                            <div id="lead_list" class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-3 @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) bg-gray-100 @endif">
                                @if(isset($assignedLeads) && $assignedLeads->count() > 0)
                                    @php
                                        $oldSelected = old('selected_lead_ids', []);
                                        if (!is_array($oldSelected)) {
                                            $oldSelected = [];
                                        }
                                    @endphp
                                    @foreach($assignedLeads as $lead)
                                        <div class="flex items-center py-2 border-b border-gray-200 last:border-b-0 hover:bg-gray-50 lead-row">
                                            <input type="checkbox" name="selected_lead_ids[]" id="lead_{{ $lead->id }}" value="{{ $lead->id }}" {{ in_array($lead->id, $oldSelected) ? 'checked' : '' }} @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) disabled @endif class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded cursor-pointer">
                                            <label for="lead_{{ $lead->id }}" class="ml-2 block text-sm text-gray-700 cursor-pointer flex-1">
                                                <span class="font-medium">{{ $lead->name }}</span>
                                                <span class="text-gray-500"> - {{ $lead->phone ?? 'No phone' }}</span>
                                                @if($lead->company)
                                                    <span class="text-gray-500"> ({{ $lead->company }})</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500 text-center py-4">No leads available</p>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="selected_count">0</span> lead(s) selected
                            </p>
                            @error('selected_lead_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Reassignment <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="5" required @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) disabled @endif placeholder="Please provide a reason for requesting reassignment (e.g., on leave, unavailable, etc.)" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('reason') border-red-500 @enderror @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) bg-gray-100 cursor-not-allowed @endif">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">This request will be reviewed by @if(isset($isSalesManager) && $isSalesManager) Admin @else Sales Manager @endif.</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('leads.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) disabled @endif class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white @if((isset($hasPendingRequest) && $hasPendingRequest) || $assignedLeadsCount === 0) bg-gray-400 cursor-not-allowed @else bg-teal-600 hover:bg-teal-700 @endif">
                    @if(isset($hasPendingRequest) && $hasPendingRequest)
                        Request Pending
                    @elseif($assignedLeadsCount === 0)
                        No Leads to Reassign
                    @else
                        Submit Request
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countRadio = document.getElementById('selection_type_count');
    const specificRadio = document.getElementById('selection_type_specific');
    const countSelection = document.getElementById('count_selection');
    const specificSelection = document.getElementById('specific_selection');
    const form = document.getElementById('reassignmentForm');
    
    if (countRadio && specificRadio && countSelection && specificSelection) {
        countRadio.addEventListener('change', function() {
            if (this.checked) {
                countSelection.classList.remove('hidden');
                specificSelection.classList.add('hidden');
                // Clear specific selection
                const checkboxes = specificSelection.querySelectorAll('input[name="selected_lead_ids[]"]');
                checkboxes.forEach(function(cb) {
                    cb.checked = false;
                });
                // Make leads_count required
                const leadsCountInput = document.getElementById('leads_count');
                if (leadsCountInput) {
                    leadsCountInput.setAttribute('required', 'required');
                }
                updateSelectedCount();
            }
        });
        
        specificRadio.addEventListener('change', function() {
            if (this.checked) {
                specificSelection.classList.remove('hidden');
                countSelection.classList.add('hidden');
                // Clear count
                const leadsCountInput = document.getElementById('leads_count');
                if (leadsCountInput) {
                    leadsCountInput.value = '';
                }
                // Ensure all checkboxes in specific selection are enabled
                const checkboxes = specificSelection.querySelectorAll('input[name="selected_lead_ids[]"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = false;
                });
                updateSelectedCount();
            }
        });
        
        // Update selected count when checkboxes change
        function updateSelectedCount() {
            const checkboxes = specificSelection.querySelectorAll('input[name="selected_lead_ids[]"]');
            let count = 0;
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked && !checkbox.disabled) {
                    count++;
                }
            });
            const countElement = document.getElementById('selected_count');
            if (countElement) {
                countElement.textContent = count;
            }
        }
        
        // Add event listeners to checkboxes
        if (specificSelection) {
            specificSelection.addEventListener('change', function(e) {
                if (e.target.type === 'checkbox' && e.target.name === 'selected_lead_ids[]') {
                    updateSelectedCount();
                }
            });
        }

        // Lead search filtering
        const leadSearchInput = document.getElementById('lead_search');
        const leadList = document.getElementById('lead_list');
        if (leadSearchInput && leadList) {
            leadSearchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                const rows = leadList.querySelectorAll('.lead-row');
                rows.forEach(function (row) {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }
        
        // Initial count update
        updateSelectedCount();
    }
    
    // Form submission validation
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            const hasPendingRequest = @json(isset($hasPendingRequest) && $hasPendingRequest);
            const assignedLeadsCount = @json($assignedLeadsCount ?? 0);
            
            console.log('hasPendingRequest:', hasPendingRequest, 'assignedLeadsCount:', assignedLeadsCount);
            
            if (hasPendingRequest || assignedLeadsCount === 0) {
                e.preventDefault();
                alert('You cannot submit this request. ' + (hasPendingRequest ? 'You already have a pending request.' : 'You have no leads to reassign.'));
                return false;
            }
            
            // Validate selection type
            const selectionType = document.querySelector('input[name="selection_type"]:checked');
            console.log('Selection type:', selectionType ? selectionType.value : 'none');
            if (!selectionType) {
                e.preventDefault();
                alert('Please select a lead selection method.');
                return false;
            }
            
            if (selectionType.value === 'specific') {
                // Ensure specific selection is visible and checkboxes are enabled
                if (specificSelection) {
                    specificSelection.classList.remove('hidden');
                    const allCheckboxes = specificSelection.querySelectorAll('input[name="selected_lead_ids[]"]');
                    allCheckboxes.forEach(function(checkbox) {
                        checkbox.disabled = false;
                    });
                }
                
                const selectedLeads = document.querySelectorAll('input[name="selected_lead_ids[]"]:checked');
                console.log('Selected leads count:', selectedLeads.length);
                console.log('Selected lead IDs:', Array.from(selectedLeads).map(cb => cb.value));
                
                if (selectedLeads.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one lead to reassign.');
                    return false;
                }
            } else if (selectionType.value === 'count') {
                const leadsCount = document.getElementById('leads_count');
                if (!leadsCount || !leadsCount.value || parseInt(leadsCount.value) < 1) {
                    e.preventDefault();
                    alert('Please enter a valid number of leads to reassign.');
                    return false;
                }
                if (parseInt(leadsCount.value) > assignedLeadsCount) {
                    e.preventDefault();
                    alert('You can only reassign up to ' + assignedLeadsCount + ' lead(s).');
                    return false;
                }
            }
            
            // Validate assigned_to
            const assignedTo = document.getElementById('assigned_to');
            if (!assignedTo || !assignedTo.value) {
                e.preventDefault();
                alert('Please select a team member to assign leads to.');
                return false;
            }
            
            // Validate reason
            const reason = document.getElementById('reason');
            if (!reason || !reason.value.trim()) {
                e.preventDefault();
                alert('Please provide a reason for reassignment.');
                return false;
            }
            
            // If we reach here, all validations passed - allow form submission
            console.log('All validations passed, allowing form submission');
            // Don't prevent default - let the form submit naturally
        });
    }
});
</script>
@endsection
