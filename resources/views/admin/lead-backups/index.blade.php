@extends('layouts.app')

@section('title', 'Lead Backups')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Lead Backups</h1>
            <p class="mt-2 text-gray-600">View deleted leads that are backed up for 40 days</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('admin.lead-backups.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                       placeholder="Name, email, phone, company...">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active (Not Expired)</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deleted From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deleted To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Backups Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Deleted Leads Backup</h2>
            
            @if($backups->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead Name</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted By</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires At</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($backups as $backup)
                        <tr class="hover:bg-gray-50 {{ $backup->isExpired() ? 'bg-red-50' : '' }}">
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $backup->name }}</div>
                                @if($backup->original_lead_id)
                                <div class="text-xs text-gray-500">ID: {{ $backup->original_lead_id }}</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $backup->phone }}</div>
                                @if($backup->email)
                                <div class="text-xs text-gray-500">{{ $backup->email }}</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $backup->company ?? 'N/A' }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $backup->deletedBy->name ?? 'Unknown' }}</div>
                                @if($backup->approvedBy)
                                <div class="text-xs text-gray-500">Approved by: {{ $backup->approvedBy->name }}</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $backup->deleted_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $backup->deleted_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($backup->expires_at)
                                    <div class="text-sm text-gray-900">{{ $backup->expires_at->format('M d, Y') }}</div>
                                    <div class="text-xs {{ $backup->isExpired() ? 'text-red-600' : 'text-gray-500' }}">
                                        {{ $backup->daysUntilExpiration() }} days left
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500">Never</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($backup->isExpired())
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.lead-backups.show', $backup->id) }}" 
                                       class="text-teal-600 hover:text-teal-900">
                                        View
                                    </a>
                                    @if(!$backup->isExpired())
                                    <form method="POST" action="{{ route('admin.lead-backups.restore', $backup->id) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('Are you sure you want to restore this lead? The backup will be deleted after restoration.');">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900">
                                            Restore
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $backups->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No backups found</h3>
                <p class="mt-1 text-sm text-gray-500">There are no lead backups matching your criteria.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

