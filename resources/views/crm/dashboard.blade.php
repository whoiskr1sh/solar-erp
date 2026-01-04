@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">CRM Dashboard</h1>
            <p class="text-gray-600">Customer relationship management overview</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('leads.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Lead
            </a>
            <a href="{{ route('quotations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Create Quotation
            </a>
        </div>
    </div>

    <!-- Lead Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Total Leads</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $leadStats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Qualified</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $leadStats['qualified'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Converted</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $leadStats['converted'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Conversion Rate</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $conversionRate }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Lost</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $leadStats['lost'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Lead Pipeline -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Lead Pipeline</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">New Leads</span>
                    <div class="flex items-center">
                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $leadStats['total'] > 0 ? ($leadStats['new'] / $leadStats['total']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $leadStats['new'] }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Qualified</span>
                    <div class="flex items-center">
                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $leadStats['total'] > 0 ? ($leadStats['qualified'] / $leadStats['total']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $leadStats['qualified'] }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Converted</span>
                    <div class="flex items-center">
                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $leadStats['total'] > 0 ? ($leadStats['converted'] / $leadStats['total']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $leadStats['converted'] }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Lost</span>
                    <div class="flex items-center">
                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ $leadStats['total'] > 0 ? ($leadStats['lost'] / $leadStats['total']) * 100 : 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $leadStats['lost'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lead Sources -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Lead Sources</h3>
            <div class="space-y-3">
                @forelse($leadSources as $source)
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $source->source)) }}</span>
                        <div class="flex items-center">
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $leadSources->sum('count') > 0 ? ($source->count / $leadSources->sum('count')) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $source->count }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No lead source data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Leads -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Leads</h3>
                <a href="{{ route('leads.index') }}" class="text-teal-600 hover:text-teal-500 text-sm">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentLeads as $lead)
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $lead->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $lead->company ?: 'No company' }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $lead->status_badge }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent leads</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Projects</h3>
                <a href="{{ route('projects.index') }}" class="text-teal-600 hover:text-teal-500 text-sm">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentProjects as $project)
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $project->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $project->client ? $project->client->name : 'No client' }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $project->status_badge }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent projects</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Financial Stats -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Overview</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Total Revenue</span>
                    <span class="text-lg font-semibold text-green-600">&#8377; {{ number_format($financialStats['total_revenue'], 0) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Pending Revenue</span>
                    <span class="text-lg font-semibold text-yellow-600">&#8377; {{ number_format($financialStats['pending_revenue'], 0) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Quotation Value</span>
                    <span class="text-lg font-semibold text-blue-600">&#8377; {{ number_format($financialStats['quotation_value'], 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Invoices</h3>
                <a href="{{ route('invoices.index') }}" class="text-teal-600 hover:text-teal-500 text-sm">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentInvoices as $invoice)
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $invoice->invoice_number }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $invoice->client ? $invoice->client->name : 'No client' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-semibold text-gray-900">&#8377; {{ number_format($invoice->total_amount, 0) }}</span>
                            <div class="text-xs text-gray-500">{{ ucfirst($invoice->status) }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent invoices</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Quotations -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Quotations</h3>
                <a href="{{ route('quotations.index') }}" class="text-teal-600 hover:text-teal-500 text-sm">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentQuotations as $quotation)
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $quotation->quotation_number }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $quotation->client ? $quotation->client->name : 'No client' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-semibold text-gray-900">&#8377; {{ number_format($quotation->total_amount, 0) }}</span>
                            <div class="text-xs text-gray-500">{{ ucfirst($quotation->status) }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent quotations</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Task Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Task Statistics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Task Overview</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $taskStats['total'] }}</div>
                    <div class="text-sm text-gray-500">Total Tasks</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $taskStats['completed'] }}</div>
                    <div class="text-sm text-gray-500">Completed</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $taskStats['pending'] }}</div>
                    <div class="text-sm text-gray-500">Pending</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $taskStats['overdue'] }}</div>
                    <div class="text-sm text-gray-500">Overdue</div>
                </div>
            </div>
        </div>

        <!-- Recent Tasks -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Tasks</h3>
                <a href="{{ route('tasks.index') }}" class="text-teal-600 hover:text-teal-500 text-sm">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentTasks as $task)
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $task->title }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $task->project ? $task->project->name : 'No project' }}</p>
                        </div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $task->status_badge }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent tasks</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection


