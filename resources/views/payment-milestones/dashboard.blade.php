@extends('layouts.app')

@section('title', 'Payment Milestones Dashboard')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Milestones Dashboard</h1>
            <p class="text-gray-600">Overview of payment milestones and financial tracking</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('payment-milestones.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Milestones
            </a>
            <a href="{{ route('payment-milestones.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Milestone
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Milestones -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div>
                <p class="text-sm font-bold text-blue-600 uppercase mb-1">Total Milestones</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['total_milestones']) }}</p>
            </div>
        </div>

        <!-- Pending Amount -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-sm font-bold text-green-600 uppercase mb-1">Total Milestone Amount</p>
                <p class="text-xl font-bold text-gray-800">${{ number_format($summary['total_milestone_amount'], 0) }}</p>
            </div>
        </div>

        <!-- Paid Amount -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div>
                <p class="text-sm font-bold text-purple-600 uppercase mb-1">Total Paid Amount</p>
                <p class="text-xl font-bold text-gray-800">${{ number_format($summary['total_paid_amount'], 0) }}</p>
            </div>
        </div>

        <!-- Pending Amount -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-orange-500 p-4">
            <div class="ml-4">
                <svg class="w-8 h-8 text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-orange-600 uppercase mb-1">Pending Amount</p>
                <p class="text-xl font-bold text-gray-800">${{ number_format($summary['pending_amount'], 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Milestone Status Distribution</h3>
            <div class="space-y-4">
                @forelse($statusBreakdown as $status => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($status === 'pending') bg-yellow-400
                            @elseif($status === 'in_progress') bg-blue-400
                            @elseif($status === 'completed') bg-green-400
                            @elseif($status === 'paid') bg-purple-400
                            @elseif($status === 'overdue') bg-red-400
                            @elseif($status === 'cancelled') bg-gray-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No milestones found</div>
                @endforelse
            </div>
        </div>

        <!-- Type Distribution Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Milestone Types Distribution</h3>
            <div class="space-y-4">
                @forelse($typeBreakdown as $type => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($type === 'advance') bg-purple-400
                            @elseif($type === 'progress') bg-blue-400
                            @elseif($type === 'completion') bg-green-400
                            @elseif($type === 'retention') bg-yellow-400
                            @elseif($type === 'final') bg-gray-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $type }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No milestones found</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Milestones -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Top Valued Milestones</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">MS #</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Title</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Project</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Amount</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Due Date</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Payment</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topMilestones as $milestone)
                    <tr class="hover:bg-gray-50 {{ $milestone->is_overdue ? 'bg-red-50' : '' }}">
                        <td class="px-3 py-4 text-sm text-gray-900">{{ $milestone->milestone_number }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900">{{ Str::limit($milestone->title, 25) }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            {{ $milestone->project ? Str::limit($milestone->project->project_name, 15) : 'N/A' }}
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">{{ $milestone->formatted_milestone_amount }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            <div class="{{ $milestone->is_overdue ? 'text-red-600 font-medium' : '' }}">
                                {{ $milestone->due_date->format('M d') }}
                                @if($milestone->is_overdue)
                                    <div class="text-xs text-red-500">{{ $milestone->delay_days }}d overdue</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $milestone->status_badge }}">
                                {{ ucfirst($milestone->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $milestone->payment_status_badge }}">
                                {{ ucfirst($milestone->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No milestones found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity & Overdue Milestones -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Milestones -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Milestone Activity</h3>
            <div class="space-y-4">
                @forelse($recentMilestones as $milestone)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-teal-600 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($milestone->title, 30) }}</p>
                            <p class="text-xs text-gray-500">{{ $milestone->project->project_name ?? 'Independent' }} • {{ $milestone->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $milestone->status_badge }}">
                            {{ ucfirst($milestone->status) }}
                        </span>
                        <span class="text-sm font-medium text-gray-900">{{ $milestone->formatted_milestone_amount }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">No recent activity</div>
                @endforelse
            </div>
        </div>

        <!-- Overdue Milestones -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Overdue Milestones</h3>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                    {{ $summary['overdue'] }} Overdue
                </span>
            </div>
            <div class="space-y-4">
                @forelse($overdueMilestones as $milestone)
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-red-600 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($milestone->title, 25) }}</p>
                            <p class="text-xs text-red-600">{{ $milestone->delay_days }} days overdue • {{ $milestone->due_date->format('M d') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900">{{ $milestone->formatted_milestone_amount }}</span>
                        <a href="{{ route('payment-milestones.show', $milestone) }}" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">Review</a>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">No overdue milestones</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
