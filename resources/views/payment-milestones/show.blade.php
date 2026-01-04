@extends('layouts.app')

@section('title', 'View Payment Milestone')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Milestone Details</h1>
            <p class="text-gray-600">{{ $paymentMilestone->title }} - {{ $paymentMilestone->milestone_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('payment-milestones.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Milestones
            </a>
            <a href="{{ route('payment-milestones.edit', $paymentMilestone) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Edit Milestone
            </a>
        </div>
    </div>

    <!-- Milestone Overview -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $paymentMilestone->status_badge }}">
                    {{ ucfirst($paymentMilestone->status) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $paymentMilestone->type_badge }}">
                    {{ ucfirst($paymentMilestone->milestone_type) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $paymentMilestone->payment_status_badge }}">
                    {{ ucfirst($paymentMilestone->payment_status) }}
                </span>
            </div>
            <div class="text-sm text-gray-500">
                Created {{ $paymentMilestone->created_at->diffForHumans() }}
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <h3 class="text-sm font-medium text-blue-600 uppercase mb-2">Milestone Amount</h3>
                <p class="text-2xl font-bold text-blue-800">{{ $paymentMilestone->formatted_milestone_amount }}</p>
            </div>
            
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <h3 class="text-sm font-medium text-green-600 uppercase mb-2">Paid Amount</h3>
                <p class="text-2xl font-bold text-green-800">{{ $paymentMilestone->formatted_paid_amount }}</p>
            </div>
            
            <div class="text-center p-4 {{ $paymentMilestone->remaining_amount > 0 ? 'bg-red-50' : 'bg-green-50' }} rounded-lg">
                <h3 class="text-sm font-medium {{ $paymentMilestone->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }} uppercase mb-2">Remaining</h3>
                <p class="text-2xl font-bold {{ $paymentMilestone->remaining_amount > 0 ? 'text-red-800' : 'text-green-800' }}">
                    {{ $paymentMilestone->formatted_remaining_amount }}
                </p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-6">
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>Payment Progress</span>
                <span>{{ $paymentMilestone->payment_percentage }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $paymentMilestone->payment_percentage }}%"></div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Basic Information</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-700">Milestone Number:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->milestone_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Currency:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->currency }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Planned Date:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->planned_date->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Due Date:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->due_date->format('M d, Y') }}</span>
                    @if($paymentMilestone->is_overdue)
                        <span class="text-red-600 text-sm">{{ $paymentMilestone->delay_days }} days overdue</span>
                    @endif
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Completion %:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->milestone_percentage }}%</span>
                </div>
            </div>
        </div>

        <!-- Project & Assignment -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Project & Assignment</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project:</label>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->project->project_name ?? 'No project assigned' }}</span>
                    @if($paymentMilestone->project)
                        <p class="text-sm text-gray-500">{{ $paymentMilestone->project->client_name }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quotation:</label>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->quotation->quotation_number ?? 'No quotation linked' }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Created By:</label>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->creator->name }}</span>
                </div>
                @if($paymentMilestone->assignee)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To:</label>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->assignee->name }}</span>
                    <p class="text-sm text-gray-500">{{ $paymentMilestone->assignee->designation }}</p>
                </div>
                @endif
                @if($paymentMilestone->payer)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paid By:</label>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->payer->name }}</span>
                    <p class="text-sm text-gray-500">{{ $paymentMilestone->paid_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Payment Details</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-700">Payment Method:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->payment_method ? ucfirst(str_replace('_', ' ', $paymentMilestone->payment_method)) : 'Not specified' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Payment Reference:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->payment_reference ?: 'Not provided' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Payment Date:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->payment_date ? $paymentMilestone->payment_date->format('M d, Y') : 'Not paid yet' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Days Until Due:</span>
                    <span class="font-medium text-gray-900 {{ $paymentMilestone->is_overdue ? 'text-red-600' : '' }}">
                        {{ $paymentMilestone->days_until_due }} days
                    </span>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Status Information</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-700">Status:</span>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $paymentMilestone->status_badge }}">
                        {{ ucfirst($paymentMilestone->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Payment Status:</span>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $paymentMilestone->payment_status_badge }}">
                        {{ ucfirst($paymentMilestone->payment_status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Created:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Updated:</span>
                    <span class="font-medium text-gray-900">{{ $paymentMilestone->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($paymentMilestone->description || $paymentMilestone->payment_notes || $paymentMilestone->milestone_notes)
    <!-- Notes Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Notes</h3>
        <div class="space-y-4">
            @if($paymentMilestone->description)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description:</label>
                <p class="text-gray-900">{{ $paymentMilestone->description }}</p>
            </div>
            @endif
            @if($paymentMilestone->payment_notes)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Notes:</label>
                <p class="text-gray-900">{{ $paymentMilestone->payment_notes }}</p>
            </div>
            @endif
            @if($paymentMilestone->milestone_notes)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Milestone Notes:</label>
                <p class="text-gray-900">{{ $paymentMilestone->milestone_notes }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('payment-milestones.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-300">
            Back to Milestones
        </a>
        <a href="{{ route('payment-milestones.edit', $paymentMilestone) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg transition duration-300">
            Edit Milestone
        </a>
        
        @if($paymentMilestone->status === 'pending')
        <form method="POST" action="{{ route('payment-milestones.mark-in-progress', $paymentMilestone) }}" class="inline">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-300" onclick="return confirm('Mark as in progress?')">
                Start Progress
            </button>
        </form>
        @endif

        @if($paymentMilestone->status === 'in_progress')
        <form method="POST" action="{{ route('payment-milestones.mark-completed', $paymentMilestone) }}" class="inline">
            @csrf
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-300" onclick="return confirm('Mark as completed?')">
                Mark Completed
            </button>
        </form>
        @endif

        @if($paymentMilestone->payment_status !== 'paid')
        <button onclick="markPaid({{ $paymentMilestone->id }})" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg transition duration-300">
            Record Payment
        </button>
        @endif
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
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Remaining Amount: <span class="font-bold">{{ $paymentMilestone->formatted_remaining_amount }}</span></p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Amount</label>
                            <input type="number" name="paid_amount" step="0.010000000000000000" max="{{ $paymentMilestone->remaining_amount }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
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
                            <input type="text" name="payment_reference" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Transaction ID, Cheque No., etc.">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="payment_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Payment notes..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button type="button" onclick="closeMarkPaidModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        Cancel
                    </button>
                    <button типа="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
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
