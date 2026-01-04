@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Costing & Budgeting</h1>
            <p class="text-gray-600">Manage project costings and budgets</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('costing.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Costing
            </a>
            <a href="{{ route('costing.export', request()->query()) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
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
                <div class="bg-gray-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Draft</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['draft'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-yellow-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Pending</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['pending'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-green-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Approved</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['approved'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-purple-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Total Value</p>
                <p class="text-lg font-bold text-gray-900">₹ {{ number_format($stats['total_value'], 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-indigo-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">This Month</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['this_month'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-3 mb-4">
        <form method="GET" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search costings..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                <select name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Created By</label>
                <select name="created_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('created_by') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div class="md:col-span-6 flex justify-end space-x-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                    Apply Filters
                </button>
                <a href="{{ route('costing.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Costings Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-xs table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Costing #</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Project</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Client</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Total Cost</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Status</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Created By</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Created At</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($costings as $costing)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-2">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $costing->costing_number }}">{{ $costing->costing_number }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs text-gray-900 truncate" title="{{ $costing->project_name }}">{{ $costing->project_name }}</div>
                            @if($costing->project)
                            <div class="text-xs text-gray-500 truncate" title="{{ $costing->project->name }}">{{ $costing->project->name }}</div>
                            @endif
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs text-gray-900 truncate" title="{{ $costing->client_name }}">{{ $costing->client_name }}</div>
                            @if($costing->client_email)
                            <div class="text-xs text-gray-500 truncate" title="{{ $costing->client_email }}">{{ $costing->client_email }}</div>
                            @endif
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $costing->formatted_total_cost }}">{{ $costing->formatted_total_cost }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $costing->status_badge }}" title="{{ ucfirst($costing->status) }}">
                                {{ ucfirst($costing->status) }}
                            </span>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs text-gray-900 truncate" title="{{ $costing->creator->name }}">{{ $costing->creator->name }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs text-gray-900 truncate" title="{{ $costing->created_at->format('M d, Y h:i A') }}">{{ $costing->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $costing->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-2 py-2 text-xs font-medium">
                            <div class="flex flex-wrap gap-0.5 truncate">
                                <a href="{{ route('costing.show', $costing) }}" class="text-teal-600 hover:text-teal-900 text-xs whitespace-nowrap px-1">V</a>
                                @if($costing->status === 'draft')
                                <a href="{{ route('costing.edit', $costing) }}" class="text-blue-600 hover:text-blue-900 text-xs whitespace-nowrap px-1">E</a>
                                @endif
                                @if(in_array($costing->status, ['draft', 'pending']))
                                <form method="POST" action="{{ route('costing.approve', $costing) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 text-xs whitespace-nowrap px-1">A</button>
                                </form>
                                <form method="POST" action="{{ route('costing.reject', $costing) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs whitespace-nowrap px-1">R</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('costing.destroy', $costing) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this costing?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs whitespace-nowrap px-1">D</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-2 py-6 text-center">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">No costings found</h3>
                            <p class="text-xs text-gray-600">Get started by creating your first costing.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($costings->hasPages())
        <div class="px-2 py-2 border-t border-gray-200">
            {{ $costings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
