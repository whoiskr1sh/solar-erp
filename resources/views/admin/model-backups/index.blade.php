@extends('layouts.app')

@section('title', 'All Backups')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">All Backups</h1>
            <p class="mt-2 text-gray-600">View all deleted items that are backed up for 40 days</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('admin.model-backups.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                       placeholder="Search by name or type...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Model Type</label>
                <select name="model_type" id="model_type_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Types</option>
                    @if(isset($modelTypes) && $modelTypes->count() > 0)
                        @foreach($modelTypes as $type)
                            <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="restored" {{ request('status') == 'restored' ? 'selected' : '' }}>Restored</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
            </div>
            <div class="md:col-span-5 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'model_type', 'status', 'date_from', 'date_to']))
                <a href="{{ route('admin.model-backups.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Backups Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($backups->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted By</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($backups as $backup)
                    <tr class="hover:bg-gray-50 {{ $backup->isExpired() ? 'bg-red-50' : '' }} {{ $backup->is_restored ? 'bg-green-50' : '' }}">
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $backup->model_name }}</div>
                            @if($backup->original_model_id)
                            <div class="text-xs text-gray-500">ID: {{ $backup->original_model_id }}</div>
                            @endif
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $backup->getModelTypeDisplayAttribute() }}</div>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $backup->deletedBy->name ?? 'Unknown' }}</div>
                            @if($backup->approvedBy)
                            <div class="text-xs text-gray-500">Approved by: {{ $backup->approvedBy->name }}</div>
                            @endif
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $backup->deleted_at ? $backup->deleted_at->format('M d, Y') : 'N/A' }}</div>
                            @if($backup->deleted_at)
                            <div class="text-xs text-gray-500">{{ $backup->deleted_at->format('h:i A') }}</div>
                            @endif
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap">
                            @if($backup->is_restored)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Restored
                                </span>
                                @if($backup->restored_at)
                                <div class="text-xs text-gray-500 mt-1">{{ $backup->restored_at->format('M d, Y') }}</div>
                                @endif
                            @elseif($backup->isExpired())
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Expired
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Active
                                </span>
                                @if($backup->daysUntilExpiration() !== null)
                                <div class="text-xs text-gray-500 mt-1">{{ $backup->daysUntilExpiration() }} days left</div>
                                @endif
                            @endif
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.model-backups.show', $backup->id) }}" 
                                   class="text-teal-600 hover:text-teal-900">
                                    View
                                </a>
                                @if(!$backup->is_restored && !$backup->isExpired())
                                <form method="POST" action="{{ route('admin.model-backups.restore', $backup->id) }}" 
                                      class="inline" 
                                      onsubmit="return confirm('Are you sure you want to restore this item? The backup will be marked as restored.');">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                        Restore
                                    </button>
                                </form>
                                @endif
                                @if(auth()->user()->hasRole('SUPER ADMIN'))
                                <form method="POST" action="{{ route('admin.model-backups.destroy', $backup->id) }}" 
                                      class="inline" 
                                      onsubmit="return confirm('Are you sure you want to permanently delete this backup? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Delete
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
        <div class="mt-6 px-6 pb-6">
            {{ $backups->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No backups found</h3>
            <p class="mt-1 text-sm text-gray-500">No deleted items have been backed up yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection

