@extends('layouts.app')

@section('title', 'Budget Dashboard')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Budget Dashboard</h1>
            <p class="text-gray-600">Overview of all budgets and financial performance</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('budgets.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Budget
            </a>
            <a href="{{ route('budgets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Budgets
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Budget Amount -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div>
                <p class="text-sm font-bold text-blue-600 uppercase mb-1">Total Budget</p>
                <p class="text-xl font-bold text-gray-800">${{ number_format($summary['total_budget_amount'], 0) }}</p>
            </div>
        </div>

        <!-- Actual Spending -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-sm font-bold text-green-600 uppercase mb-1">Actual Spending</p>
                <p class="text-xl font-bold text-gray-800">${{ number_format($summary['total_actual_amount'], 0) }}</p>
            </div>
        </div>

        <!-- Budget Variance -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
            <div>
                <p class="text-sm font-bold text-yellow-600 uppercase mb-1">Variance</p>
                <p class="text-xl font-bold text-gray-800">${{ number_format($summary['variance'], 0) }}</p>
            </div>
        </div>

        <!-- Active Budgets -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div class="ml-4">
                <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-purple-600 uppercase mb-1">Active Budgets</p>
                <p class="text-xl font-bold text-gray-800">{{ $summary['active_budgets'] }}</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Budget Status Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Budget Status Distribution</h3>
            <div class="space-y-4">
                @foreach($statusBreakdown as $status => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($status === 'draft') bg-gray-400
                            @elseif($status === 'pending') bg-yellow-400
                            @elseif($status === 'approved') bg-green-400
                            @elseif($status === 'rejected') bg-red-400
                            @elseif($status === 'completed') bg-blue-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $status }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Budget Categories -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Budget by Category</h3>
            <div class="space-y-4">
                @foreach($categoryBreakdown as $category)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }};"></div>
                        <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $category->budget_count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Performing Budgets -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Top Performing Budgets</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Budget #</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Title</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Category</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Budget Amount</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actual Amount</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Variance %</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Status</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topBudgets as $budget)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-1 text-xs text-gray-900">{{ $budget->budget_number }}</td>
                        <td class="px-3 py-1 text-xs text-gray-900">{{ Str::limit($budget->title, 20) }}</td>
                        <td class="px-3 py-1 text-xs text-gray-900">{{ $budget->category->name }}</td>
                        <td class="px-3 py-1 text-xs text-gray-900">{{ $budget->formatted_budget_amount }}</td>
                        <td class="px-3 py-1 text-xs text-gray-900">{{ $budget->formatted_actual_amount }}</td>
                        <td class="px-3 py-1 text-xs {{ $budget->variance_percentage >= 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $budget->variance_percentage >= 0 ? '+' : '' }}{{ $budget->variance_percentage }}%
                        </td>
                        <td class="px-3 py-1 text-xs">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $budget->status_badge }}">
                                {{ ucfirst($budget->status) }}
                            </span>
                        '</td>
                        <td class="px-3 py-1 text-xs text-gray-900">
                            <div class="flex space-x-1">
                                <a href="{{ route('budgets.show', $budget) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">V</a>
                                <a href="{{ route('budgets.edit', $budget) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">E</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No budgets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Budget Activity</h3>
        <div class="space-y-4">
            @forelse($recentBudgets as $budget)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-teal-600 rounded-full mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $budget->title }}</p>
                        <p class="text-xs text-gray-500">{{ $budget->category->name }} • {{ $budget->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $budget->status_badge }}">
                        {{ ucfirst($budget->status) }}
                    </span>
                    <span class="text-sm font-medium text-gray-900">{{ $budget->formatted_budget_amount }}</span>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-8">No recent activity</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
