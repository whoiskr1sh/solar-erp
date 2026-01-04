@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Resource Allocation</h1>
            <p class="text-gray-600">Manage project resource allocations and utilization</p>
        </div>
        <a href="{{ route('resource-allocations.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Allocation
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-1">Total Allocations</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                        </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-green-600 uppercase mb-1">In Use</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['in_use'] }}</p>
                        </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Overallocated</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['overallocated'] }}</p>
                        </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                            </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-red-600 uppercase mb-1">Critical</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['critical'] }}</p>
                        </div>
                <div class="ml-4">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.66 11.2C17.43 10.9 17.15 10.64 16.85 10.45C16.55 10.26 16.24 10.12 15.92 10.05C15.6 9.98 15.27 9.98 14.94 10.05C14.61 10.12 14.3 10.26 14 10.45C13.7 10.64 13.42 10.9 13.19 11.2L12 12.39L10.81 11.2C10.58 10.9 10.3 10.64 10 10.45C9.7 10.26 9.39 10.12 9.06 10.05C8.73 9.98 8.4 9.98 8.07 10.05C7.74 10.12 7.43 10.26 7.13 10.45C6.83 10.64 6.55 10.9 6.32 11.2C5.86 11.8 5.86 12.6 6.32 13.2L7.53 14.41L6.32 15.62C5.86 16.22 5.86 17.02 6.32 17.62C6.78 18.22 7.58 18.22 8.18 17.76L9.39 16.55L10.6 17.76C11.2 18.22 12 18.22 12.6 17.76L13.81 16.55L15.02 17.76C15.62 18.22 16.42 18.22 17.02 17.76C17.48 17.16 17.48 16.36 17.02 15.76L15.81 14.55L17.02 13.34C17.48 12.74 17.48 11.94 17.02 11.34L17.66 11.2Z"/>
                            </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-lg font-semibold text-teal-600">Filters</h6>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('resource-allocations.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="filterForm">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="search" value="{{ request('search') }}" placeholder="Search allocations...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="status">
                        <option value="all">All Status</option>
                        <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                        <option value="allocated" {{ request('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                        <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resource Type</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="resource_type">
                        <option value="all">All Types</option>
                        <option value="human" {{ request('resource_type') == 'human' ? 'selected' : '' }}>Human</option>
                        <option value="equipment" {{ request('resource_type') == 'equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="material" {{ request('resource_type') == 'material' ? 'selected' : '' }}>Material</option>
                        <option value="financial" {{ request('resource_type') == 'financial' ? 'selected' : '' }}>Financial</option>
                        <option value="other" {{ request('resource_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="priority">
                        <option value="all">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="project_id">
                        <option value="all">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Activity</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="activity_id">
                        <option value="all">All Activities</option>
                        @foreach($activities as $activity)
                            <option value="{{ $activity->id }}" {{ request('activity_id') == $activity->id ? 'selected' : '' }}>{{ $activity->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Allocated To</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="allocated_to">
                        <option value="all">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('allocated_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach

                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Special Filters</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input class="mr-2 rounded border-gray-300 text-teal-600 focus:ring-teal-500" type="checkbox" name="overallocated" value="true" {{ request('overallocated') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Overallocated</span>
                        </label>
                        <label class="flex items-center">
                            <input class="mr-2 rounded border-gray-300 text-teal-600 focus:ring-teal-500" type="checkbox" name="underutilized" value="true" {{ request('underutilized') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Underutilized</span>
                        </label>
                        <label class="flex items-center">
                            <input class="mr-2 rounded border-gray-300 text-teal-600 focus:ring-teal-500" type="checkbox" name="critical" value="true" {{ request('critical') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Critical</span>
                        </label>
                    </div>
                </div>
            </form>
            
            <!-- Filter Actions -->
            <div class="flex flex-wrap gap-3 mt-6">
                <button type="submit" form="filterForm" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md transition duration-300">Filter</button>
                <a href="{{ route('resource-allocations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-300">Clear</a>
                <button type="button" onclick="exportData()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-300">Export</button>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h6 class="text-lg font-semibold text-teal-600">Resource Allocations</h6>
        </div>
        <div class="overflow-hidden">
            <div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Code</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Title</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Type</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Priority</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Project</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Resource</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Allocated To</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Quantity</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Utilization</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Start Date</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">End Date</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allocations as $allocation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ Str::limit($allocation->allocation_code, 8) }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="text-xs text-gray-900">{{ Str::limit($allocation->title, 15) }}</div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">{{ Str::limit(ucfirst($allocation->resource_type), 8) }}</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $allocation->priority_badge ? 'bg-amber-100 text-amber-800' : 'bg-yellow-100 text-yellow-800' }}">{{ Str::limit(ucfirst($allocation->priority), 8) }}</span>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ Str::limit(ucfirst($allocation->status), 8) }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="text-xs text-gray-900">{{ Str::limit($allocation->project->name ?? 'N/A', 12) }}</div>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="text-xs text-gray-900">{{ Str::limit($allocation->resource_name, 12) }}</div>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="text-xs text-gray-900">{{ Str::limit($allocation->allocatedTo->name ?? 'Unassigned', 10) }}</div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                    {{ $allocation->allocated_quantity }} {{ Str::limit($allocation->quantity_unit, 3) }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-12 bg-gray-200 rounded-full h-1.5 mr-1">
                                            <div class="bg-teal-600 h-1.5 rounded-full" style="width: {{ $allocation->utilization_percentage }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-900">{{ $allocation->utilization_percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                    {{ $allocation->allocation_start_date ? $allocation->allocation_start_date->format('M d') : 'N/A' }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">
                                    {{ $allocation->allocation_end_date ? $allocation->allocation_end_date->format('M d') : 'N/A' }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs font-medium">
                                    <div class="flex space-x-1">
                                        <a href="{{ route('resource-allocations.show', $allocation) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="View">V</a>
                                        <a href="{{ route('resource-allocations.edit', $allocation) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs" title="Edit">E</a>
                                        @if($allocation->status === 'planned')
                                            <form method="POST" action="{{ route('resource-allocations.mark-allocated', $allocation) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" title="Mark Allocated">✓</button>
                                            </form>
                                        @elseif($allocation->status === 'allocated')
                                            <form method="POST" action="{{ route('resource-allocations.mark-in-use', $allocation) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="Mark In Use">▶</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('resource-allocations.destroy', $allocation) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this allocation?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Delete">D</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-xl font-medium text-gray-900 mb-2">No resource allocations found</p>
                                        <p class="text-gray-500">Create your first resource allocation to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Showing {{ $allocations->firstItem() ?? 0 }} to {{ $allocations->lastItem() ?? 0 }} of {{ $allocations->total() }} results
                </div>
                <div>
                    {{ $allocations->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportData() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value) {
            params.append(key, value);
        }
    }
    
    const url = '{{ route("resource-allocations.export") }}?' + params.toString();
    window.open(url, '_blank');
}
</script>

@endsection


