@extends('layouts.app')

@section('title', 'Project Manager Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Project Manager Dashboard</h1>
                        <p class="mt-2 text-gray-600">Welcome back, {{ Auth::user()->name }}! Manage your projects efficiently.</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('project-manager.projects.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>New Project
                        </a>
                        <a href="{{ route('project-manager.tasks.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-tasks mr-2"></i>New Task
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Projects Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-project-diagram text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Projects</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_projects'] }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 font-medium">{{ $stats['active_projects'] }} Active</span>
                    <span class="text-gray-300 mx-2">•</span>
                    <span class="text-gray-600">{{ $stats['completed_projects'] }} Completed</span>
                </div>
            </div>

            <!-- Tasks Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Tasks</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-yellow-600 font-medium">{{ $stats['pending_tasks'] }} Pending</span>
                    <span class="text-gray-300 mx-2">•</span>
                    <span class="text-red-600">{{ $stats['overdue_tasks'] }} Overdue</span>
                </div>
            </div>

            <!-- Material Requests Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-boxes text-orange-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Material Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_material_requests'] }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-yellow-600 font-medium">{{ $stats['pending_material_requests'] }} Pending</span>
                    <span class="text-gray-300 mx-2">•</span>
                    <span class="text-green-600">{{ $stats['approved_material_requests'] }} Approved</span>
                </div>
            </div>

            <!-- Budget Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-rupee-sign text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Budget</p>
                        <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['total_budget']) }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-red-600 font-medium">₹{{ number_format($stats['used_budget']) }} Used</span>
                    <span class="text-gray-300 mx-2">•</span>
                    <span class="text-green-600">₹{{ number_format($stats['total_budget'] - $stats['used_budget']) }} Remaining</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <a href="{{ route('project-manager.projects.create') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-project-diagram text-blue-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-blue-900">New Project</span>
                </a>
                <a href="{{ route('project-manager.tasks.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <i class="fas fa-tasks text-green-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-green-900">New Task</span>
                </a>
                <a href="{{ route('project-manager.material-requests.create') }}" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                    <i class="fas fa-boxes text-orange-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-orange-900">Material Request</span>
                </a>
                <a href="{{ route('project-manager.progress-reports.create') }}" class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                    <i class="fas fa-chart-line text-yellow-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-yellow-900">Progress Report</span>
                </a>
                <a href="{{ route('project-manager.resource-allocations.create') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <i class="fas fa-users text-purple-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-purple-900">Resource Allocation</span>
                </a>
                <a href="{{ route('project-manager.documents.create') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-file-alt text-gray-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-900">Upload Document</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Tasks -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Tasks</h3>
                        <a href="{{ route('project-manager.tasks.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentTasks->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentTasks as $task)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $task->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $task->project->name }}</p>
                                        <div class="flex items-center mt-1 space-x-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($task->status === 'completed') bg-green-100 text-green-800
                                                @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                                @elseif($task->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($task->status) }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($task->priority === 'critical') bg-red-100 text-red-800
                                                @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                                @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">{{ $task->due_date ? $task->due_date->format('M d') : 'No due date' }}</p>
                                        @if($task->assignedTo)
                                            <p class="text-xs text-gray-500">{{ $task->assignedTo->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-tasks text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No recent tasks found</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Upcoming Deadlines</h3>
                        <a href="{{ route('project-manager.tasks.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                    </div>
                </div>
                <div class="p-6">
                    @if($upcomingDeadlines->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingDeadlines as $task)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $task->title }}</h4>
                                        <p class="text-sm text-gray-600">{{ $task->project->name }}</p>
                                        <div class="flex items-center mt-1 space-x-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Due Soon
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-red-600">{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}</p>
                                        <p class="text-xs text-gray-500">{{ $task->due_date->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-check text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No upcoming deadlines</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Material Requests -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Material Requests</h3>
                    <a href="{{ route('project-manager.material-requests.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentMaterialRequests->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentMaterialRequests as $request)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $request->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $request->request_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->project->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($request->status === 'approved') bg-green-100 text-green-800
                                                @elseif($request->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                @if($request->priority === 'critical') bg-red-100 text-red-800
                                                @elseif($request->priority === 'high') bg-orange-100 text-orange-800
                                                @elseif($request->priority === 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst($request->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-boxes text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No recent material requests found</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Progress Reports -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Progress Reports</h3>
                    <a href="{{ route('project-manager.progress-reports.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentDPR->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentDPR as $dpr)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $dpr->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $dpr->project->name }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($dpr->description, 100) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">{{ $dpr->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">by {{ $dpr->createdBy->name }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-line text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No recent progress reports found</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Converted Leads -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-8">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Converted Leads</h3>
                    <a href="{{ route('leads.index', ['status' => 'converted']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
            </div>
            <div class="p-6">
                @if(isset($convertedLeads) && $convertedLeads->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimated Value</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($convertedLeads as $lead)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $lead->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $lead->company ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>{{ $lead->phone }}</div>
                                            @if($lead->email)
                                                <div class="text-xs text-gray-500">{{ $lead->email }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $lead->estimated_value ? '₹' . number_format($lead->estimated_value, 2) : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('project-manager.projects.create') }}?client_id={{ $lead->id }}" 
                                               class="text-blue-600 hover:text-blue-900">Create Project</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-user-check text-gray-300 text-4xl mb-4"></i>
                        <p class="text-gray-500">No converted leads available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
