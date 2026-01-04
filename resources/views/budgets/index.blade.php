@extends('layouts.app')

@section('title', 'Budget Management')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Budget Management</h1>
            <p class="text-gray-600">Track and manage project budgets</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('budgets.dashboard') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Dashboard
            </a>
            <a href="{{ route('budget-categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Categories
            </a>
            <a href="{{ route('budgets.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Create Budget
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-green-600 uppercase mb-1">Total Budget</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_budget_amount'], 0) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-1">Actual Spent</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_actual_amount'], 0) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-purple-600 uppercase mb-1">Variance</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_budget_amount'] - $stats['total_actual_amount'], 0) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Total Budgets</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_budgets'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-red-600 uppercase mb-1">Over Budget</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['over_budget_count'] }} projects</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-teal-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-teal-600 uppercase mb-1">This Month</p>
                    <p class="text-2rl font-bold text-gray-800">${{ number_format($stats['this_month_budget'], 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="search" value="{{ request('search') }}" placeholder="Search budgets...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                <select name="project" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                <select name="period" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Periods</option>
                    <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="quarterly" {{ request('period') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                    <option value="yearly" {{ request('period') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    <option value="project" {{ request('period') == 'project' ? 'selected' : '' }}>Project</option>
                </select>
            </div>

            <div class="flex flex-wrap gap-3 items-end">
                <button type="submit" form="filterForm" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md transition duration-300">Filter</button>
                <a href="{{ route('budgets.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-300">Clear</a>
            </div>
        </form>
    </div>

    <!-- Budgets Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Budget #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Budget Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Actual Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Variance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($budgets as $budget)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $budget->budget_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($budget->title, 20) }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($budget->description, 25) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full" style="background-color: {{ $budget->category->color }}20; color: {{ $budget->category->color }};">
                                {{ $budget->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($budget->project->name ?? 'N/A', 15) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $budget->formatted_budget_amount }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $budget->formatted_actual_amount }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium {{ $budget->variance_amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $budget->variance_amount >= 0 ? '+' : '' }}{{ number_format($budget->variance_amount) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $budget->status_badge }}">
                                {{ ucfirst($budget->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ ucfirst($budget->budget_period) }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('budgets.show', $budget) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="View">V</a>
                                <a href="{{ route('budgets.edit', $budget) }}" class="bg-yellow-500 hover:bg-yellow-600 hover:text-white px-2 py-1 rounded text-xs" title="Edit">E</a>
                                @if($budget->status === 'pending')
                                <form method="POST" action="{{ route('budgets.approve', $budget) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" title="Approve">A</button>
                                </form>
                                <form method="POST" action="{{ route('budgets.reject', $budget) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Reject">R</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('budgets.destroy', $budget) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this budget?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type "submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Delete">D</button>
                                </form>
                                </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No budgets found</h3>
                            <p class="text-gray-600">Create your first budget to get started.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($budgets->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Showing {{ $budgets->firstItem() ?? 0 }} to {{ $budgets->lastItem() ?? 0 }} of {{ $budgets->total() }} results
            </div>
            <div>
                {{ $budgets->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
