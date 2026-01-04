@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $escalation->title }}</h1>
            <p class="text-gray-600">Escalation #{{ $escalation->escalation_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('escalations.edit', $escalation) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                Edit Escalation
            </a>
            <a href="{{ route('escalations.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Escalations
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $escalation->type_badge }}">
                            {{ ucfirst($escalation->type) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $escalation->priority_badge }}">
                            {{ ucfirst($escalation->priority) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $escalation->status_badge }}">
                            {{ ucfirst($escalation->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $escalation->category_badge }}">
                            {{ ucfirst($escalation->category) }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $escalation->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                        <p class="text-gray-900">{{ $escalation->customer_name ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-gray-900">{{ $escalation->customer_email ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <p class="text-gray-900">{{ $escalation->customer_phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Related Entities -->
            @if($escalation->relatedLead || $escalation->relatedProject || $escalation->relatedInvoice || $escalation->relatedQuotation || $escalation->relatedCommission)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Related Entities</h3>
                <div class="space-y-3">
                    @if($escalation->relatedLead)
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div>
                            <p class="font-medium text-blue-900">Related Lead</p>
                            <p class="text-sm text-blue-700">{{ $escalation->relatedLead->company }}</p>
                        </div>
                        <a href="{{ route('leads.show', $escalation->relatedLead) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Lead</a>
                    </div>
                    @endif
                    
                    @if($escalation->relatedProject)
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div>
                            <p class="font-medium text-green-900">Related Project</p>
                            <p class="text-sm text-green-700">{{ $escalation->relatedProject->name }}</p>
                        </div>
                        <a href="{{ route('projects.show', $escalation->relatedProject) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">View Project</a>
                    </div>
                    @endif
                    
                    @if($escalation->relatedInvoice)
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div>
                            <p class="font-medium text-purple-900">Related Invoice</p>
                            <p class="text-sm text-purple-700">{{ $escalation->relatedInvoice->invoice_number }}</p>
                        </div>
                        <a href="{{ route('invoices.show', $escalation->relatedInvoice) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">View Invoice</a>
                    </div>
                    @endif
                    
                    @if($escalation->relatedQuotation)
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <div>
                            <p class="font-medium text-yellow-900">Related Quotation</p>
                            <p class="text-sm text-yellow-700">{{ $escalation->relatedQuotation->quotation_number }}</p>
                        </div>
                        <a href="{{ route('quotations.show', $escalation->relatedQuotation) }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">View Quotation</a>
                    </div>
                    @endif
                    
                    @if($escalation->relatedCommission)
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <div>
                            <p class="font-medium text-orange-900">Related Commission</p>
                            <p class="text-sm text-orange-700">{{ $escalation->relatedCommission->commission_number }}</p>
                        </div>
                        <a href="{{ route('commissions.show', $escalation->relatedCommission) }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium">View Commission</a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Internal Notes -->
            @if($escalation->internal_notes)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Internal Notes</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $escalation->internal_notes }}</p>
                </div>
            </div>
            @endif

            <!-- Resolution Notes -->
            @if($escalation->resolution_notes)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Resolution Notes</h3>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $escalation->resolution_notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($escalation->status === 'open')
                    <form method="POST" action="{{ route('escalations.mark-in-progress', $escalation) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm">
                            Mark In Progress
                        </button>
                    </form>
                    @endif
                    
                    @if($escalation->status === 'in_progress')
                    <button onclick="showResolveModal()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                        Mark Resolved
                    </button>
                    @endif
                    
                    @if($escalation->status === 'resolved')
                    <form method="POST" action="{{ route('escalations.mark-closed', $escalation) }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                            Mark Closed
                        </button>
                    </form>
                    @endif
                    
                    <button onclick="showEscalateModal()" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm">
                        Escalate
                    </button>
                    
                    <button onclick="showAssignModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        Assign
                    </button>
                </div>
            </div>

            <!-- Assignment Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                        <p class="text-gray-900">{{ $escalation->assignedTo->name ?? 'Unassigned' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Escalated To</label>
                        <p class="text-gray-900">{{ $escalation->escalatedTo->name ?? 'Not escalated' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created By</label>
                        <p class="text-gray-900">{{ $escalation->creator->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Escalation Level</label>
                        <p class="text-gray-900">Level {{ $escalation->escalation_level }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                        <p class="text-gray-900">{{ $escalation->created_at->format('M d, Y H:i') }}</p>
                        <p class="text-sm text-gray-500">{{ $escalation->days_open }} days ago</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <p class="text-gray-900">{{ $escalation->formatted_due_date }}</p>
                        @if($escalation->is_overdue)
                        <p class="text-sm text-red-600">Overdue</p>
                        @elseif($escalation->is_due_soon)
                        <p class="text-sm text-yellow-600">Due Soon</p>
                        @endif
                    </div>
                    @if($escalation->resolved_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Resolved</label>
                        <p class="text-gray-900">{{ $escalation->formatted_resolved_at }}</p>
                    </div>
                    @endif
                    @if($escalation->closed_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Closed</label>
                        <p class="text-gray-900">{{ $escalation->formatted_closed_at }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Info</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Urgent</span>
                        @if($escalation->is_urgent)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Yes</span>
                        @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Requires Response</span>
                        @if($escalation->requires_response)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                        @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                        @endif
                    </div>
                    @if($escalation->tags)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                        <div class="flex flex-wrap gap-1">
                            @foreach($escalation->tags as $tag)
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resolve Modal -->
<div id="resolveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mark as Resolved</h3>
                <form method="POST" action="{{ route('escalations.mark-resolved', $escalation) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="resolution_notes" class="block text-sm font-medium text-gray-700 mb-2">Resolution Notes *</label>
                        <textarea id="resolution_notes" name="resolution_notes" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="Describe how the escalation was resolved..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideResolveModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Mark Resolved
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Escalate Modal -->
<div id="escalateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Escalate To</h3>
                <form method="POST" action="{{ route('escalations.escalate', $escalation) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="escalated_to" class="block text-sm font-medium text-gray-700 mb-2">Select User *</label>
                        <select id="escalated_to" name="escalated_to" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select User</option>
                            @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideEscalateModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg">
                            Escalate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Assign Modal -->
<div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Assign To</h3>
                <form method="POST" action="{{ route('escalations.assign', $escalation) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Select User *</label>
                        <select id="assigned_to" name="assigned_to" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Select User</option>
                            @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideAssignModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showResolveModal() {
    document.getElementById('resolveModal').classList.remove('hidden');
}

function hideResolveModal() {
    document.getElementById('resolveModal').classList.add('hidden');
    document.getElementById('resolution_notes').value = '';
}

function showEscalateModal() {
    document.getElementById('escalateModal').classList.remove('hidden');
}

function hideEscalateModal() {
    document.getElementById('escalateModal').classList.add('hidden');
    document.getElementById('escalated_to').value = '';
}

function showAssignModal() {
    document.getElementById('assignModal').classList.remove('hidden');
}

function hideAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
    document.getElementById('assigned_to').value = '';
}
</script>
@endsection
