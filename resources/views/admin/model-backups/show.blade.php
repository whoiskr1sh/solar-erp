@extends('layouts.app')

@section('title', 'Backup Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between bg-white shadow rounded-lg p-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $backup->model_name }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $backup->getModelTypeDisplayAttribute() }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.model-backups.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Backups
            </a>
            @if(!$backup->is_restored && !$backup->isExpired())
            <form method="POST" action="{{ route('admin.model-backups.restore', $backup->id) }}" onsubmit="return confirm('Are you sure you want to restore this item? The backup will be marked as restored.');">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Restore Item
                </button>
            </form>
            @elseif($backup->isExpired())
            <button disabled class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                Expired
            </button>
            @else
            <button disabled class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                Already Restored
            </button>
            @endif
            @if(auth()->user()->hasRole('SUPER ADMIN'))
            <form method="POST" action="{{ route('admin.model-backups.destroy', $backup->id) }}" onsubmit="return confirm('Are you sure you want to permanently delete this backup? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Permanently
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Backup Details -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Backup Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Model Type</h3>
                <p class="text-sm text-gray-900">{{ $backup->getModelTypeDisplayAttribute() }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Original ID</h3>
                <p class="text-sm text-gray-900">{{ $backup->original_model_id ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Deleted By</h3>
                <p class="text-sm text-gray-900">{{ $backup->deletedBy->name ?? 'Unknown' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Approved By</h3>
                <p class="text-sm text-gray-900">{{ $backup->approvedBy->name ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Deleted At</h3>
                <p class="text-sm text-gray-900">{{ $backup->deleted_at ? $backup->deleted_at->format('M d, Y h:i A') : 'N/A' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Expires At</h3>
                <p class="text-sm text-gray-900">{{ $backup->expires_at ? $backup->expires_at->format('M d, Y h:i A') : 'N/A' }}</p>
                @if($backup->daysUntilExpiration() !== null)
                <p class="text-xs text-gray-500 mt-1">{{ $backup->daysUntilExpiration() }} days remaining</p>
                @endif
            </div>
            @if($backup->is_restored)
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Restored By</h3>
                <p class="text-sm text-gray-900">{{ $backup->restoredBy->name ?? 'Unknown' }}</p>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Restored At</h3>
                <p class="text-sm text-gray-900">{{ $backup->restored_at ? $backup->restored_at->format('M d, Y h:i A') : 'N/A' }}</p>
            </div>
            @endif
            @if($backup->deletion_reason)
            <div class="md:col-span-2">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Deletion Reason</h3>
                <p class="text-sm text-gray-900">{{ $backup->deletion_reason }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Model Data -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Backed Up Data</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($backup->model_data as $key => $value)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            @if(is_array($value))
                                <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                            @elseif(is_bool($value))
                                {{ $value ? 'Yes' : 'No' }}
                            @elseif($value === null)
                                <span class="text-gray-400">NULL</span>
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

