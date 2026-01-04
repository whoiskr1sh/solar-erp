@extends('layouts.app')

@section('title', 'View Profitability Report')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profitability Report</h1>
            <p class="text-gray-600">{{ $projectProfitability->project->name }} - {{ ucfirst($projectProfitability->period) }} Report</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('project-profitabilities.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Reports
            </a>
            <a href="{{ route('project-profitabilities.edit', $projectProfitability) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Edit Report
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $projectProfitability->project->name }}</h2>
                <p class="text-gray-600">{{ ucfirst($projectProfitability->period) }} Report • {{ $projectProfitability->start_date->format('M d, Y') }} - {{ $projectProfitability->end_date->format('M d, Y') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $projectProfitability->status_badge }}">
                    {{ ucfirst($projectProfitability->status) }}
                </span>
                <p class="text-sm text-gray-500 mt-1">Created {{ $projectProfitability->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="text-sm font-bold text-green-600 uppercase mb-1">Total Revenue</div>
            <div class="text-3xl font-bold text-gray-800">${{ number_format($projectProfitability->total_revenue, 2) }}</div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-6">
            <div class="text-sm font-bold text-red-600 uppercase mb-1">Total Costs</div>
            <div class="text-3xl font-bold text-gray-800">${{ number_format($projectProfitability->total_costs, 2) }}</div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="text-sm font-bold text-blue-600 uppercase mb-1">Gross Profit</div>
            <div class="text-3xl font-bold {{ $projectProfitability->gross_profit >= 0 ? 'text-blue-600' : 'text-red-600' }}">${{ number_format($projectProfitability->gross_profit, 2) }}</div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-6">
            <div class="text-sm font-bold text-yellow-600 uppercase mb-1">Gross Margin</div>
            <div class="text-3xl font-bold {{ $projectProfitability->gross_margin_percentage >= 0 ? 'text-yellow-600' : 'text-red-600' }}">{{ number_format($projectProfitability->gross_margin_percentage, 1) }}%</div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="text-sm font-bold text-purple-600 uppercase mb-1">Completion</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($projectProfitability->completion_percentage, 1) }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Breakdown -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Revenue Breakdown</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Contract Value</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->contract_value, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Progress Billing</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->progress_billing, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Overrun Revenue</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->overrun_revenue, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 bg-green-50 rounded-lg px-3">
                    <span class="font-bold text-green-800">Total Revenue</span>
                    <span class="font-bold text-green-800 text-lg">${{ number_format($projectProfitability->total_revenue, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Cost Breakdown -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Cost Breakdown</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Material Costs</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->material_costs, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Labor Costs</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->labor_costs, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Equipment Costs</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->equipment_costs, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Transportation Costs</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->transportation_costs, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Permits Costs</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->permits_costs, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Overhead Costs</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->overhead_costs, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Subcontractor Costs</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->subcontractor_costs, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 bg-red-50 rounded-lg px-3">
                    <span class="font-bold text-red-800">Total Costs</span>
                    <span class="font-bold text-red-800 text-lg">${{ number_format($projectProfitability->total_costs, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Additional Financial Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Additional Financial Info</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Change Order Amount</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->change_order_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-700">Retention Amount</span>
                    <span class="font-medium text-gray-900">${{ number_format($projectProfitability->retention_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 bg-blue-50 rounded-lg px-3">
                    <span class="font-bold text-blue-800">Net Margin</span>
                    <span class="font-bold text-blue-800 text-lg">{{ number_format($projectProfitability->net_margin_percentage, 1) }}%</span>
                </div>
                <div class="flex justify-between items-center py-2 bg-purple-50 rounded-lg px-3">
                    <span class="font-bold text-purple-800">Net Profit</span>
                    <span class="font-bold text-purple-800 text-lg">${{ number_format($projectProfitability->net_profit, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Project Progress -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Project Progress</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-700">Project Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ ucfirst($projectProfitability->project->status) }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-700">Days Completed</span>
                    <span class="font-medium text-gray-900">{{ $projectProfitability->days_completed }} / {{ $projectProfitability->total_days }} days</span>
                </div>
                <div class="py-2">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Progress</span>
                        <span>{{ number_format($projectProfitability->completion_percentage, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $projectProfitability->completion_percentage }}%"></div>
                    </div>
                </div>
                <div class="pt-2 border-t border-gray-200">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700">Report Creator</span>
                        <span class="font-medium text-gray-900">{{ $projectProfitability->creator->name }}</span>
                    </div>
                    @if($projectProfitability->reviewer)
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700">Reviewed By</span>
                        <span class="font-medium text-gray-900">{{ $projectProfitability->reviewer->name }}</span>
                    </div>
                    @endif
                    @if($projectProfitability->reviewed_at)
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-700">Reviewed At</span>
                        <span class="text-sm text-gray-900">{{ $projectProfitability->reviewed_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($projectProfitability->notes)
    <!-- Notes -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Notes</h3>
        <div class="text-gray-700 whitespace-pre-wrap">{{ $projectProfitability->notes }}</div>
    </div>
    @endif

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('project-profitabilities.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-300">
            Back to Reports
        </a>
        <a href="{{ route('project-profitabilities.edit', $projectProfitability) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg transition duration-300">
            Edit Report
        </a>
        @if($projectProfitability->status === 'draft')
        <form method="POST" action="{{ route('project-profitabilities.approve', $projectProfitability) }}" class="inline">
            @csrf
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-300">
                Approve Report
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
