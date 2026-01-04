@extends('layouts.app')

@section('title', 'Service Engineer Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div id="welcome-header" class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg border-0 p-8 text-white transition-all duration-500 transform">
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-md">Service Active</span>
                    <span class="text-blue-100 text-sm opacity-80">{{ now()->format('l, M d, Y') }}</span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-50 text-lg font-medium opacity-90">Service Engineer - Maintenance & Support</p>
                <div class="flex items-center space-x-4 mt-4 pt-4 border-t border-white/10">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse mr-2"></div>
                        <span class="text-sm text-blue-100 italic">Department: {{ auth()->user()->department ?? 'Service' }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <div class="w-32 h-32 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20 shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-tools text-6xl text-white drop-shadow-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-plus text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">New Service</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-red-50 dark:bg-red-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Report Issue</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-file-contract text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">AMC Contract</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-wrench text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Maintenance</span>
        </a>
        <button onclick="window.print()" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-download text-gray-600 dark:text-gray-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Svc Report</span>
        </button>
    </div>

    <!-- Service Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Service Requests Card -->
        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tools text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Service Requests</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['service_requests']) }}</p>
                    </div>
                </div>
                <div class="text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ $stats['pending_services'] }} Pending</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    Awaiting Service
                </div>
            </div>
        </a>

        <!-- Completed Services Card -->
        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['completed_services']) }}</p>
                    </div>
                </div>
                <div class="text-green-600 dark:text-green-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 dark:text-green-400 font-medium">{{ $stats['resolved_complaints'] }} Resolved</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-check mr-1"></i>
                    Service Complete
                </div>
            </div>
        </a>

        <!-- Complaints Card -->
        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-50 dark:bg-red-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Complaints</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_complaints']) }}</p>
                    </div>
                </div>
                <div class="text-red-600 dark:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-red-600 dark:text-red-400 font-medium">{{ $stats['open_complaints'] }} Open</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-hourglass-half mr-1"></i>
                    Under Review
                </div>
            </div>
        </a>

        <!-- AMC Contracts Card -->
        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-file-contract text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">AMC Contracts</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['amc_contracts']) }}</p>
                    </div>
                </div>
                <div class="text-purple-600 dark:text-purple-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-purple-600 dark:text-purple-400 font-medium">{{ $stats['active_amc'] }} Active</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-calendar-check mr-1"></i>
                    Maintenance Due
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Service Requests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Service Requests</h3>
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentServiceRequests as $request)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-tools text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $request->title ?? 'Service Request #' . $request->id }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->complaint_number ?? 'SR-' . $request->id }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full mb-1">
                            {{ ucfirst($request->status ?? 'Pending') }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $request->created_at->format('M d') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No service requests found</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Complaints -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Complaints</h3>
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentComplaints as $complaint)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $complaint->title ?? 'Complaint #' . $complaint->id }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $complaint->complaint_number ?? 'COMP-' . $complaint->id }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 rounded-full mb-1">
                            {{ ucfirst($complaint->status ?? 'Open') }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $complaint->created_at->format('M d') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No complaints found</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection


