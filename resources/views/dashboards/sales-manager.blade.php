@extends('layouts.app')

@section('title', 'Sales Manager Dashboard - CRM')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg shadow-sm border border-gray-200 p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-purple-100 text-lg">Sales Manager Dashboard - CRM Focus</p>
                <p class="text-purple-200 text-sm mt-1">Last Login: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y H:i') : 'First Login' }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-4xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Leads Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Leads</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 font-medium">{{ $stats['new_leads'] }} New</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-chart-line mr-1"></i>
                    Lead Pipeline
                </div>
            </div>
        </div>

        <!-- Converted Leads Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Converted</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['converted_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 font-medium">{{ $stats['converted_leads'] > 0 ? round(($stats['converted_leads'] / $stats['total_leads']) * 100, 1) : 0 }}% Rate</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-percentage mr-1"></i>
                    Conversion Rate
                </div>
            </div>
        </div>

        <!-- Total Projects Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-project-diagram text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Projects</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_projects']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-orange-600 font-medium">{{ $stats['active_projects'] }} Active</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-tasks mr-1"></i>
                    Project Portfolio
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-rupee-sign text-emerald-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-semibold text-gray-900">₹{{ number_format($stats['total_revenue']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-emerald-600 font-medium">Paid Invoices</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-chart-bar mr-1"></i>
                    Financial Growth
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Leads -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Leads</h3>
                <a href="{{ route('leads.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentLeads as $lead)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $lead->name }}</p>
                            <p class="text-xs text-gray-500">{{ $lead->company ?? 'No Company' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                        {{ ucfirst($lead->status) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-sm text-center py-4">No recent leads</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Projects</h3>
                <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentProjects as $project)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-project-diagram text-orange-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $project->name }}</p>
                            <p class="text-xs text-gray-500">{{ $project->client->name ?? 'No Client' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-sm text-center py-4">No recent projects</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Quotations -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Quotations</h3>
                <a href="{{ route('quotations.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentQuotations as $quotation)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-invoice text-purple-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $quotation->quotation_number ?? 'QT-'.$quotation->id }}
                            </p>
                            <p class="text-xs text-gray-500">₹{{ number_format($quotation->total_amount ?? 0) }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                        {{ ucfirst($quotation->status ?? 'Draft') }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-sm text-center py-4">No recent quotations</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('leads.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-user-plus text-blue-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-blue-800">Add Lead</span>
            </a>
            <a href="{{ route('quotations.create') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <i class="fas fa-file-invoice text-orange-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-orange-800">New Quote</span>
            </a>
            <a href="{{ route('leads.index') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-list text-green-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-green-800">View Leads</span>
            </a>
            <a href="{{ route('crm.dashboard') }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                <i class="fas fa-chart-bar text-indigo-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-indigo-800">Analytics</span>
            </a>
            <a href="{{ route('projects.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-project-diagram text-purple-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-purple-800">Projects</span>
            </a>
            <a href="{{ route('leads.index', ['export' => 'excel']) }}" class="flex flex-col items-center p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors">
                <i class="fas fa-file-export text-amber-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-amber-800">Export</span>
            </a>
        </div>
    </div>
</div>
@endsection
