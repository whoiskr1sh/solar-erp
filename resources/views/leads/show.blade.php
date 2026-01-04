@extends('layouts.app')

@section('title', 'Lead Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Lead Details</h1>
            <p class="mt-2 text-gray-600">View and manage lead information</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Lead
            </a>
            <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Leads
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <p class="text-sm text-gray-900">{{ $lead->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                        <p class="text-sm text-gray-900">{{ $lead->company ?? 'Not specified' }}</p>
                    </div>

                    @if(auth()->user()->hasRole('SUPER ADMIN'))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <p class="text-sm text-gray-900">{{ $lead->phone }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <p class="text-sm text-gray-900">{{ $lead->email ?? 'Not provided' }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst(str_replace('_', ' ', $lead->source)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="flex flex-col">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->status_badge }}">
                                {{ $lead->status_label }}
                            </span>
                            @if($lead->needsFollowUp())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-2">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    Follow-up Required
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lead Stage</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->lead_stage_badge }}">
                            {{ $lead->lead_stage_label }}
                        </span>
                    </div>

                    @if($lead->follow_up_date || $lead->follow_up_notes)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Follow-up Information</label>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            @if($lead->follow_up_date)
                                <p class="text-sm text-gray-900 mb-1">
                                    <strong>Follow-up Date:</strong> {{ $lead->follow_up_date->format('M d, Y') }}
                                    @if($lead->follow_up_date->isPast())
                                        <span class="text-red-600 font-semibold"> (Overdue)</span>
                                    @endif
                                </p>
                            @endif
                            @if($lead->follow_up_notes)
                                <p class="text-sm text-gray-900">
                                    <strong>Notes:</strong> {{ $lead->follow_up_notes }}
                                </p>
                            @endif
                            @if($lead->last_follow_up_at)
                                <p class="text-xs text-gray-500 mt-2">
                                    Last follow-up: {{ $lead->last_follow_up_at->format('M d, Y g:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lead->priority_badge }}">
                            {{ ucfirst($lead->priority) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Value</label>
                        <p class="text-sm text-gray-900">{{ $lead->estimated_value ? '₹' . number_format($lead->estimated_value) : 'Not specified' }}</p>
                    </div>

                    @if($lead->expected_close_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expected Close Date</label>
                        <p class="text-sm text-gray-900">{{ $lead->expected_close_date->format('M d, Y') }}</p>
                    </div>
                    @endif

                    @if($lead->address)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <p class="text-sm text-gray-900">{{ $lead->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($lead->notes)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $lead->notes }}</p>
            </div>
            @endif

            <!-- Quotations -->
            @if($lead->quotations->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quotations</h3>
                <div class="space-y-2">
                    @foreach($lead->quotations as $quotation)
                    <a href="{{ route('quotations.show', $quotation) }}" class="block p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $quotation->quotation_number }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $quotation->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="text-sm text-gray-900">₹{{ number_format($quotation->total_amount) }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Projects -->
            @if($lead->projects->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Projects</h3>
                <div class="space-y-2">
                    @foreach($lead->projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="block p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $project->name }}</p>
                                <p class="text-xs text-gray-500">{{ $project->status }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Invoices -->
            @if($lead->invoices->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Invoices</h3>
                <div class="space-y-2">
                    @foreach($lead->invoices as $invoice)
                    <a href="{{ route('invoices.show', $invoice) }}" class="block p-3 border border-gray-200 rounded-md hover:bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500">{{ $invoice->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="text-sm text-gray-900">₹{{ number_format($invoice->total_amount) }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Assignment -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                        <p class="text-sm text-gray-900">{{ $lead->assignedUser->name ?? 'Unassigned' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                        <p class="text-sm text-gray-900">{{ $lead->creator->name ?? 'Unknown' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
                        <p class="text-sm text-gray-900">{{ $lead->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                        <p class="text-sm text-gray-900">{{ $lead->updated_at->format('M d, Y \a\t g:i A') }}</p>
                        @if($lead->lastUpdater)
                            <p class="text-xs text-gray-500 mt-1">Follow-up updated by {{ $lead->lastUpdater->name }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Documentation -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Documentation</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="font-medium text-gray-700 mb-1">Electricity Bill</p>
                        @if($lead->electricity_bill_path)
                            <a href="{{ Storage::url($lead->electricity_bill_path) }}" target="_blank" class="text-teal-600 underline">View</a>
                        @else
                            <p class="text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-gray-700 mb-1">Cancelled Cheque</p>
                        @if($lead->cancelled_cheque_path)
                            <a href="{{ Storage::url($lead->cancelled_cheque_path) }}" target="_blank" class="text-teal-600 underline">View</a>
                        @else
                            <p class="text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-gray-700 mb-1">Aadhar</p>
                        @if($lead->aadhar_path)
                            <a href="{{ Storage::url($lead->aadhar_path) }}" target="_blank" class="text-teal-600 underline">View</a>
                        @else
                            <p class="text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-gray-700 mb-1">PAN</p>
                        @if($lead->pan_path)
                            <a href="{{ Storage::url($lead->pan_path) }}" target="_blank" class="text-teal-600 underline">View</a>
                        @else
                            <p class="text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-gray-700 mb-1">Other Document</p>
                        @if($lead->other_document_path)
                            <p class="text-gray-700 text-xs mb-1">{{ $lead->other_document_name ?? 'Other' }}</p>
                            <a href="{{ Storage::url($lead->other_document_path) }}" target="_blank" class="text-teal-600 underline">View</a>
                        @else
                            <p class="text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-gray-700 mb-1">Passport Photo</p>
                        @if($lead->passport_photo_path)
                            <a href="{{ Storage::url($lead->passport_photo_path) }}" target="_blank" class="text-teal-600 underline">View</a>
                        @else
                            <p class="text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Site Photos -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Site Photos</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="font-medium text-gray-700 mb-1">Pre Installation</p>
                        @if($lead->site_photo_pre_installation_path)
                            <a href="{{ Storage::url($lead->site_photo_pre_installation_path) }}" target="_blank" class="text-teal-600 underline">View</a>
                        @else
                            <p class="text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-gray-700 mb-1">Post Installation</p>
                        @if($lead->site_photo_post_installation_path)
                            <a href="{{ Storage::url($lead->site_photo_post_installation_path) }}" target="_blank" class="text-teal-600 underline">View</a>
                        @else
                            <p class="text-gray-500">Not uploaded</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                
                <div class="space-y-3">
                    <button onclick="updateStatus('interested')" class="w-full px-4 py-2 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 bg-white hover:bg-green-50">
                        Mark as Interested
                    </button>
                    
                    <button onclick="updateStatus('partially_interested')" class="w-full px-4 py-2 border border-yellow-300 rounded-md shadow-sm text-sm font-medium text-yellow-700 bg-white hover:bg-yellow-50">
                        Mark as Partially Interested
                    </button>
                    
                    <button onclick="updateStatus('not_reachable')" class="w-full px-4 py-2 border border-orange-300 rounded-md shadow-sm text-sm font-medium text-orange-700 bg-white hover:bg-orange-50">
                        Mark as Not Reachable
                    </button>
                    
                    <button onclick="updateStatus('not_answered')" class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Mark as Not Answered
                    </button>
                    
                    <button onclick="updateStatus('not_interested')" class="w-full px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                        Mark as Not Interested
                    </button>
                    
                    @if($lead->status === 'not_reachable')
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <button onclick="openDeleteModal({{ $lead->id }}, '{{ $lead->name }}', {{ (in_array($lead->priority, ['high', 'urgent']) || ($lead->estimated_value && $lead->estimated_value >= 100000)) ? 'true' : 'false' }})" class="w-full px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                Delete Lead
                            </button>
                            <p class="text-xs text-gray-500 mt-2">Only "Not Reachable" leads can be deleted. This will require approval.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Lead</h3>
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete lead: <strong id="deleteLeadName"></strong>?</p>
            <p class="text-xs text-red-600 mb-2 font-semibold">Note: Only leads with status "Not Reachable" can be deleted.</p>
            @if(auth()->user()->hasRole('SUPER ADMIN'))
            <p class="text-xs text-yellow-600 mb-4">The lead will be backed up for 40 days and can be restored from Lead Backups.</p>
            @else
            <p class="text-xs text-yellow-600 mb-4" id="approvalMessage">This will require approval. The lead will be backed up for 40 days after approval.</p>
            @endif
            
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label for="delete_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Deletion @if(!auth()->user()->hasRole('SUPER ADMIN'))<span class="text-red-500">*</span>@endif</label>
                    <textarea id="delete_reason" name="reason" rows="3" @if(!auth()->user()->hasRole('SUPER ADMIN'))required minlength="10"@endif
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                              placeholder="Enter reason for deletion{{ !auth()->user()->hasRole('SUPER ADMIN') ? ' (minimum 10 characters)' : ' (optional)' }}..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        @if(auth()->user()->hasRole('SUPER ADMIN'))
                            Reason is optional for Super Admin.
                        @else
                            Please provide a detailed reason for deletion (minimum 10 characters).
                        @endif
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        @if(auth()->user()->hasRole('SUPER ADMIN'))
                            Delete Lead
                        @else
                            Request Deletion
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    const statusLabels = {
        'interested': 'Interested',
        'not_interested': 'Not Interested',
        'partially_interested': 'Partially Interested',
        'not_reachable': 'Not Reachable',
        'not_answered': 'Not Answered'
    };
    const statusLabel = statusLabels[status] || status;
    if (confirm(`Are you sure you want to mark this lead as ${statusLabel}?`)) {
        fetch('{{ route("leads.update-status", $lead) }}', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status');
        });
    }
}

function openDeleteModal(leadId, leadName, isHigherLead) {
    document.getElementById('deleteLeadName').textContent = leadName;
    const form = document.getElementById('deleteForm');
    form.action = '{{ url("/leads") }}/' + leadId;
    document.getElementById('delete_reason').value = '';
    
    const approvalMessage = document.getElementById('approvalMessage');
    if (approvalMessage) {
        if (isHigherLead) {
            approvalMessage.textContent = 'This is a high-priority/high-value lead. This will require approval from Admin. The lead will be backed up for 40 days after approval.';
        } else {
            approvalMessage.textContent = 'This will require approval from Sales Manager. The lead will be backed up for 40 days after approval.';
        }
    }
    
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteForm').reset();
}

// Close delete modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
