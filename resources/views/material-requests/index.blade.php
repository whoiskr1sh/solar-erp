@extends('layouts.app')

@section('title', 'Material Requests')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Material Requests</h1>
            <p class="text-gray-600">Manage material procurement requests and approvals</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('material-requests.dashboard') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Dashboard
            </a>
            <a href="{{ route('material-requests.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Create Request
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase mb-1">Total Requests</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['total_requests']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
            <div>
                <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Pending</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['pending_requests']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-xs font-bold text-green-600 uppercase mb-1">Approved</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['approved_requests']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div>
                <p class="text-xs font-bold text-purple-600 uppercase mb-1">Completed</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['completed_requests']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-4">
            <div>
                <p class="text-xs font-bold text-red-600 uppercase mb-1">Urgent</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['urgent_requests']) }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search requests..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            <div>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    <option value="raw_materials" {{ request('category') == 'raw_materials' ? 'selected' : '' }}>Raw Materials</option>
                    <option value="tools_equipment" {{ request('category') == 'tools_equipment' ? 'selected' : '' }}>Tools & Equipment</option>
                    <option value="consumables" {{ request('category') == 'consumables' ? 'selected' : '' }}>Consumables</option>
                    <option value="safety_items" {{ request('category') == 'safety_items' ? 'selected' : '' }}>Safety Items</option>
                </select>
            </div>
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="urgent" value="1" {{ request('urgent') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Urgent Only</span>
                </label>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    Filter
                </button>
                <a href="{{ route('material-requests.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Req #</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Request Details</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Project</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Priority</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Category</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Amount</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Required Date</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($materialRequests as $request)
                    <tr class="hover:bg-gray-50 {{ $request->is_overdue ? 'bg-red-50' : ($request->is_urgent ? 'bg-yellow-50' : '') }}">
                        <td class="px-3 py-4 text-sm text-gray-900">{{ $request->request_number }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            <div class="max-w-xs">
                                <div class="font-medium">{{ Str::limit($request->title, 30) }}</div>
                                <div class="text-gray-500 text-xs">{{ Str::limit($request->description, 20) }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ $request->requester->name }}</div>
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            {{ $request->project ? Str::limit($request->project->project_name, 15) : 'N/A' }}
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $request->priority_badge }}">
                                {{ ucfirst($request->priority) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $request->category_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $request->category)) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">₹{{ number_format($request->total_amount, 0) }}</td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $request->status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            <div class="{{ $request->is_overdue ? 'text-red-600 font-medium' : '' }}">
                                {{ $request->required_date->format('M d') }}
                                @if($request->is_overdue)
                                    <div class="text-xs text-red-500">{{ abs($request->days_until_required) }}d overdue</div>
                                @elseif($request->days_until_required <= 3)
                                    <div class="text-xs text-yellow-500">{{ $request->days_until_required }}d left</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('material-requests.show', $request) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="View">V</a>
                                <a href="{{ route('material-requests.edit', $request) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs" title="Edit">E</a>
                                @if($request->status === 'pending')
                                <form method="POST" action="{{ route('material-requests.approve', $request) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="approved_amount" value="{{ $request->total_amount }}">
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" title="Approve" onclick="return confirm('Approve this request?')">A</button>
                                </form>
                                @endif
                                @if($request->status === 'approved' || $request->status === 'pending')
                                <form method="POST" action="{{ route('material-requests.destroy', $request) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Delete">D</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            No material requests found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            {{ $materialRequests->links() }}
        </div>
    </div>
</div>
@endsection
