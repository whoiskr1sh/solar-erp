@extends('layouts.app')

@section('title', 'Quality Engineer Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div id="welcome-header" class="bg-gradient-to-r from-emerald-600 to-green-600 rounded-xl shadow-lg border-0 p-8 text-white transition-all duration-500 transform">
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-md">Quality Active</span>
                    <span class="text-emerald-100 text-sm opacity-80">{{ now()->format('l, M d, Y') }}</span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-emerald-50 text-lg font-medium opacity-90">Quality Engineer - Quality Control & Assurance</p>
                <div class="flex items-center space-x-4 mt-4 pt-4 border-t border-white/10">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse mr-2"></div>
                        <span class="text-sm text-emerald-100 italic">Department: {{ auth()->user()->department ?? 'Quality Control' }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <div class="w-32 h-32 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20 shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-clipboard-check text-6xl text-white drop-shadow-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <a href="{{ route('inventory.inward-quality-check') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-clipboard-check text-emerald-600 dark:text-emerald-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Quality Check</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-boxes text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Inv. Audit</span>
        </a>
        <a href="{{ route('inventory.inward-quality-check') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-list text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">View Checks</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-chart-line text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Report</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Defects</span>
        </a>
        <button onclick="window.print()" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-download text-gray-600 dark:text-gray-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">QA Report</span>
        </button>
    </div>

    <!-- Quality Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Quality Checks Card -->
        <a href="{{ route('inventory.inward-quality-check') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-clipboard-check text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Quality Checks</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['quality_checks']) }}</p>
                    </div>
                </div>
                <div class="text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">{{ $stats['pending_checks'] }} Pending</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    Awaiting Review
                </div>
            </div>
        </a>

        <!-- Approved Checks Card -->
        <a href="{{ route('inventory.inward-quality-check', ['status' => 'approved']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Approved</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['approved_checks']) }}</p>
                    </div>
                </div>
                <div class="text-green-600 dark:text-green-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 dark:text-green-400 font-medium">{{ $stats['approved_checks'] > 0 ? round(($stats['approved_checks'] / $stats['quality_checks']) * 100, 1) : 0 }}% Rate</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-percentage mr-1"></i>
                    Approval Rate
                </div>
            </div>
        </a>

        <!-- Rejected Checks Card -->
        <a href="{{ route('inventory.inward-quality-check', ['status' => 'rejected']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-50 dark:bg-red-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Rejected</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['rejected_checks']) }}</p>
                    </div>
                </div>
                <div class="text-red-600 dark:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-red-600 dark:text-red-400 font-medium">Requires Action</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Quality Issues
                </div>
            </div>
        </a>

        <!-- Inventory Audits Card -->
        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-boxes text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Audits</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['inventory_audits']) }}</p>
                    </div>
                </div>
                <div class="text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">Completed</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clipboard-list mr-1"></i>
                    Audit Reports
                </div>
            </div>
        </a>
    </div>

    <!-- Performance Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Quality Score Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-star text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Quality Score</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">4.9/5</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Excellent</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-award mr-1"></i>
                    Quality Rating
                </div>
            </div>
        </div>

        <!-- Defect Rate Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-bug text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Defect Rate</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">0.8%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-purple-600 dark:text-purple-400 font-medium">Very Low</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-chart-line mr-1"></i>
                    Quality Control
                </div>
            </div>
        </div>

        <!-- Compliance Rate Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-alt text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Compliance</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">99.2%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Fully Compliant</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-check-circle mr-1"></i>
                    Standards
                </div>
            </div>
        </div>

        <!-- Efficiency Score Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-50 dark:bg-pink-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tachometer-alt text-pink-600 dark:text-pink-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Efficiency</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">96%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-pink-600 dark:text-pink-400 font-medium">High Perf</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-rocket mr-1"></i>
                    Efficiency
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Quality Checks -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Checks</h3>
                <a href="{{ route('inventory.inward-quality-check') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentQualityChecks as $check)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-clipboard-check text-emerald-600 dark:text-emerald-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $check->item_name ?? 'Check #' . $check->id }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $check->qc_number ?? 'QC-' . $check->id }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 rounded-full mb-1">
                            {{ ucfirst($check->status ?? 'Pending') }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $check->created_at->format('M d') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No quality checks performed by you</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Audits -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Audits</h3>
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentAudits as $audit)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-boxes text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $audit->warehouse_name ?? 'Audit #' . $audit->id }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $audit->audit_id ?? 'AUDIT-' . $audit->id }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full mb-1">
                            {{ ucfirst($audit->status ?? 'Pending') }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $audit->created_at->format('M d') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No audits performed by you</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quality Standards & Guidelines -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quality Standards & Guidelines</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                <h4 class="font-medium text-emerald-900 dark:text-emerald-300 mb-2">Quality Standards</h4>
                <p class="text-sm text-emerald-700 dark:text-emerald-400">Comprehensive quality control procedures, inspection checklists, and standards for solar installations.</p>
            </div>
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h4 class="font-medium text-blue-900 dark:text-blue-300 mb-2">Testing Protocols</h4>
                <p class="text-sm text-blue-700 dark:text-blue-400">Detailed testing procedures, safety protocols, and performance validation methods for all components.</p>
            </div>
            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                <h4 class="font-medium text-purple-900 dark:text-purple-300 mb-2">Compliance Requirements</h4>
                <p class="text-sm text-purple-700 dark:text-purple-400">Regulatory compliance guidelines, certification requirements, and industry best practices.</p>
            </div>
        </div>
    </div>
</div>
@endsection






