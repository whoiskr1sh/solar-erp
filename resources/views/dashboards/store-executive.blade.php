@extends('layouts.app')

@section('title', 'Store Executive Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div id="welcome-header" class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg border-0 p-8 text-white transition-all duration-500 transform">
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-md">Inventory Active</span>
                    <span class="text-blue-100 text-sm opacity-80">{{ now()->format('l, M d, Y') }}</span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-50 text-lg font-medium opacity-90">Store Executive - Warehouse Operations</p>
                <div class="flex items-center space-x-4 mt-4 pt-4 border-t border-white/10">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse mr-2"></div>
                        <span class="text-sm text-blue-100 italic">Department: {{ auth()->user()->department ?? 'Store' }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <div class="w-32 h-32 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20 shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-warehouse text-6xl text-white drop-shadow-lg"></i>
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
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Add Product</span>
        </a>
        <a href="{{ route('material-requests.index') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-clipboard-list text-amber-600 dark:text-amber-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Requests</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-receipt text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Create GRN</span>
        </a>
        <a href="#" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-warehouse text-purple-600 dark:text-purple-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Inventory Report</span>
        </a>
        <button onclick="window.print()" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-download text-gray-600 dark:text-gray-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Export Report</span>
        </button>
    </div>

    <!-- Inventory Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products Card -->
        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-boxes text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Products</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_products']) }}</p>
                    </div>
                </div>
                <div class="text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ $stats['low_stock_products'] }} Low Stock</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Need Attention
                </div>
            </div>
        </a>

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
                <span class="text-sm text-amber-600 dark:text-amber-400 font-medium">{{ $stats['pending_requests'] }} Pending</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    Awaiting Approval
                </div>
            </div>
        </a>

        <!-- Approved Requests Card -->
        <a href="{{ route('material-requests.index', ['status' => 'approved']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Approved</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['approved_requests']) }}</p>
                    </div>
                </div>
                <div class="text-green-600 dark:text-green-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 dark:text-green-400 font-medium">Ready for Processing</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-truck mr-1"></i>
                    In Transit
                </div>
            </div>
        </a>

        <!-- GRN Received Card -->
        <a href="#" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-receipt text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">GRN Received</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['grn_received']) }}</p>
                    </div>
                </div>
                <div class="text-purple-600 dark:text-purple-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-purple-600 dark:text-purple-400 font-medium">{{ $stats['pending_grn'] }} Pending</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-hourglass-half mr-1"></i>
                    Processing
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Low Stock Products -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Low Stock Products</h3>
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($lowStockProducts as $product)
                <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name ?? 'Product #' . $product->id }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $product->sku ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 rounded-full mb-1">
                            {{ $product->current_stock ?? 0 }} Units
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Min: {{ $product->min_stock_level ?? 0 }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No low stock products</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Material Requests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Requests</h3>
                <a href="{{ route('material-requests.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentMaterialRequests as $request)
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
    </div>

</div>
@endsection


