@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-corporate sm:rounded-lg mb-6 border border-corporate-200">
            <div class="p-6 bg-gradient-to-r from-primary-50 to-accent-50 border-b border-corporate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-corporate-900">Projects Management</h2>
                        <p class="text-corporate-600 mt-1">Manage your solar installation projects efficiently</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('projects.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-corporate hover:shadow-corporate-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add New Project
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6 hover:shadow-corporate-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-corporate-600">Total Projects</p>
                        <p class="text-2xl font-bold text-corporate-900">{{ number_format($stats['total']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6 hover:shadow-corporate-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-accent-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-corporate-600">Active Projects</p>
                        <p class="text-2xl font-bold text-corporate-900">{{ number_format($stats['active']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6 hover:shadow-corporate-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-corporate-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-corporate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-corporate-600">In Progress</p>
                        <p class="text-2xl font-bold text-corporate-900">{{ number_format($stats['in_progress']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6 hover:shadow-corporate-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-corporate-600">Total Value</p>
                        <p class="text-2xl font-bold text-corporate-900">₹{{ number_format($stats['total_value']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-corporate border border-corporate-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-corporate-900">Filters & Search</h3>
            </div>
            
            <!-- Quick Filters -->
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('projects.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ !request()->hasAny(['status', 'priority', 'search']) ? 'bg-primary-100 text-primary-800 border border-primary-200' : 'bg-corporate-100 text-corporate-700 hover:bg-corporate-200 border border-corporate-200' }}">
                    All Projects
                </a>
                <a href="{{ route('projects.index', ['status' => 'active']) }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') == 'active' ? 'bg-accent-100 text-accent-800 border border-accent-200' : 'bg-corporate-100 text-corporate-700 hover:bg-corporate-200 border border-corporate-200' }}">
                    Active
                </a>
                <a href="{{ route('projects.index', ['status' => 'in_progress']) }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') == 'in_progress' ? 'bg-corporate-100 text-corporate-800 border border-corporate-200' : 'bg-corporate-100 text-corporate-700 hover:bg-corporate-200 border border-corporate-200' }}">
                    In Progress
                </a>
                <a href="{{ route('projects.index', ['status' => 'completed']) }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') == 'completed' ? 'bg-accent-100 text-accent-800 border border-accent-200' : 'bg-corporate-100 text-corporate-700 hover:bg-corporate-200 border border-corporate-200' }}">
                    Completed
                </a>
                <a href="{{ route('projects.index', ['status' => 'on_hold']) }}" class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') == 'on_hold' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-corporate-100 text-corporate-700 hover:bg-corporate-200 border border-corporate-200' }}">
                    On Hold
                </a>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-4">
                <div class="flex space-x-3">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by project name, code, or client..." class="block w-full pl-10 pr-3 py-3 border border-corporate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white placeholder-corporate-400">
                    </div>
                    @if(request()->hasAny(['status', 'priority', 'search']))
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-3 border border-corporate-300 rounded-lg shadow-corporate text-sm font-medium text-corporate-700 bg-white hover:bg-corporate-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear All Filters
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Advanced Filters -->
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <div>
                    <label class="block text-sm font-semibold text-corporate-700 mb-3">Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-corporate-300 rounded-lg shadow-corporate focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <option value="">All Status</option>
                        <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>Planning</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-corporate-700 mb-3">Priority</label>
                    <select name="priority" class="w-full px-4 py-3 border border-corporate-300 rounded-lg shadow-corporate focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <option value="">All Priority</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-corporate-700 mb-3">Client</label>
                    <select name="client_id" class="w-full px-4 py-3 border border-corporate-300 rounded-lg shadow-corporate focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <option value="">All Clients</option>
                        @if(isset($clients))
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                
                <div class="md:col-span-3 flex justify-end space-x-4">
                    <a href="{{ route('projects.index') }}" class="px-6 py-3 border border-corporate-300 rounded-lg shadow-corporate text-sm font-semibold text-corporate-700 bg-white hover:bg-corporate-50 transition-colors">
                        Clear All
                    </a>
                    <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition-colors shadow-corporate">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Projects Table -->
        <div class="bg-white overflow-hidden shadow-corporate sm:rounded-lg border border-corporate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gradient-to-r from-corporate-50 to-primary-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-corporate-700 uppercase tracking-wider w-32">Project Details</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-corporate-700 uppercase tracking-wider w-40">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-corporate-700 uppercase tracking-wider w-24">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-corporate-700 uppercase tracking-wider w-24">Priority</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-corporate-700 uppercase tracking-wider w-28">Value</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-corporate-700 uppercase tracking-wider w-28">Progress</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-corporate-700 uppercase tracking-wider w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-corporate-200">
                        @forelse($projects as $project)
                        <tr class="hover:bg-corporate-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-corporate-900">{{ $project->name }}</div>
                                <div class="text-xs text-corporate-600">{{ $project->project_code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-corporate-900">{{ Str::limit($project->client_name ?? 'N/A', 20) }}</div>
                                <div class="text-xs text-corporate-600">{{ Str::limit($project->location ?? 'N/A', 15) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $project->status_badge ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ Str::limit(ucfirst(str_replace('_', ' ', $project->status)), 8) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $project->priority_badge ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ Str::limit(ucfirst($project->priority ?? 'N/A'), 6) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-900">₹{{ number_format($project->budget ?? 0, 0) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-gray-900">{{ $project->progress ?? 0 }}%</div>
                                <div class="w-full bg-gray-200 rounded-full h-1">
                                    <div class="bg-blue-600 h-1 rounded-full" style="width: {{ $project->progress ?? 0 }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-primary-700 bg-primary-100 rounded-lg hover:bg-primary-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-corporate-700 bg-corporate-100 rounded-lg hover:bg-corporate-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('projects.destroy', $project) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No Projects found</p>
                                    <p class="text-sm">Get started by creating your first project.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($projects->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $projects->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection