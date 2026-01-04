@extends('layouts.app')

@section('title', 'Payment Milestones')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Milestones</h1>
            <p class="text-gray-600">Track payment schedules and project milestones</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('payment-milestones.dashboard') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Dashboard
            </a>
            <a href="{{ route('payment-milestones.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Create Milestone
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-green-600 uppercase mb-1">Total Milestones</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_milestones']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['pending_milestones']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-1">In Progress</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['in_progress_milestones']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-purple-600 uppercase mb-1">Paid</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['paid_milestones']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-red-600 uppercase mb-1">Overdue</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['overdue_milestones']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-green-600 uppercase mb-1">Total Amount</p>
                    <p class="text-xl font-bold text-gray-800">${{ number_format($stats['total_milestone_amount'], 0) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-1">Paid Amount</p>
                    <p class="text-xl font-bold text-gray-800">${{ number_format($stats['total_paid_amount'], 0) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-orange-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-orange-600 uppercase mb-1">Pending</p>
                    <p class="text-xl font-bold text-gray-800">${{ number_format($stats['pending_amount'], 0) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-purple-600 uppercase mb-1">This Month Due</p>
                    <p class="text-xl font-bold text-gray-800">${{ number_format($stats['this_month_due'], 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="search" value="{{ request('search') }}" placeholder="Search milestones...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Payment Status</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="overdue" {{ request('payment_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="milestone_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="advance" {{ request('milestone_type') == 'advance' ? 'selected' : '' }}>Advance</option>
                    <option value="progress" {{ request('milestone_type') == 'progress' ? 'selected' : '' }}>Progress</option>
                    <option value="completion" {{ request('milestone_type') == 'completion' ? 'selected' : '' }}>Completion</option>
                    <option value="retention" {{ request('milestone_type') == 'retention' ? 'selected' : '' }}>Retention</option>
                    <option value="final" {{ request('milestone_type') == 'final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                <select name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                            {{ $project->project_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                        Filter
                    </button>
                    <a href="{{ route('payment-milestones.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Milestone #</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Title</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Project</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Type</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Amount</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Paid</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Due Date</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Payment</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($milestones as $milestone)
                    <tr class="hover:bg-gray-50 {{ $milestone->is_overdue ? 'bg-red-50' : '' }}">
                        <td class="px-3 py-4 text-sm text-gray-900">{{ $milestone->milestone_number }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            <div class="max-w-xs">
                                <div class="font-medium">{{ Str::limit($milestone->title, 25) }}</div>
                                @if($milestone->description)
                                    <div class="text-gray-500 text-xs">{{ Str::limit($milestone->description, 30) }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            {{ $milestone->project ? Str::limit($milestone->project->project_name, 15) : 'N/A' }}
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $milestone->type_badge }}">
                                {{ ucfirst($milestone->milestone_type) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">{{ $milestone->formatted_milestone_amount }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900">{{ $milestone->formatted_paid_amount }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            <div class="{{ $milestone->is_overdue ? 'text-red-600 font-medium' : '' }}">
                                {{ $milestone->due_date->format('M d, Y') }}
                                @if($milestone->is_overdue)
                                    <div class="text-xs text-red-500">({{ $milestone->delay_days }} days overdue)</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $milestone->status_badge }}">
                                {{ ucfirst($milestone->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $milestone->payment_status_badge }}">
                                {{ ucfirst($milestone->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('payment-milestones.show', $milestone) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="View">V</a>
                                <a href="{{ route('payment-milestones.edit', $milestone) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs" title="Edit">E</a>
                                @if($milestone->status === 'pending')
                                <a href="{{ route('payment-milestones.mark-in-progress', $milestone) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="Start Progress" onclick="return confirm('Mark as in progress?')">▶</a>
                                @endif
                                @if($milestone->status === 'in_progress')
                                <a href="{{ route('payment-milestones.mark-completed', $milestone) }}" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" title="Mark Completed" onclick="return confirm('Mark as completed?')">✓</a>
                                @endif
                                @if($milestone->payment_status !== 'paid')
                                <button onclick="markPaid({{ $milestone->id }})" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" title="Mark Paid">$</button>
                                @endif
                                <form method="POST" action="{{ route('payment-milestones.destroy', $milestone) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this milestone?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Delete">D</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                            No payment milestones found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            {{ $milestones->links() }}
        </div>
    </div>
</div>

<!-- Mark Paid Modal -->
<div id="markPaidModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <form id="markPaidForm" method="POST">
                @csrf
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Record Payment</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount</label>
                            <input type="number" name="paid_amount" step="0.010000000000000000" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <select name="payment_method" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="online">Online</option>
                                <option value="upi">UPI</option>
                                <option value="card">Card</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Reference</label>
                            <input type="text" name="payment_reference" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="payment_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeMarkPaidModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                        Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function markPaid(milestoneId) {
    document.getElementById('markPaidForm').action = `/payment-milestones/${milestoneId}/mark-paid`;
    document.getElementById('markPaidModal').classList.remove('hidden');
}

function closeMarkPaidModal() {
    document.getElementById('markPaidModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('markPaidModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMarkPaidModal();
    }
});
</script>
@endsection
