@extends('layouts.app')

@section('title', 'Project Profitability')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Project Profitability</h1>
            <p class="text-gray-600">Track and analyze project financial performance</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('project-profitabilities.dashboard') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Dashboard
            </a>
            <a href="{{ route('project-profitabilities.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Add Report
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div>
                <p class="text-xs font-bold text-green-600 uppercase mb-1">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_revenue'], 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-6">
            <div>
                <p class="text-xs font-bold text-red-600 uppercase mb-1">Total Costs</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_costs'], 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase mb-1">Net Profit</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_profit'], 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Avg Margin</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['avg_margin'], 1) }}%</p>
                </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-purple-600 uppercase mb-1">Total Projects</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_projects'] }}</p>
                </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 7l-4.5 2.5L22 12V7zm-1 1v8c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2V8h2v8h12V8h2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-teal-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-teal-600 uppercase mb-1">Approved</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['approved_projects'] }}</p>
                </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17l-4.17-4.17 1.41-1.41L9 13.35l9.76-9.76 1.41 1.41-9.76 9.76L9 16.17z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="search" value="{{ request('search') }}" placeholder="Search projects...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status')== 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="reviewed" {{ request('status')== 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="approved" {{ request('status')== 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="final" {{ request('status')== 'final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                <select name="period" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Periods</option>
                    <option value="monthly" {{ request('period')== 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="quarterly" {{ request('period')== 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    <option value="yearly" {{ request('period')== 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>

            <div class="flex flex-wrap gap-3 items-end">
                <button type="submit" form="filterForm" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md transition duration-300">Filter</button>
                <a href="{{ route('project-profitabilities.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-300">Clear</a>
            </div>
        </form>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Costs</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Profit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Margin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($profitabilities as $profitability)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($profitability->project->name, 20) }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($profitability->project->client_name, 25) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ ucfirst($profitability->period) }}</div>
                            <div class="text-sm text-gray-500">{{ $profitability->start_date->format('M d') }} - {{ $profitability->end_date->format('M d') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${{ number_format($profitability->total_revenue, 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${{ number_format($profitability->total_costs, 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium {{ $profitability->gross_profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($profitability->gross_profit, 0) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium {{ $profitability->gross_margin_percentage >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($profitability->gross_margin_percentage, 1) }}%
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $profitability->status_badge }}">
                                {{ ucfirst($profitability->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $profitability->created_at->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($profitability->creator->name, 12) }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('project-profitabilities.show', $profitability) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="View">V</a>
                                <a href="{{ route('project-profitabilities.edit', $profitability) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs" title="Edit">E</a>
                                @if($profitability->status === 'draft')
                                <form method="POST" action="{{ route('project-profitabilities.approve', $profitability) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" title="Approve">A</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('project-profitabilities.destroy', $profitability) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this profitability report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Delete">D</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No profitability reports found</h3>
                            <p class="text-gray-600">Create your first project profitability report to get started.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($profitabilities->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Showing {{ $profitabilities->firstItem() ?? 0 }} to {{ $profitabilities->lastItem() ?? 0 }} of {{ $profitabilities->total() }} results
            </div>
            <div>
                {{ $profitabilities->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
