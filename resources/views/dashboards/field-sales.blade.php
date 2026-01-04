@extends('layouts.app')

@section('title', 'Field Sales Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div id="welcome-section" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-all duration-1000 ease-in-out">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Field Sales Executive - On-Site Sales & Client Management</p>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Department: {{ auth()->user()->department }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-map-marker-alt text-4xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Field Sales Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Leads Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Leads</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ $stats['new_leads'] }} New</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-chart-line mr-1"></i>
                    Lead Pipeline
                </div>
            </div>
        </div>

        <!-- Qualified Leads Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Qualified Leads</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['qualified_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-orange-600 dark:text-orange-400 font-medium">{{ $stats['contacted_leads'] }} Contacted</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-phone mr-1"></i>
                    Field Activity
                </div>
            </div>
        </div>

        <!-- Converted Leads Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Converted</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['converted_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 dark:text-green-400 font-medium">{{ $stats['conversion_rate'] }}% Rate</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-trophy mr-1"></i>
                    Success Rate
                </div>
            </div>
        </div>

        <!-- Quotation Success Rate Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-invoice text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Quotation Success</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['quotation_success_rate'] }}%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-purple-600 dark:text-purple-400 font-medium">{{ $stats['successful_quotations'] }} Successful</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-chart-line mr-1"></i>
                    Success Rate
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Leads -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Leads</h3>
                <a href="{{ route('leads.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentLeads as $lead)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $lead->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $lead->phone }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $lead->status == 'converted' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : ($lead->status == 'qualified' ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $lead->created_at->format('M d') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-users text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">No leads available</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Quotations -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Quotations</h3>
                <a href="{{ route('quotations.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentQuotations as $quotation)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-invoice text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $quotation->quotation_number }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">₹{{ number_format($quotation->total_amount) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $quotation->status == 'approved' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : ($quotation->status == 'accepted' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200') }}">
                            {{ ucfirst($quotation->status) }}
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $quotation->created_at->format('M d') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-file-invoice text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">No quotations created yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Documents</h3>
                <a href="{{ route('documents.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentDocuments as $document)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-alt text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $document->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $document->file_type }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                            {{ ucfirst($document->status) }}
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $document->created_at->format('M d') }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-file-alt text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">No documents uploaded yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('leads.create') }}" class="flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                <i class="fas fa-plus text-blue-600 dark:text-blue-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-blue-800 dark:text-blue-200">Add Lead</span>
            </a>
            <a href="{{ route('quotations.create') }}" class="flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors">
                <i class="fas fa-file-invoice text-purple-600 dark:text-purple-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-purple-800 dark:text-purple-200">New Quotation</span>
            </a>
            <a href="{{ route('documents.create') }}" class="flex flex-col items-center p-4 bg-green-50 dark:bg-green-900/30 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/50 transition-colors">
                <i class="fas fa-upload text-green-600 dark:text-green-400 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-green-800 dark:text-green-200">Upload Document</span>
            </a>
            <a href="#" class="flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                <i class="fas fa-download text-gray-600 dark:text-gray-300 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-200">Export Data</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const welcomeSection = document.getElementById('welcome-section');
    
    if (welcomeSection) {
        // Hide welcome section after 4 seconds with fade out animation
        setTimeout(function() {
            welcomeSection.style.opacity = '0';
            welcomeSection.style.transform = 'translateY(-20px)';
            
            // Remove element from DOM after animation completes
            setTimeout(function() {
                welcomeSection.style.display = 'none';
            }, 1000); // Wait for transition to complete
        }, 4000); // 4 seconds delay
    }
});
</script>
@endsection
