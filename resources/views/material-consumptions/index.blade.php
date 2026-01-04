@extends('layouts.app')

@section('title', 'Material Consumption')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Material Consumption</h1>
            <p class="text-gray-600">Track and manage material consumption across projects</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('material-consumptions.dashboard') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Dashboard
            </a>
            <a href="{{ route('material-consumptions.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Record Consumption
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <!-- Total Consumptions -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase mb-1">Total</p>
                <p class="text-lg font-bold text-gray-800">{{ number_format($stats['total_consumptions']) }}</p>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-xs font-bold text-green-600 uppercase mb-1">Completed</p>
                <p class="text-lg font-bold text-gray-800">{{ number_format($stats['completed_consumptions']) }}</p>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
            <div>
                <p class="text-xs font-bold text-yellow-600 uppercase mb-1">In Progress</p>
                <p class="text-lg font-bold text-gray-800">{{ number_format($stats['in_progress_consumptions']) }}</p>
            </div>
        </div>

        <!-- High Wastage -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-4">
            <div>
                <p class="text-xs font-bold text-red-600 uppercase mb-1">High Wastage</p>
                <p class="text-lg font-bold text-gray-800">{{ number_format($stats['high_wastage_consumptions']) }}</p>
            </div>
        </div>

        <!-- Total Cost -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div>
                <p class="text-xs font-bold text-purple-600 uppercase mb-1">Total Cost</p>
                <p class="text-lg font-bold text-gray-800">₹{{ number_format($stats['total_cost'], 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ route('material-consumptions.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Consumption #, Material, Description..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="damaged" {{ request('status') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                </select>
            </div>

            <!-- Quality Status -->
            <div>
                <label for="quality_status" class="block text-sm font-medium text-gray-700 mb-1">Quality</label>
                <select id="quality_status" name="quality_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                    <option value="">All Quality</option>
                    <option value="good" {{ request('quality_status') === 'good' ? 'selected' : '' }}>Good</option>
                    <option value="damaged" {{ request('quality_status') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                    <option value="defective" {{ request('quality_status') === 'defective' ? 'selected' : '' }}>Defective</option>
                    <option value="expired" {{ request('quality_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <!-- Work Phase -->
            <div>
                <label for="work_phase" class="block text-sm font-medium text-gray-700 mb-1">Work Phase</label>
                <select id="work_phase" name="work_phase" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                    <option value="">All Phases</option>
                    <option value="preparation" {{ request('work_phase') === 'preparation' ? 'selected' : '' }}>Preparation</option>
                    <option value="foundation" {{ request('work_phase') === 'foundation' ? 'selected' : '' }}>Foundation</option>
                    <option value="structure" {{ request('work_phase') === 'structure' ? 'selected' : '' }}>Structure</option>
                    <option value="electrical" {{ request('work_phase') === 'electrical' ? 'selected' : '' }}>Electrical</option>
                    <option value="commissioning" {{ request('work_phase') === 'commissioning' ? 'selected' : '' }}>Commissioning</option>
                    <option value="maintenance" {{ request('work_phase') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="other" {{ request('work_phase') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Project -->
            <div>
                <label for="project" class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                <select id="project" name="project" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                            {{ Str::limit($project->project_name, 25) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Consumer -->
            <div>
                <label for="consumed_by" class="block text-sm font-medium text-gray-700 mb-1">Consumed By</label>
                <select id="consumed_by" name="consumed_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('consumed_by') == $user->id ? 'selected' : '' }}>
                            {{ Str::limit($user->name, 20) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300 text-sm">
                    Filter
                </button>
                <a href="{{ route('material-consumptions.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300 text-sm">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="min-w-full divide-y divide-gray-200">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumption</th>
                        <th class="w-24 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                        <th class="w-20 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="w-12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efficiency</th>
                        <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quality</th>
                        <th class="w-12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                        <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumer</th>
                        <th class="w-12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($consumptions as $consumption)
                    <tr class="hover:bg-gray-50">
                        <!-- Consumption Number -->
                        <td class="px-3 py-3">
                            <div class="text-sm font-medium text-gray-900">
                                {{ Str::limit($consumption->consumption_number, 10) }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $consumption->consumption_date ? $consumption->consumption_date->format('M d') : 'N/A' }}
                            </div>
                        </td>

                        <!-- Material -->
                        <td class="px-3 py-3">
                            <div class="text-sm font-medium text-gray-900">
                                {{ Str::limit($consumption->material->item_name, 12) }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $consumption->work_phase }}
                            </div>
                        </td>

                        <!-- Project -->
                        <td class="px-3 py-3">
                            <div class="text-sm text-gray-900">
                                {{ $consumption->project ? Str::limit($consumption->project->project_name, 12) : '-' }}
                            </div>
                        </td>

                        <!-- Quantity -->
                        <td class="px-3 py-3">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $consumption->quantity_consumed }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $consumption->unit_of_measurement ?? 'units' }}
                            </div>
                        </td>

                        <!-- Efficiency -->
                        <td class="px-3 py-3">
                            @if($consumption->consumption_percentage > 80)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $consumption->consumption_percentage }}%
                                </span>
                            @elseif($consumption->consumption_percentage > 60)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ $consumption->consumption_percentage }}%
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $consumption->consumption_percentage }}%
                                </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-3 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $consumption->consumption_status_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $consumption->consumption_status)) }}
                            </span>
                        </td>

                        <!-- Quality -->
                        <td class="px-3 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $consumption->quality_status_badge }}">
                                {{ ucfirst($consumption->quality_status) }}
                            </span>
                        </td>

                        <!-- Cost -->
                        <td class="px-3 py-3">
                            <div class="text-sm font-medium text-gray-900">
                                ₹{{ number_format($consumption->total_cost, 0) }}
                            </div>
                            @if($consumption->wastage_cost > 0)
                                <div class="text-xs text-red-500">
                                    Waste: ₹{{ number_format($consumption->wastage_cost, 0) }}
                                </div>
                            @endif
                        </td>

                        <!-- Consumer -->
                        <td class="px-3 py-3">
                            <div class="text-sm text-gray-900">
                                {{ Str::limit($consumption->consumedBy->name, 12) }}
                            </div>
                        </td>

                        <!-- Date -->
                        <td class="px-3 py-3">
                            <div class="text-sm text-gray-900">{{ $consumption->consumption_date ? $consumption->consumption_date->format('M d') : 'N/A' }}</div>
                        </td>

                        <!-- Actions -->
                        <td class="px-3 py-3">
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('material-consumptions.show', $consumption) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">V</a>
                                
                                @if($consumption->consumption_status === 'draft' || $consumption->consumption_status === 'in_progress')
                                    <a href="{{ route('material-consumptions.edit', $consumption) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">E</a>
                                @endif
                                
                                @if($consumption->consumption_status !== 'completed')
                                    <form method="POST" action="{{ route('material-consumptions.approve', $consumption) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs">A</button>
                                    </form>
                                @endif
                                
                                <form method="POST" action="{{ route('material-consumptions.destroy', $consumption) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">D</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-400 mb-2">No consumption records found</p>
                                <p class="text-sm text-gray-400 mb-4">Start tracking material consumption for better project management</p>
                                <a href="{{ route('material-consumptions.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                    Record First Consumption
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($consumptions->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $consumptions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
