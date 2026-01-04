@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Expense Details</h1>
            <p class="text-gray-600">View expense information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('expenses.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Expenses
            </a>
            <a href="{{ route('expenses.edit', $expense) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Edit Expense
            </a>
        </div>
    </div>

    <!-- Expense Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                    Basic Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Expense Number</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $expense->expense_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Title</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $expense->title }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                        <p class="text-gray-900">{{ $expense->description ?? 'No description provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.313 1.017 2.076 1.017 1.024 0 1.888-.414 2.577-1.134.685-.713 1.034-1.666 1.034-2.866V8.067c.375-.092.694-.212.958-.363a1 1 0 00.072-1.685c-.69-.719-1.548-1.106-2.436-1.106-.888 0-1.746.387-2.436 1.106a1 1 0 00.072 1.685c.264.151.583.271.958.363v1.24z" clip-rule="evenodd"/>
                    </svg>
                    Financial Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Amount</label>
                        <p class="text-2xl font-bold text-teal-600">{{ $expense->formatted_amount }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Payment Method</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $expense->payment_method_badge }}">
                            {{ ucfirst($expense->payment_method) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $expense->status_badge }}">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            @if($expense->notes || $expense->receipt_path)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                    Additional Information
                </h3>
                @if($expense->notes)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Notes</label>
                    <p class="text-gray-900 whitespace-pre-line">{{ $expense->notes }}</p>
                </div>
                @endif
                @if($expense->receipt_path)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Receipt</label>
                    <a href="{{ asset('storage/' . $expense->receipt_path) }}" target="_blank" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586v-5.586a1 1 0 112 0v5.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Download Receipt
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Status Actions -->
            @if($expense->status === 'pending')
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('expenses.approve', $expense) }}">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Approve Expense
                        </button>
                    </form>
                    
                    <button onclick="showRejectModal()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300">
                        Reject Expense
                    </button>
                    
                    <!-- Rejection Modal -->
                    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Expense</h3>
                                <form method="POST" action="{{ route('expenses.reject', $expense) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                                        <textarea name="rejection_reason" rows="3" required
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                  placeholder="Enter reason for rejection"></textarea>
                                    </div>
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" onclick="hideRejectModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                                            Cancel
                                        </button>
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                            Reject Expense
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($expense->status === 'approved')
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <form method="POST" action="{{ route('expenses.mark-paid', $expense) }}">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                        Mark as Paid
                    </button>
                </form>
            </div>
            @endif

            <!-- Expense Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense Details</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="text-sm text-gray-900 flex items-center mt-1">
                            <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: {{ $expense->category->color }}"></span>
                            {{ $expense->category->name }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Project</dt>
                        <dd class="text-sm text-gray-900">{{ $expense->project ? $expense->project->name : 'General Expense' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Expense Date</dt>
                        <dd class="text-sm text-gray-900">{{ $expense->expense_date->format('F j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created By</dt>
                        <dd class="text-sm text-gray-900">{{ $expense->creator->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="text-sm text-gray-900">{{ $expense->created_at->format('F j, Y \a\t g:i A') }}</dd>
                    </div>
                    @if($expense->approved_by)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                        <dd class="text-sm text-gray-900">{{ $expense->approver->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Approved At</dt>
                        <dd class="text-sm text-gray-900">{{ $expense->approved_at->format('F j, Y \a\t g:i A') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
