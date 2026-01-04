@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Escalations</h1>
            <p class="text-gray-600">Manage customer escalations and support requests</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportEscalations()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Export CSV
            </button>
            <a href="{{ route('escalations.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                Add Escalation
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-4">
        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-blue-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Total</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-red-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Open</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['open'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-yellow-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">In Progress</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['in_progress'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-green-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Resolved</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['resolved'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-purple-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Overdue</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['overdue'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-orange-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Urgent</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['urgent'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-3 mb-4">
        <form method="GET" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search escalations..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Status</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Types</option>
                    <option value="complaint" {{ request('type') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                    <option value="issue" {{ request('type') == 'issue' ? 'selected' : '' }}>Issue</option>
                    <option value="request" {{ request('type') == 'request' ? 'selected' : '' }}>Request</option>
                    <option value="incident" {{ request('type') == 'incident' ? 'selected' : '' }}>Incident</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Categories</option>
                    <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                    <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                    <option value="service" {{ request('category') == 'service' ? 'selected' : '' }}>Service</option>
                    <option value="support" {{ request('category') == 'support' ? 'selected' : '' }}>Support</option>
                    <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <label class="flex items-center">
                    <input type="checkbox" name="overdue" value="true" {{ request('overdue') == 'true' ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <span class="ml-2 text-sm text-gray-700">Overdue Only</span>
                </label>
            </div>
            
            <div class="flex items-end">
                <label class="flex items-center">
                    <input type="checkbox" name="urgent" value="true" {{ request('urgent') == 'true' ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <span class="ml-2 text-sm text-gray-700">Urgent Only</span>
                </label>
            </div>
            
            <div class="lg:col-span-6 flex justify-end space-x-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                    Apply Filters
                </button>
                <a href="{{ route('escalations.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Escalations Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-xs table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Escalation #</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Title</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Type</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Priority</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Status</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Customer</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Assigned To</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Due Date</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Days Open</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($escalations as $escalation)
                    <tr class="hover:bg-gray-50 {{ $escalation->is_overdue ? 'bg-red-50' : '' }}">
                        <td class="px-2 py-2">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $escalation->escalation_number }}">{{ $escalation->escalation_number }}</div>
                            @if($escalation->is_urgent)
                            <div class="text-xs text-orange-600 truncate">🚨 Urgent</div>
                            @endif
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $escalation->title }}">{{ $escalation->title }}</div>
                            <div class="text-xs text-gray-500 truncate" title="{{ $escalation->category }}">{{ ucfirst($escalation->category) }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $escalation->type_badge }}" title="{{ ucfirst($escalation->type) }}">
                                {{ ucfirst($escalation->type) }}
                            </span>
                        </td>
                        <td class="px-2 py-2">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $escalation->priority_badge }}" title="{{ ucfirst($escalation->priority) }}">
                                {{ ucfirst($escalation->priority) }}
                            </span>
                        </td>
                        <td class="px-2 py-2">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $escalation->status_badge }}" title="{{ ucfirst($escalation->status) }}">
                                {{ ucfirst($escalation->status) }}
                            </span>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $escalation->customer_name ?? 'N/A' }}">{{ $escalation->customer_name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 truncate" title="{{ $escalation->customer_email ?? 'N/A' }}">{{ $escalation->customer_email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $escalation->assignedTo->name ?? 'Unassigned' }}">{{ $escalation->assignedTo->name ?? 'Unassigned' }}</div>
                        </td>
                        <td class="px-2 py-2">
                            @if($escalation->due_date)
                            <div class="text-xs text-gray-900 truncate" title="{{ $escalation->formatted_due_date }}">{{ $escalation->due_date->format('M d, Y') }}</div>
                            @if($escalation->is_overdue)
                            <div class="text-xs text-red-600 truncate">Overdue</div>
                            @elseif($escalation->is_due_soon)
                            <div class="text-xs text-yellow-600 truncate">Due Soon</div>
                            @endif
                            @else
                            <div class="text-xs text-gray-500 truncate">Not set</div>
                            @endif
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs font-medium text-gray-900 truncate">{{ $escalation->days_open }} days</div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="flex flex-wrap gap-0.5">
                                <a href="{{ route('escalations.show', $escalation) }}" class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors duration-200" title="View">
                                    V
                                </a>
                                <a href="{{ route('escalations.edit', $escalation) }}" class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition-colors duration-200" title="Edit">
                                    E
                                </a>
                                @if($escalation->status === 'open')
                                <form method="POST" action="{{ route('escalations.mark-in-progress', $escalation) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded transition-colors duration-200" title="Mark In Progress">
                                        P
                                    </button>
                                </form>
                                @endif
                                @if($escalation->status === 'in_progress')
                                <button onclick="showResolveModal({{ $escalation->id }})" class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-600 hover:text-green-800 hover:bg-green-50 rounded transition-colors duration-200" title="Mark Resolved">
                                    R
                                </button>
                                @endif
                                <form method="POST" action="{{ route('escalations.destroy', $escalation) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this escalation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors duration-200" title="Delete">
                                        D
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-900 mb-2">No escalations found</p>
                                <p class="text-gray-500 mb-4">Get started by creating your first escalation.</p>
                                <a href="{{ route('escalations.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm">
                                    Add Escalation
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($escalations->hasPages())
        <div class="px-2 py-2 border-t border-gray-200">
            {{ $escalations->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Resolve Modal -->
<div id="resolveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mark as Resolved</h3>
                <form id="resolveForm" method="POST">
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

<script>
function exportEscalations() {
    console.log('Export function called');
    
    // Get current filter values with null checks
    const searchElement = document.querySelector('input[name="search"]');
    const statusElement = document.querySelector('select[name="status"]');
    const priorityElement = document.querySelector('select[name="priority"]');
    const typeElement = document.querySelector('select[name="type"]');
    const categoryElement = document.querySelector('select[name="category"]');
    const assignedToElement = document.querySelector('select[name="assigned_to"]');
    const overdueElement = document.querySelector('input[name="overdue"]');
    const urgentElement = document.querySelector('input[name="urgent"]');
    
    const search = searchElement ? searchElement.value : '';
    const status = statusElement ? statusElement.value : 'all';
    const priority = priorityElement ? priorityElement.value : 'all';
    const type = typeElement ? typeElement.value : 'all';
    const category = categoryElement ? categoryElement.value : 'all';
    const assignedTo = assignedToElement ? assignedToElement.value : 'all';
    const overdue = overdueElement ? overdueElement.checked : false;
    const urgent = urgentElement ? urgentElement.checked : false;
    
    console.log('Filter values:', { search, status, priority, type, category, assignedTo, overdue, urgent });
    
    // Build query string
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (status && status !== 'all') params.append('status', status);
    if (priority && priority !== 'all') params.append('priority', priority);
    if (type && type !== 'all') params.append('type', type);
    if (category && category !== 'all') params.append('category', category);
    if (assignedTo && assignedTo !== 'all') params.append('assigned_to', assignedTo);
    if (overdue) params.append('overdue', 'true');
    if (urgent) params.append('urgent', 'true');
    
    // Create export URL
    const exportUrl = '{{ route("escalations.export") }}' + (params.toString() ? '?' + params.toString() : '');
    console.log('Export URL:', exportUrl);
    
    // Create hidden link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'escalations_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    console.log('Download triggered');
    
    // Show success message
    setTimeout(() => {
        console.log('Export completed successfully!');
    }, 1000);
}

function showResolveModal(escalationId) {
    const modal = document.getElementById('resolveModal');
    const form = document.getElementById('resolveForm');
    form.action = '{{ route("escalations.mark-resolved", ":id") }}'.replace(':id', escalationId);
    modal.classList.remove('hidden');
}

function hideResolveModal() {
    const modal = document.getElementById('resolveModal');
    modal.classList.add('hidden');
    document.getElementById('resolution_notes').value = '';
}
</script>
@endsection


