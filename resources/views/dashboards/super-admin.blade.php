@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg shadow-sm border border-gray-200 p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-purple-100 text-lg">Super Administrator - Complete System Access</p>
                <p class="text-purple-200 text-sm mt-1">Last Login: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('M d, Y H:i') : 'First Login' }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-crown text-4xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 font-medium">All Roles</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-user-shield mr-1"></i>
                    System Users
                </div>
            </div>
        </div>

        <!-- Total Leads Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Leads</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_leads']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 font-medium">All Sources</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-chart-line mr-1"></i>
                    Lead Pipeline
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

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pending Tasks Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Pending Tasks</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['pending_tasks']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-yellow-600 font-medium">Requires Attention</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Action Required
                </div>
            </div>
        </div>

        <!-- Total Vendors Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-truck text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Vendors</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_vendors']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-indigo-600 font-medium">Supply Chain</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-handshake mr-1"></i>
                    Partnerships
                </div>
            </div>
        </div>

        <!-- Total Employees Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-id-badge text-pink-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Employees</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_employees']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-pink-600 font-medium">Team Members</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-users-cog mr-1"></i>
                    Workforce
                </div>
            </div>
        </div>

        <!-- System Health Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heartbeat text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">System Health</p>
                        <p class="text-2xl font-semibold text-gray-900">98%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 font-medium">Optimal</span>
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-check-circle mr-1"></i>
                    All Systems Go
                </div>
            </div>
        </div>
    </div>

    <!-- Sub-Coordinator Call Statistics -->
    @if(isset($subCoordinatorCallStats) && count($subCoordinatorCallStats) > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Sub-Coordinator Call Statistics</h2>
            <span class="text-sm text-gray-500">Call Performance Tracking</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub-Coordinator</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Leads</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Calls</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Today's Calls</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Calls/Lead</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($subCoordinatorCallStats as $stat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $stat['name'] }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $stat['role'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ number_format($stat['total_leads']) }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ number_format($stat['total_calls']) }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm font-semibold text-green-600">{{ number_format($stat['today_calls']) }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $stat['total_leads'] > 0 ? number_format($stat['total_calls'] / $stat['total_leads'], 2) : '0.00' }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

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

        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-purple-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->designation ?? 'No Designation' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                        {{ $user->roles->first()->name ?? 'No Role' }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-sm text-center py-4">No recent users</p>
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
            <a href="{{ route('projects.create') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <i class="fas fa-project-diagram text-orange-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-orange-800">New Project</span>
            </a>
            <a href="{{ route('users.create') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-user-plus text-purple-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-purple-800">Add User</span>
            </a>
            <a href="{{ route('analytics.dashboard') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-chart-bar text-green-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-green-800">Analytics</span>
            </a>
            <a href="{{ route('settings.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-cog text-gray-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-gray-800">Settings</span>
            </a>
            <a href="#" class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                <i class="fas fa-download text-red-600 text-2xl mb-2"></i>
                <span class="text-sm font-medium text-red-800">Export Data</span>
            </a>
        </div>
    </div>
</div>
@endsection


