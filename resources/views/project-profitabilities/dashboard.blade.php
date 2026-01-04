@extends('layouts.app')

@section('title', 'Profitability Dashboard')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profitability Dashboard</h1>
            <p class="text-gray-600">Financial performance overview</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('project-profitabilities.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Reports
            </a>
            <a href="{{ route('project-profitabilities.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Add Report
            </a>
        </div>
    </div>

    <!-- Overall Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-sm font-bold text-green-600 uppercase mb-1">Total Revenue</p>
                <p class="text-xl font-bold text-gray-800">${{ number_format($summary['total_revenue'], 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-4">
            <div>
                <p class="text-sm font-bold text-red-600 uppercase mb-1">Total Costs</p>
                <p class="text-xl font-bold text-gray-800">${{ number_format($summary['total_costs'], 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div>
                <p class="text-sm font-bold text-blue-600 uppercase mb-1">Gross Profit</p>
                <p class="text-xl font-bold text-gray-800">${{ number_format($summary['total_profit'], 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-bold text-yellow-600 uppercase mb-1">Total Projects</p>
                    <p class="text-xl font-bold text-gray-800">{{ $summary['total_projects'] }}</p>
                </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 7l-4.5 2.5L22 12V7zm-1 1v8c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2V8h2v8h12V8h2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Projects -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Top Performing Projects</h2>
                <span class="text-sm text-gray-500">By Profit Margin</span>
            </div>
            <div class="space-y-4">
                @forelse($topProjects as $project)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ Str::limit($project->project->name, 25) }}</h3>
                        <p class="text-sm text-gray-500">{{ Str::limit($project->project->client_name, 30) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">${{ number_format($project->gross_profit, 0) }}</p>
                        <p class="text-xs text-green-600">{{ number_format($project->gross_margin_percentage, 1) }}% margin</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No projects found</p>
                @endforelse
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Status Breakdown</h2>
                <span class="text-sm text-gray-500">Reports Status</span>
            </div>
            <div class="space-y-4">
                @forelse($statusBreakdown as $status => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3 
                            @if($status === 'approved') bg-green-500
                            @elseif($status === 'reviewed') bg-blue-500
                            @elseif($status === 'final') bg-purple-500
                            @else bg-gray-500
                            @endif
                        "></div>
                        <span class="font-medium text-gray-700">{{ ucfirst($status) }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm font-bold text-gray-900 mr-2">{{ $count }}</span>
                        <span class="text-xs text-gray-500">
                            {{ $summary['total_projects'] > 0 ? number_format(($count/$summary['total_projects'])*100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-lg shadow-md p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Recent Profitability Reports</h2>
                <a href="{{ route('project-profitabilities.index') }}" class="text-teal-600 hover:text-teal-700 text-sm">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Margin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentReports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($report->project->name, 20) }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($report->project->client_name, 25) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ucfirst($report->period) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($report->total_revenue, 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $report->gross_profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($report->gross_profit, 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $report->gross_margin_percentage >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($report->gross_margin_percentage, 1) }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $report->status_badge }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $report->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No reports found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('project-profitabilities.create') }}" class="flex items-center p-4 bg-teal-50 border border-teal-200 rounded-lg hover:bg-teal-100 transition duration-300">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11H13v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="font-medium text-gray-900">Create New Report</h3>
                    <p class="text-sm text-gray-500">Add a new profitability analysis</p>
                </div>
            </a>

            <a href="{{ route('project-profitabilities.index') }}" class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition duration-300">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="font-medium text-gray-900">View All Reports</h3>
                    <p class="text-sm text-gray-500">Browse all profitability reports</p>
                </div>
            </a>

            <a href="{{ route('projects.index') }}" class="flex items-center p-4 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition duration-300">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 7l-4.5 2.5L22 12V7zm-1 1v8c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2V8h2v8h12V8h2z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="font-medium text-gray-900">Manage Projects</h3>
                    <p class="text-sm text-gray-500">View and manage projects</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
