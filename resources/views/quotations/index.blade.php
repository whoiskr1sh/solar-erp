@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Quotations</h2>
                        <p class="text-gray-600 mt-1">Manage your quotations and proposals</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('quotations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Quotation
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Quotations</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Approved</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Value</p>
                        <p class="text-lg font-semibold text-gray-900">₹{{ number_format($stats['total_amount'] ?? 0, 0) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Filters & Search</h3>
            </div>
            
            <!-- Quick Filters -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('quotations.index') }}" class="px-3 py-1 text-sm rounded-full {{ !request()->hasAny(['status', 'priority', 'search']) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All Quotations
                </a>
                <a href="{{ route('quotations.index', ['status' => 'draft']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') == 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Draft
                </a>
                <a href="{{ route('quotations.index', ['status' => 'sent']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') == 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Sent
                </a>
                <a href="{{ route('quotations.index', ['status' => 'approved']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') == 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Approved
                </a>
                <a href="{{ route('quotations.index', ['status' => 'rejected']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Rejected
                </a>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-4">
                <div class="flex space-x-3">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by quotation number, client, or project..." class="block w-full pl-10 pr-3 py-2 px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @if(request()->hasAny(['status', 'priority', 'search']))
                    <a href="{{ route('quotations.index') }}" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear All Filters
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Advanced Filters -->
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quotation Type</label>
                    <select name="quotation_type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="solar_chakki" {{ request('quotation_type') == 'solar_chakki' ? 'selected' : '' }}>Solar Chakki</option>
                        <option value="solar_street_light" {{ request('quotation_type') == 'solar_street_light' ? 'selected' : '' }}>Solar Street Light</option>
                        <option value="commercial" {{ request('quotation_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="subsidy_quotation" {{ request('quotation_type') == 'subsidy_quotation' ? 'selected' : '' }}>Subsidy Quotation</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                    <select name="client_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Clients</option>
                        @if(isset($clients))
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                    <select name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Projects</option>
                        @if(isset($projects))
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div class="md:col-span-3 flex justify-end space-x-3">
                    <a href="{{ route('quotations.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Clear All
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-blue-700">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Quotations Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Quote Details</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Client</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Type</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Amount</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Valid Until</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Revised Quotation</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($quotations as $quotation)
                        <tr class="hover:bg-gray-50 {{ $quotation->needsFollowUp() ? 'bg-yellow-50 border-l-4 border-yellow-400' : '' }}">
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($quotation->needsFollowUp())
                                        <svg class="w-4 h-4 text-yellow-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                    <div>
                                <div class="text-xs font-medium text-gray-900">{{ $quotation->quotation_number ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $quotation->created_at->format('M d') ?? 'N/A' }}</div>
                                        @if($quotation->last_modified_at)
                                            <div class="text-xs text-gray-400">Modified: {{ $quotation->last_modified_at->setTimezone('Asia/Kolkata')->format('M d, Y h:i A') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-900">{{ Str::limit($quotation->client->name ?? 'N/A', 20) }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($quotation->project->name ?? 'N/A', 15) }}</div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $quotation->quotation_type_label }}
                                </span>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'sent' => 'bg-blue-100 text-blue-800',
                                        'accepted' => 'bg-green-100 text-green-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'expired' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                    $statusColor = $statusColors[$quotation->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $statusColor }}">
                                    {{ Str::limit(ucfirst($quotation->status ?? 'N/A'), 8) }}
                                </span>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-900">₹{{ number_format($quotation->total_amount ?? 0, 0) }}</div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="text-xs text-gray-900">{{ $quotation->valid_until ? $quotation->valid_until->format('M d') : 'N/A' }}</div>
                                @if($quotation->valid_until && $quotation->valid_until < now())
                                    <span class="text-xs text-red-600">Expired</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                @if($quotation->is_revision)
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-pink-100 text-pink-800">
                                        Revision #{{ $quotation->revision_number ?? 'N/A' }}
                                    </span>
                                    @if($quotation->is_latest)
                                        <div class="text-xs text-green-700 mt-1">Latest</div>
                                    @endif
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-50 text-blue-800">
                                        Original
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-xs font-medium">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('quotations.show', $quotation) }}" class="text-blue-600 hover:text-blue-900 text-xs leading-none">View</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('quotations.edit', $quotation) }}" class="text-indigo-600 hover:text-indigo-900 text-xs leading-none">Edit</a>
                                    @if($quotation->status !== 'approved')
                                    <span class="text-gray-300">|</span>
                                    <form method="POST" action="{{ route('quotations.update', $quotation) }}" class="inline-block m-0 p-0">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="text-green-600 hover:text-green-900 text-xs leading-none bg-transparent border-none p-0">Approve</button>
                                    </form>
                                    @endif
                                    <span class="text-gray-300">|</span>
                                    <button type="button" onclick="openDeleteModal({{ $quotation->id }}, '{{ $quotation->quotation_number }}')" class="text-red-600 hover:text-red-900 text-xs leading-none bg-transparent border-none p-0">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No Quotations found</p>
                                    <p class="text-sm">Get started by creating your first quotation.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($quotations->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $quotations->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Quotation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Quotation</h3>
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete quotation: <strong id="deleteQuotationNumber"></strong>?</p>
            <p class="text-xs text-yellow-600 mb-4">This will require approval from Admin.</p>
            
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label for="delete_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Deletion <span class="text-red-500">*</span></label>
                    <textarea id="delete_reason" name="reason" rows="3" required minlength="10"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                              placeholder="Enter reason for deletion (minimum 10 characters)..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Please provide a detailed reason for deletion (minimum 10 characters).</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        Request Deletion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteModal(quotationId, quotationNumber) {
    document.getElementById('deleteQuotationNumber').textContent = quotationNumber;
    document.getElementById('deleteForm').action = '/quotations/' + quotationId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteForm').reset();
}
</script>
@endsection