@extends('layouts.app')

@section('title', 'Site Expense Details')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Site Expense Details</h1>
            <p class="text-gray-600 dark:text-gray-400">View site expense information</p>
        </div>
        <div class="flex space-x-3">
            @if($siteExpense->status == 'pending' && auth()->user()->id == $siteExpense->created_by)
                <a href="{{ route('site-expenses.edit', $siteExpense) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    Edit Expense
                </a>
            @endif
            <a href="{{ route('site-expenses.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Site Expenses
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Expense Number</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $siteExpense->expense_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Title</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $siteExpense->title }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Description</label>
                        <p class="text-gray-900 dark:text-white">{{ $siteExpense->description ?? 'No description provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Project</label>
                        <p class="text-gray-900 dark:text-white">{{ $siteExpense->project ? $siteExpense->project->name : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Site Location</label>
                        <p class="text-gray-900 dark:text-white">{{ $siteExpense->site_location ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Category</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ ucfirst($siteExpense->expense_category) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Amount</label>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($siteExpense->amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Expense Date</label>
                        <p class="text-gray-900 dark:text-white">{{ $siteExpense->expense_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Payment Method</label>
                        <p class="text-gray-900 dark:text-white">{{ ucfirst($siteExpense->payment_method) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Vendor Name</label>
                        <p class="text-gray-900 dark:text-white">{{ $siteExpense->vendor_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Receipt Number</label>
                        <p class="text-gray-900 dark:text-white">{{ $siteExpense->receipt_number ?? 'N/A' }}</p>
                    </div>
                    @if($siteExpense->receipt_path)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Receipt</label>
                        <a href="{{ Storage::url($siteExpense->receipt_path) }}" target="_blank" class="text-blue-600 hover:underline dark:text-blue-400">View Receipt</a>
                    </div>
                    @endif
                </div>
            </div>

            @if($siteExpense->notes)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notes</h3>
                <p class="text-gray-900 dark:text-white">{{ $siteExpense->notes }}</p>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h3>
                <div class="mb-4">
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $siteExpense->status_badge }}">
                        {{ ucfirst($siteExpense->status) }}
                    </span>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <p class="mb-2"><strong>Approval Level:</strong> {{ ucfirst($siteExpense->approval_level ?? 'N/A') }}</p>
                    @if($siteExpense->hr_approved_by)
                        <p class="mb-1"><strong>HR Approved:</strong> {{ $siteExpense->hrApprover->name ?? 'N/A' }}</p>
                        <p class="mb-2 text-xs">{{ $siteExpense->hr_approved_at ? $siteExpense->hr_approved_at->format('M d, Y H:i') : '' }}</p>
                    @endif
                    @if($siteExpense->approved_by)
                        <p class="mb-1"><strong>Admin Approved:</strong> {{ $siteExpense->approver->name ?? 'N/A' }}</p>
                        <p class="mb-2 text-xs">{{ $siteExpense->approved_at ? $siteExpense->approved_at->format('M d, Y H:i') : '' }}</p>
                    @endif
                    @if($siteExpense->hr_rejection_reason)
                        <p class="mt-2 text-red-600 dark:text-red-400"><strong>HR Rejection:</strong> {{ $siteExpense->hr_rejection_reason }}</p>
                    @endif
                    @if($siteExpense->admin_rejection_reason)
                        <p class="mt-2 text-red-600 dark:text-red-400"><strong>Admin Rejection:</strong> {{ $siteExpense->admin_rejection_reason }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Created By</h3>
                <p class="text-gray-900 dark:text-white">{{ $siteExpense->creator->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $siteExpense->created_at->format('M d, Y H:i') }}</p>
            </div>

            @if($siteExpense->status == 'pending')
                @if($siteExpense->approval_level == 'hr' && (auth()->user()->hasRole('HR MANAGER') || auth()->user()->hasRole('SUPER ADMIN')))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                    <div class="space-y-2">
                        <form action="{{ route('site-expenses.approve', $siteExpense) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                Approve (HR)
                            </button>
                        </form>
                        <button onclick="showRejectModal()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Reject
                        </button>
                    </div>
                </div>
                @elseif($siteExpense->approval_level == 'admin' && auth()->user()->hasRole('SUPER ADMIN'))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                    <div class="space-y-2">
                        <form action="{{ route('site-expenses.approve', $siteExpense) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                Final Approve (Admin)
                            </button>
                        </form>
                        <button onclick="showRejectModal()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Reject
                        </button>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Site Expense</h3>
            <form action="{{ route('site-expenses.reject', $siteExpense) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason *</label>
                    <textarea name="rejection_reason" rows="3" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection

