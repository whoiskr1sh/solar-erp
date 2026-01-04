@extends('layouts.app')

@section('title', 'Material Requests Dashboard')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Material Requests Dashboard</h1>
            <p class="text-gray-600">Overview of material procurement and approval workflows</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('material-requests.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Requests
            </a>
            <a href="{{ route('material-requests.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Request
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Requests -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div>
                <p class="text-sm font-bold text-blue-600 uppercase mb-1">Total Requests</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['total_requests']) }}</p>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
            <div>
                <p class="text-sm font-bold text-yellow-600 uppercase mb-1">Pending Requests</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['pending_requests']) }}</p>
            </div>
        </div>

        <!-- Approved Requests -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-sm font-bold text-green-600 uppercase mb-1">Approved Requests</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['approved_requests']) }}</p>
            </div>
        </div>

        <!-- Completed Requests -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div>
                <p class="text-sm font-bold text-purple-600 uppercase mb-1">Completed</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['completed_requests']) }}</p>
            </div>
        </div>
    </div>

    <!-- Performance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Urgent Requests -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-4">
            <div>
                <p class="text-sm font-bold text-red-600 uppercase mb-1">Urgent Requests</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['urgent_requests']) }}</p>
            </div>
        </div>

        <!-- Overdue Requests -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-orange-500 p-4">
            <div>
                <p class="text-sm font-bold text-orange-600 uppercase mb-1">Overdue Requests</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['overdue_requests']) }}</p>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-cyan-500 p-4">
            <div>
                <p class="text-sm font-bold text-cyan-600 uppercase mb-1">This Month</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['this_month_requests']) }}</p>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Value -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div>
                <p class="text-sm font-bold text-green-600 uppercase mb-1">Total Value</p>
                <p class="text-2xl font-bold text-gray-800">₹{{ number_format($summary['total_value'], 0) }}</p>
            </div>
        </div>

        <!-- Approved Value -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div>
                <p class="text-sm font-bold text-blue-600 uppercase mb-1">Approved Value</p>
                <p class="text-2xl font-bold text-gray-800">₹{{ number_format($summary['approved_value'], 0) }}</p>
            </div>
        </div>

        <!-- Approval Rate -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div>
                <p class="text-sm font-bold text-purple-600 uppercase mb-1">Approval Rate</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $summary['total_value'] > 0 ? number_format(($summary['approved_value'] / $summary['total_value']) * 100, 1) : 0 }}%
                </p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Request Status Distribution</h3>
            <div class="space-y-4">
                @forelse($statusBreakdown as $status => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($status === 'draft') bg-gray-400
                            @elseif($status === 'pending') bg-yellow-400
                            @elseif($status === 'approved') bg-green-400
                            @elseif($status === 'in_progress') bg-blue-400
                            @elseif($status === 'partial') bg-purple-400
                            @elseif($status === 'completed') bg-green-400
                            @elseif($status === 'rejected') bg-red-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No requests found</div>
                @endforelse
            </div>
        </div>

        <!-- Category Distribution Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Request Categories Distribution</h3>
            <div class="space-y-4">
                @forelse($categoryBreakdown as $category => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($category === 'raw_materials') bg-blue-400
                            @elseif($category === 'tools_equipment') bg-purple-400
                            @elseif($category === 'consumables') bg-green-400
                            @elseif($category === 'safety_items') bg-red-400
                            @elseif($category === 'electrical') bg-yellow-400
                            @elseif($category === 'mechanical') bg-gray-400
                            @else bg-indigo-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $category)) }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No categories found</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Priority & Type Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Priority Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Priority Distribution</h3>
            <div class="space-y-4">
                @forelse($priorityBreakdown as $priority => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($priority === 'low') bg-blue-400
                            @elseif($priority === 'medium') bg-green-400
                            @elseif($priority === 'high') bg-yellow-400
                            @elseif($priority === 'urgent') bg-red-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $priority }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No priorities found</div>
                @endforelse
            </div>
        </div>

        <!-- Request Type Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Request Types Distribution</h3>
            <div class="space-y-4">
                @forelse($typeBreakdown as $type => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($type === 'purchase') bg-green-400
                            @elseif($type === 'rental') bg-blue-400
                            @elseif($type === 'transfer') bg-purple-400
                            @elseif($type === 'emergency') bg-red-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $type }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No types found</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent & Urgent Requests -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Requests -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Material Requests</h3>
            <div class="space-y-4">
                @forelse($recentRequests as $request)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-teal-600 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($request->title, 30) }}</p>
                            <p class="text-xs text-gray-500">{{ $request->project->project_name ?? 'Independent' }} • {{ $request->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $request->status_badge }}">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </span>
                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($request->total_amount, 0) }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">No recent requests</div>
                @endforelse
            </div>
        </div>

        <!-- Urgent Requests -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Urgent Requests</h3>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                    {{ $summary['urgent_requests'] }} Urgent
                </span>
            </div>
            <div class="space-y-4">
                @forelse($urgentRequests as $request)
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-red-600 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($request->title, 25) }}</p>
                            <p class="text-xs text-red-600">
                                {{ $request->priority === 'urgent' ? 'Urgent Priority' : 'Due Soon' }} • 
                                {{ $request->required_date->format('M d') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($request->total_amount, 0) }}</span>
                        <a href="{{ route('material-requests.show', $request) }}" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Review</a>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">No urgent requests</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
