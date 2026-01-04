@extends('layouts.app')

@section('title', 'Purchase Manager Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div id="welcome-header" class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-xl shadow-lg border-0 p-8 text-white transition-all duration-500 transform">
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-md">Purchasing Active</span>
                    <span class="text-amber-100 text-sm opacity-80">{{ now()->format('l, M d, Y') }}</span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-amber-50 text-lg font-medium opacity-90">Purchase Manager - Procurement & Supply Chain</p>
                <div class="flex items-center space-x-4 mt-4 pt-4 border-t border-white/10">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse mr-2"></div>
                        <span class="text-sm text-amber-100 italic">Department: {{ auth()->user()->department ?? 'Procurement' }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <div class="w-32 h-32 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20 shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-shopping-cart text-6xl text-white drop-shadow-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <a href="{{ route('material-requests.create') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-plus text-amber-600 dark:text-amber-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">New Request</span>
        </a>
        <a href="{{ route('vendors.create') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-building text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Add Vendor</span>
        </a>
        <a href="{{ route('material-requests.index') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-list text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">View Requests</span>
        </a>
        <a href="{{ route('vendors.index') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-handshake text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">View Vendors</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-chart-bar text-yellow-600 dark:text-yellow-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Report</span>
        </a>
        <button onclick="window.print()" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-download text-gray-600 dark:text-gray-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Export Report</span>
        </button>
    </div>

    <!-- Purchase Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Material Requests Card -->
        <a href="{{ route('material-requests.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-clipboard-list text-amber-600 dark:text-amber-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Material Requests</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['material_requests']) }}</p>
                    </div>
                </div>
                <div class="text-amber-600 dark:text-amber-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-amber-600 dark:text-amber-400 font-medium">{{ $stats['pending_material_requests'] }} Pending</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    Awaiting Approval
                </div>
            </div>
        </a>

        <!-- Purchase Orders Card -->
        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-file-invoice text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Purchase Orders</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['purchase_orders'] ?? 0) }}</p>
                    </div>
                </div>
                <div class="text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">This Month</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-calendar mr-1"></i>
                    Recent Activity
                </div>
            </div>
        </a>

        <!-- Vendors Managed Card -->
        <a href="{{ route('vendors.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-handshake text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Vendors</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['vendors_managed'] ?? 0) }}</p>
                    </div>
                </div>
                <div class="text-green-600 dark:text-green-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 dark:text-green-400 font-medium">Active Partners</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-building mr-1"></i>
                    Supplier Network
                </div>
            </div>
        </a>

        <!-- Budget Utilization Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chart-pie text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Budget</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['budget_utilization'] ?? 0) }}%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-purple-600 dark:text-purple-400 font-medium">On Track</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-dollar-sign mr-1"></i>
                    Financial Control
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Cost Savings Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-piggy-bank text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Savings</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">₹2.4L</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">This Quarter</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-chart-line mr-1"></i>
                    Savings
                </div>
            </div>
        </div>

        <!-- Supplier Performance Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-star text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Supplier Rating</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">4.7/5</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Excellent</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-thumbs-up mr-1"></i>
                    Performance
                </div>
            </div>
        </div>

        <!-- Delivery Performance Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-truck text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">On-Time</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">96%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Excellent</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    Punctuality
                </div>
            </div>
        </div>

        <!-- Quality Score Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-50 dark:bg-pink-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shield-alt text-pink-600 dark:text-pink-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Quality Score</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">98%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-pink-600 dark:text-pink-400 font-medium">High Quality</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-check-circle mr-1"></i>
                    Standards
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Material Requests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Requests</h3>
                <a href="{{ route('material-requests.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($materialRequests as $request)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-amber-600 dark:text-amber-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $request->title ?? 'Request #' . $request->id }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->request_number ?? 'REQ-' . $request->id }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 rounded-full mb-1">
                            {{ ucfirst($request->status ?? 'Pending') }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $request->created_at->format('M d') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No material requests found</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Vendors -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Vendors</h3>
                <a href="{{ route('vendors.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentVendors as $vendor)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $vendor->company_name ?? 'Vendor #' . $vendor->id }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $vendor->contact_person ?? 'Contact Person' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full mb-1">
                            {{ ucfirst($vendor->status ?? 'Active') }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $vendor->created_at->format('M d') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No vendors found</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Procurement Guidelines & Resources -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Procurement Guidelines & Resources</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                <h4 class="font-medium text-amber-900 dark:text-amber-300 mb-2">Procurement Policies</h4>
                <p class="text-sm text-amber-700 dark:text-amber-400">Complete procurement policies, approval workflows, and vendor selection criteria for solar projects.</p>
            </div>
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h4 class="font-medium text-blue-900 dark:text-blue-300 mb-2">Supplier Database</h4>
                <p class="text-sm text-blue-700 dark:text-blue-400">Comprehensive database of approved suppliers, vendor performance metrics, and procurement history.</p>
            </div>
            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                <h4 class="font-medium text-purple-900 dark:text-purple-300 mb-2">Cost Optimization</h4>
                <p class="text-sm text-purple-700 dark:text-purple-400">Strategies for cost reduction, bulk purchasing opportunities, and negotiation best practices.</p>
            </div>
        </div>
    </div>
</div>
@endsection






