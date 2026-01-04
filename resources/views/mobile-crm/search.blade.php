@extends('layouts.app')

@section('content')
<div class="p-4">
    <!-- Mobile Header -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('mobile-crm.index') }}" class="mr-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Search</h1>
                    <p class="text-sm text-gray-600">Find leads, projects, and tasks</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('mobile-crm.search') }}" class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="relative">
            <input type="text" name="q" value="{{ $query }}" placeholder="Search leads, projects, tasks..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <button type="submit" class="absolute right-2 top-2 bg-teal-600 hover:bg-teal-700 text-white px-4 py-1.5 rounded-lg text-sm transition-colors duration-200">
                Search
            </button>
        </div>
    </form>

    @if($query)
    <!-- Search Results -->
    <div class="space-y-6">
        <!-- Leads Results -->
        @if($results['leads']->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Leads ({{ $results['leads']->count() }})
            </h3>
            <div class="space-y-3">
                @foreach($results['leads'] as $lead)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $lead->name }}</p>
                            <p class="text-xs text-gray-600">{{ $lead->company ?: 'No company' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('leads.show', $lead) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 rounded text-sm transition-colors duration-200">
                        View
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Projects Results -->
        @if($results['projects']->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Projects ({{ $results['projects']->count() }})
            </h3>
            <div class="space-y-3">
                @foreach($results['projects'] as $project)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-2 rounded-lg">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $project->name }}</p>
                            <p class="text-xs text-gray-600">{{ $project->client->name ?? 'No client' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('projects.show', $project) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 rounded text-sm transition-colors duration-200">
                        View
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Tasks Results -->
        @if($results['tasks']->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Tasks ({{ $results['tasks']->count() }})
            </h3>
            <div class="space-y-3">
                @foreach($results['tasks'] as $task)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $task->title }}</p>
                            <p class="text-xs text-gray-600">{{ $task->project->name ?? 'No project' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('tasks.show', $task) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 rounded text-sm transition-colors duration-200">
                        View
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- No Results -->
        @if($results['leads']->count() == 0 && $results['projects']->count() == 0 && $results['tasks']->count() == 0)
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No results found</h3>
            <p class="text-gray-600">Try searching with different keywords.</p>
        </div>
        @endif
    </div>
    @else
    <!-- Search Tips -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Search Tips</h3>
        <div class="space-y-3">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-teal-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-900">Search by name</p>
                    <p class="text-sm text-gray-600">Enter lead, project, or task names</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-teal-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-900">Search by company</p>
                    <p class="text-sm text-gray-600">Find leads by company name</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-teal-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-900">Search by description</p>
                    <p class="text-sm text-gray-600">Find tasks by description content</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
