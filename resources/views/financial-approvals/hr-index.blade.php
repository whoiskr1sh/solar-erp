@extends('layouts.app')

@section('title', 'HR Financial Approvals')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">HR Financial Approvals</h1>
            <p class="text-gray-600 dark:text-gray-400">Review and approve site expenses and advances</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border-l-4 border-orange-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-orange-600 uppercase mb-1">Pending Site Expenses</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['pending_expenses'] }}</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-1">Pending Advances</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['pending_advances'] }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('approvals.hr.index', ['type' => 'site-expenses']) }}" class="{{ $type === 'site-expenses' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Site Expenses
                </a>
                <a href="{{ route('approvals.hr.index', ['type' => 'advances']) }}" class="{{ $type === 'advances' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Advances
                </a>
            </nav>
        </div>
    </div>

    <!-- List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Number</th>
                        <th class="px-6 py-3">Title/Purpose</th>
                        <th class="px-6 py-3">Requested By</th>
                        <th class="px-6 py-3">Amount</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($pendingRequests as $request)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium">{{ $type === 'site-expenses' ? $request->expense_number : $request->advance_number }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $request->title }}</div>
                            @if($type === 'site-expenses')
                                <div class="text-xs text-gray-500">{{ $request->project->project_name ?? 'No Project' }}</div>
                            @else
                                <div class="text-xs text-gray-500">Type: {{ ucfirst($request->advance_type) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $request->creator->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 font-bold text-teal-600 dark:text-teal-400">₹{{ number_format($request->amount, 2) }}</td>
                        <td class="px-6 py-4">{{ $type === 'site-expenses' ? $request->expense_date->format('M d, Y') : $request->advance_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <a href="{{ $type === 'site-expenses' ? route('site-expenses.show', $request) : route('advances.show', $request) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            
                            <form action="{{ $type === 'site-expenses' ? route('site-expenses.approve', $request) : route('advances.approve', $request) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                            </form>
                            
                            <button onclick="showRejectModal('{{ $type }}', {{ $request->id }})" class="text-red-600 hover:text-red-900">Reject</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No pending {{ str_replace('-', ' ', $type) }} found for HR approval.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $pendingRequests->appends(['type' => $type])->links() }}
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Reject Request</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason *</label>
                    <textarea name="rejection_reason" rows="3" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200">Cancel</button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition duration-200">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRejectModal(type, id) {
    const form = document.getElementById('rejectForm');
    const baseUrl = type === 'site-expenses' ? '/site-expenses' : '/advances';
    form.action = `${baseUrl}/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectForm').reset();
}
</script>
@endsection






