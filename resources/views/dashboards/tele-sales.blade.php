@extends('layouts.app')

@section('title', 'Tele Sales Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div id="welcome-section" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-all duration-1000 ease-in-out">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Tele Sales Executive - Lead Management & Customer Outreach</p>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Department: {{ auth()->user()->department }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-cyan-50 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-phone-alt text-4xl text-cyan-600 dark:text-cyan-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('leads.create') }}" class="group flex flex-col items-center p-4 bg-cyan-50 dark:bg-cyan-900/30 rounded-xl hover:bg-cyan-100 dark:hover:bg-cyan-900/50 transition-all duration-300 border border-cyan-100 dark:border-cyan-800/50">
                <div class="w-12 h-12 bg-cyan-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-user-plus text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-cyan-800 dark:text-cyan-200">Add Lead</span>
            </a>
            <a href="{{ route('quotations.create') }}" class="group flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-all duration-300 border border-purple-100 dark:border-purple-800/50">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-file-invoice text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-purple-800 dark:text-purple-200">New Quotation</span>
            </a>
            <a href="{{ route('leads.index') }}" class="group flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-all duration-300 border border-blue-100 dark:border-blue-800/50">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-list text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-blue-800 dark:text-blue-200">View Leads</span>
            </a>
            <a href="#" class="group flex flex-col items-center p-4 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-all duration-300 border border-emerald-100 dark:border-emerald-800/50">
                <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-phone-volume text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">Call Log</span>
            </a>
            <a href="#" class="group flex flex-col items-center p-4 bg-amber-50 dark:bg-amber-900/30 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-all duration-300 border border-amber-100 dark:border-amber-800/50">
                <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-calendar-check text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-amber-800 dark:text-amber-200">Schedule</span>
            </a>
            <a href="{{ route('leads.index', ['export' => 'excel']) }}" class="group flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-300 border border-gray-200 dark:border-gray-600">
                <div class="w-12 h-12 bg-gray-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-file-export text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Export</span>
            </a>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Leads Card -->
        <a href="{{ route('leads.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-cyan-50 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-plus text-cyan-600 dark:text-cyan-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Leads</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-cyan-600 dark:text-cyan-400 font-medium">{{ $stats['new_leads'] }} New</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-chart-line mr-1"></i>
                    Lead Pipeline
                </div>
            </div>
        </a>

        <!-- Qualified Leads Card -->
        <a href="{{ route('leads.index', ['status' => 'qualified']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-check text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Qualified Leads</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['qualified_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ $stats['contacted_leads'] }} Contacted</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-phone mr-1"></i>
                    Contacted
                </div>
            </div>
        </a>

        <!-- Converted Leads Card -->
        <a href="{{ route('leads.index', ['status' => 'converted']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Converted Leads</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['converted_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 dark:text-green-400 font-medium">{{ $stats['conversion_rate'] }}% Rate</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-percentage mr-1"></i>
                    Conversion
                </div>
            </div>
        </a>

        <!-- Follow Up Leads Card -->
        <a href="{{ route('leads.index', ['status' => 'follow_up']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Follow Up Leads</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['follow_up_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Requires Action</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Action Item
                </div>
            </div>
        </a>
    </div>

    <!-- Performance Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- This Month Leads Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-day text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">This Month Leads</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['this_month_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">{{ $stats['this_month_conversions'] }} Converted</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-bullseye mr-1"></i>
                    Monthly Goal
                </div>
            </div>
        </div>

        <!-- Total Quotations Card -->
        <a href="{{ route('quotations.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-50 dark:bg-pink-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-file-alt text-pink-600 dark:text-pink-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Quotations</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_quotations']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-pink-600 dark:text-pink-400 font-medium">{{ $stats['pending_quotations'] }} Pending</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-hourglass-half mr-1"></i>
                    Response
                </div>
            </div>
        </a>

        <!-- Documents Card -->
        <a href="{{ route('documents.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-file-pdf text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Client Documents</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_documents']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">{{ $stats['recent_documents'] }} This Week</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-history mr-1"></i>
                    Recent
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- My Leads -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Leads</h3>
                <a href="{{ route('leads.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[400px]">
                @forelse($recentLeads as $lead)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/30 rounded-full flex items-center justify-center group-hover:bg-cyan-200 dark:group-hover:bg-cyan-800 transition-colors">
                            <i class="fas fa-user text-cyan-600 dark:text-cyan-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $lead->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $lead->phone ?? 'No Phone' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-[10px] font-semibold bg-cyan-100 dark:bg-cyan-900/30 text-cyan-800 dark:text-cyan-200 rounded-full mb-1 uppercase">
                            {{ $lead->status }}
                        </span>
                        <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ $lead->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-users text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">No leads assigned</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- My Quotations -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Quotations</h3>
                <a href="{{ route('quotations.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[400px]">
                @forelse($recentQuotations as $quotation)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition-colors">
                            <i class="fas fa-file-invoice text-purple-600 dark:text-purple-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $quotation->quotation_number ?? 'QT-'.$quotation->id }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">₹{{ number_format($quotation->total_amount ?? 0) }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-[10px] font-semibold bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200 rounded-full mb-1 uppercase">
                            {{ $quotation->status ?? 'Draft' }}
                        </span>
                        <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ $quotation->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-file-invoice text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">No quotations created</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Documents</h3>
                <a href="{{ route('documents.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[400px]">
                @forelse($recentDocuments as $document)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800 transition-colors">
                            <i class="fas fa-file-alt text-emerald-600 dark:text-emerald-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[120px]">{{ $document->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">{{ $document->file_type }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="px-2 py-1 text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-200 rounded-full mb-1 uppercase">
                            {{ $document->status ?? 'Active' }}
                        </span>
                        <span class="text-[10px] text-gray-500 dark:text-gray-400">{{ $document->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-file-alt text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">No documents uploaded</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Call Scripts & Resources -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Call Scripts & Resources</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/50">
                <h4 class="font-medium text-blue-900 dark:text-blue-300 mb-2">Opening Script</h4>
                <p class="text-sm text-blue-700 dark:text-blue-400/80 italic">"Hello, this is {{ auth()->user()->name }} from Solar ERP. I'm calling to discuss how we can help you with your solar energy needs..."</p>
            </div>
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800/50">
                <h4 class="font-medium text-emerald-900 dark:text-emerald-300 mb-2">Objection Handling</h4>
                <p class="text-sm text-emerald-700 dark:text-emerald-400/80">Common objections and professional responses to help convert leads effectively.</p>
            </div>
            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-100 dark:border-purple-800/50">
                <h4 class="font-medium text-purple-900 dark:text-purple-300 mb-2">Closing Techniques</h4>
                <p class="text-sm text-purple-700 dark:text-purple-400/80">Proven closing techniques to secure appointments and commitments.</p>
            </div>
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
