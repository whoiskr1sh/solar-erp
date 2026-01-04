@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Welcome Header -->
    <div id="welcome-message" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 transition-all duration-500 ease-in-out">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0 pr-2">
                <h1 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 mb-1 truncate">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-sm sm:text-base text-gray-600">Here's what's happening with your solar business today</p>
            </div>
            <div class="hidden md:block flex-shrink-0">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
        <!-- Total Leads Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="flex items-center flex-1 min-w-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-2 sm:ml-3 flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Leads</p>
                        <p class="text-xl sm:text-2xl font-semibold text-gray-900 truncate">{{ number_format($stats['total_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between mt-2 sm:mt-0">
                <span class="text-xs sm:text-sm text-green-600 font-medium truncate pr-1">+12%</span>
                <div class="flex items-center text-xs text-gray-500 flex-shrink-0 hidden sm:flex">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Trending up
                </div>
            </div>
        </div>

        <!-- New Leads Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">New Leads</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['new_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 font-medium">+8% from last week</span>
                <div class="flex items-center text-xs text-gray-500">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Trending up
                </div>
            </div>
        </div>

        <!-- Active Projects Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Active Projects</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['active_projects']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 font-medium">+5% from last month</span>
                <div class="flex items-center text-xs text-gray-500">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Trending up
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-semibold text-gray-900">₹{{ number_format($stats['total_revenue'], 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 font-medium">+18% from last month</span>
                <div class="flex items-center text-xs text-gray-500">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Trending up
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Leads by Source Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                <div class="mb-2 sm:mb-0">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Leads by Source</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Distribution of leads across different sources</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">This Month</span>
                </div>
            </div>
            <div class="h-64 sm:h-80 flex items-center justify-center">
                <canvas id="leadsBySourceChart" width="400" height="300"></canvas>
            </div>
        </div>

        <!-- Leads by Status Chart -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-corporate-900">Leads by Status</h3>
                <div class="w-10 h-10 bg-accent-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="h-80 flex items-center justify-center">
                <canvas id="leadsByStatusChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8 mt-6 sm:mt-8">
        <!-- Monthly Revenue Chart -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-corporate-900">Monthly Revenue</h3>
                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="monthlyRevenueChart" width="300" height="200"></canvas>
            </div>
        </div>

        <!-- Task Completion Chart -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-corporate-900">Task Completion</h3>
                <div class="w-8 h-8 bg-accent-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="taskCompletionChart" width="300" height="200"></canvas>
            </div>
        </div>

        <!-- Project Status Chart -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-corporate-900">Project Status</h3>
                <div class="w-8 h-8 bg-corporate-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-corporate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="projectStatusChart" width="300" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mt-6 sm:mt-8">
        <!-- Recent Leads -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-corporate-900">Recent Leads</h3>
                <a href="{{ route('leads.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentLeads as $lead)
                <div class="flex items-center justify-between p-4 bg-corporate-50 rounded-lg hover:bg-corporate-100 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ substr($lead->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-corporate-900">{{ $lead->name }}</p>
                            <p class="text-sm text-corporate-600">{{ $lead->phone }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $lead->status_badge }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                        <p class="text-xs text-corporate-500 mt-1">{{ $lead->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-corporate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-corporate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-corporate-500 font-medium">No recent leads</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Projects -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-corporate-900">Recent Projects</h3>
                <a href="{{ route('projects.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentProjects as $project)
                <div class="flex items-center justify-between p-4 bg-corporate-50 rounded-lg hover:bg-corporate-100 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-accent-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ substr($project->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-corporate-900">{{ Str::limit($project->name, 20) }}</p>
                            <p class="text-sm text-corporate-600">{{ $project->project_code ?? 'No Code' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $project->status === 'active' ? 'bg-green-100 text-green-800' : ($project->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($project->status) }}
                        </span>
                        <p class="text-xs text-corporate-500 mt-1">{{ $project->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-corporate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-corporate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <p class="text-corporate-500 font-medium">No recent projects</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Tasks -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-corporate-900">Recent Tasks</h3>
                <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentTasks as $task)
                <div class="flex items-center justify-between p-4 bg-corporate-50 rounded-lg hover:bg-corporate-100 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-corporate-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-corporate-900">{{ Str::limit($task->title, 20) }}</p>
                            <p class="text-sm text-corporate-600">{{ $task->project->name ?? 'No Project' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($task->status) }}
                        </span>
                        <p class="text-xs text-corporate-500 mt-1">{{ $task->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-corporate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-corporate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <p class="text-corporate-500 font-medium">No recent tasks</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Invoices -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-corporate-900">Recent Invoices</h3>
                <a href="{{ route('invoices.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentInvoices as $invoice)
                <div class="flex items-center justify-between p-4 bg-corporate-50 rounded-lg hover:bg-corporate-100 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-accent-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">₹</span>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-corporate-900">{{ $invoice->invoice_number }}</p>
                            <p class="text-sm text-corporate-600">₹{{ number_format($invoice->total_amount) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                        <p class="text-xs text-corporate-500 mt-1">{{ $invoice->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-corporate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-corporate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-corporate-500 font-medium">No recent invoices</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Purchase Orders -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-corporate-900">Recent Purchase Orders</h3>
                <a href="{{ route('purchase-orders.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentPurchaseOrders as $po)
                <div class="flex items-center justify-between p-4 bg-corporate-50 rounded-lg hover:bg-corporate-100 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">PO</span>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-corporate-900">{{ $po->po_number }}</p>
                            <p class="text-sm text-corporate-600">{{ $po->vendor->company ?? 'No Vendor' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $po->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($po->status) }}
                        </span>
                        <p class="text-xs text-corporate-500 mt-1">{{ $po->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-corporate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-corporate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <p class="text-corporate-500 font-medium">No recent purchase orders</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Vendors -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-corporate-900">Recent Vendors</h3>
                <a href="{{ route('vendors.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                    View All
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentVendors as $vendor)
                <div class="flex items-center justify-between p-4 bg-corporate-50 rounded-lg hover:bg-corporate-100 transition-colors">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ substr($vendor->company, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="font-semibold text-corporate-900">{{ $vendor->company }}</p>
                            <p class="text-sm text-corporate-600">{{ $vendor->contact_person }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $vendor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($vendor->status) }}
                        </span>
                        <p class="text-xs text-corporate-500 mt-1">{{ $vendor->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-corporate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-corporate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <p class="text-corporate-500 font-medium">No recent vendors</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Auto-hide welcome message after 4.5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const welcomeMessage = document.getElementById('welcome-message');
        if (welcomeMessage) {
            setTimeout(function() {
                welcomeMessage.style.opacity = '0';
                welcomeMessage.style.transform = 'translateY(-20px)';
                setTimeout(function() {
                    welcomeMessage.style.display = 'none';
                }, 500); // Wait for transition to complete
            }, 4500); // Hide after 4.5 seconds
        }
    });
</script>
<script>
    // Leads by Source Chart
    const leadsBySourceCtx = document.getElementById('leadsBySourceChart').getContext('2d');
    new Chart(leadsBySourceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($leadsBySource->pluck('source')) !!},
            datasets: [{
                data: {!! json_encode($leadsBySource->pluck('count')) !!},
                backgroundColor: [
                    '#0d9488',
                    '#059669',
                    '#dc2626',
                    '#ea580c',
                    '#7c3aed',
                    '#db2777',
                    '#0891b2'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Leads by Status Chart
    const leadsByStatusCtx = document.getElementById('leadsByStatusChart').getContext('2d');
    new Chart(leadsByStatusCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($leadsByStatus->pluck('status')) !!},
            datasets: [{
                label: 'Leads',
                data: {!! json_encode($leadsByStatus->pluck('count')) !!},
                backgroundColor: '#0d9488',
                borderColor: '#0f766e',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Monthly Revenue Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyRevenueCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Revenue (₹)',
                data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Task Completion Chart
    const taskCompletionCtx = document.getElementById('taskCompletionChart').getContext('2d');
    new Chart(taskCompletionCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($taskCompletion->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($taskCompletion->pluck('count')) !!},
                backgroundColor: [
                    '#ef4444',
                    '#f59e0b',
                    '#3b82f6',
                    '#10b981'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Project Status Chart
    const projectStatusCtx = document.getElementById('projectStatusChart').getContext('2d');
    new Chart(projectStatusCtx, {
        type: 'polarArea',
        data: {
            labels: {!! json_encode($projectsByStatus->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($projectsByStatus->pluck('count')) !!},
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#8b5cf6',
                    '#ef4444'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        }
    });
</script>
@endsection


