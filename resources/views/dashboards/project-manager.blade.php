@extends('layouts.app')

@section('title', 'Project Manager Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 text-white transition-all duration-1000 ease-in-out">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-orange-100 text-lg">Project Manager - Project Execution & Management</p>
                <p class="text-orange-200 text-sm mt-1">Department: {{ auth()->user()->department }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-project-diagram text-4xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('projects.create') }}" class="group flex flex-col items-center p-4 bg-orange-50 dark:bg-orange-900/30 rounded-xl hover:bg-orange-100 dark:hover:bg-orange-900/50 transition-all duration-300 border border-orange-100 dark:border-orange-800/50">
                <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-project-diagram text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-orange-800 dark:text-orange-200 text-center">New Project</span>
            </a>
            <a href="{{ route('tasks.create') }}" class="group flex flex-col items-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-all duration-300 border border-blue-100 dark:border-blue-800/50">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-tasks text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-blue-800 dark:text-blue-200 text-center">Create Task</span>
            </a>
            <a href="{{ route('material-requests.create') }}" class="group flex flex-col items-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-all duration-300 border border-purple-100 dark:border-purple-800/50">
                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-boxes text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-purple-800 dark:text-purple-200 text-center">Req Material</span>
            </a>
            <a href="{{ route('site-expenses.create') }}" class="group flex flex-col items-center p-4 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-all duration-300 border border-emerald-100 dark:border-emerald-800/50">
                <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-receipt text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-emerald-800 dark:text-emerald-200 text-center">Add Expense</span>
            </a>
            <a href="{{ route('advances.create') }}" class="group flex flex-col items-center p-4 bg-amber-50 dark:bg-amber-900/30 rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-all duration-300 border border-amber-100 dark:border-amber-800/50">
                <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-hand-holding-usd text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-amber-800 dark:text-amber-200 text-center">Req Advance</span>
            </a>
            <a href="{{ route('projects.index') }}" class="group flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-300 border border-gray-200 dark:border-gray-600">
                <div class="w-12 h-12 bg-gray-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-list text-white text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 text-center">All Projects</span>
            </a>
        </div>
    </div>

    <!-- Project Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- My Projects Card -->
        <a href="{{ route('projects.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-project-diagram text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">My Projects</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['my_projects']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-orange-600 dark:text-orange-400 font-medium">{{ $stats['active_projects'] }} Active</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-play-circle mr-1"></i>
                    In Progress
                </div>
            </div>
        </a>

        <!-- Completed Projects Card -->
        <a href="{{ route('projects.index', ['status' => 'completed']) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['completed_projects']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 dark:text-green-400 font-medium">{{ $stats['completed_projects'] > 0 ? round(($stats['completed_projects'] / $stats['my_projects']) * 100, 1) : 0 }}% Rate</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-trophy mr-1"></i>
                    Success Rate
                </div>
            </div>
        </a>

        <!-- Total Tasks Card -->
        <a href="{{ route('tasks.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tasks text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Tasks</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_tasks']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ $stats['pending_tasks'] }} Pending</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clock mr-1"></i>
                    Management
                </div>
            </div>
        </a>

        <!-- Material Requests Card -->
        <a href="{{ route('material-requests.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-boxes text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Materials</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['material_requests']) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-purple-600 dark:text-purple-400 font-medium">{{ $stats['pending_material_requests'] }} Pending</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-hourglass-half mr-1"></i>
                    Awaiting
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- My Projects -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Projects</h3>
                <a href="{{ route('projects.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[400px]">
                @forelse($myProjects as $project)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center group-hover:bg-orange-200 dark:group-hover:bg-orange-800 transition-colors">
                            <i class="fas fa-project-diagram text-orange-600 dark:text-orange-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $project->client->name ?? 'No Client' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-[10px] font-semibold bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200 rounded-full uppercase">
                        {{ $project->status }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-project-diagram text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">No projects assigned</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- My Tasks -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Tasks</h3>
                <a href="{{ route('tasks.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[400px]">
                @forelse($myTasks as $task)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors">
                            <i class="fas fa-tasks text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $task->project->name ?? 'No Project' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-[10px] font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full uppercase">
                        {{ $task->status }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-tasks text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">No tasks assigned</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Material Requests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Material Requests</h3>
                <a href="{{ route('material-requests.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[400px]">
                @forelse($materialRequests as $request)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition-colors">
                            <i class="fas fa-boxes text-purple-600 dark:text-purple-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $request->material_name ?? 'Material Request' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->project->name ?? 'No Project' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-[10px] font-semibold bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200 rounded-full uppercase">
                        {{ $request->status }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-boxes text-gray-300 dark:text-gray-600 text-4xl mb-2"></i>
                    <p class="text-gray-500 dark:text-gray-400">No requests</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
