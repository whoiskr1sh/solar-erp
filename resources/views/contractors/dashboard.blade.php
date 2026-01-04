@extends('layouts.app')

@section('title', 'Contractors Dashboard')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contractors Dashboard</h1>
            <p class="text-gray-600">Overview of contractors and service providers</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('contractors.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Contractors
            </a>
            <a href="{{ route('contractors.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Contractor
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Contractors -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div>
                <p class="text-sm font-bold text-blue-600 uppercase mb-1">Total Contractors</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['total_contractors']) }}</p>
            </div>
        </div>

        <!-- Active Contractors -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-sm font-bold text-green-600 uppercase mb-1">Active Contractors</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['active_contractors']) }}</p>
            </div>
        </div>

        <!-- Verified Contractors -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div>
                <p class="text-sm font-bold text-purple-600 uppercase mb-1">Verified Contractors</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['verified_contractors']) }}</p>
            </div>
        </div>

        <!-- Available Contractors -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
            <div class="ml-4">
                <svg class="w-8 h-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-yellow-600 uppercase mb-1">Available</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['available_contractors']) }}</p>
            </div>
        </div>
    </div>

    <!-- Performance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Companies -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div>
                <p class="text-sm font-bold text-blue-600 uppercase mb-1">Companies</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['companies']) }}</p>
            </div>
        </div>

        <!-- Individuals -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-sm font-bold text-green-600 uppercase mb-1">Individuals</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['individuals']) }}</p>
            </div>
        </div>

        <!-- Subcontractors -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div>
                <p class="text-sm font-bold text-purple-600 uppercase mb-1">Subcontractors</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['subcontractors']) }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Average Rating -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
            <div>
                <p class="text-sm font-bold text-yellow-600 uppercase mb-1">Avg Rating</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['avg_rating'], 1) }}/5</p>
            </div>
        </div>

        <!-- Average Experience -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-indigo-500 p-4">
            <div>
                <p class="text-sm font-bold text-indigo-600 uppercase mb-1">Avg Experience</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['avg_experience'], 1) }} years</p>
            </div>
        </div>

        <!-- Total Projects Value -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-sm font-bold text-green-600 uppercase mb-1">Total Value</p>
                <p class="text-xl font-bold text-gray-800">₹{{ number_format($summary['total_projects_value'], 0) }}</p>
            </div>
        </div>

        <!-- This Month Added -->
        <div class="bg-white rounded-lg shadow-md border-l-4 border-cyan-500 p-4">
            <div>
                <p class="text-sm font-bold text-cyan-600 uppercase mb-1">This Month</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($summary['this_month_added']) }}</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Type Distribution Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Contractor Types Distribution</h3>
            <div class="space-y-4">
                @forelse($typeBreakdown as $type => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($type === 'individual') bg-purple-400
                            @elseif($type === 'company') bg-blue-400
                            @elseif($type === 'partnership') bg-green-400
                            @elseif($type === 'subcontractor') bg-orange-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $type }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No contractors found</div>
                @endforelse
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Status Distribution</h3>
            <div class="space-y-4">
                @forelse($statusBreakdown as $status => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($status === 'active') bg-green-400
                            @elseif($status === 'inactive') bg-gray-400
                            @elseif($status === 'suspended') bg-yellow-400
                            @elseif($status === 'blacklisted') bg-red-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $status }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No contractors found</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Availability & Specialization Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Availability Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Availability Distribution</h3>
            <div class="space-y-4">
                @forelse($availabilityBreakdown as $availability => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3
                            @if($availability === 'available') bg-green-400
                            @elseif($availability === 'busy') bg-yellow-400
                            @elseif($availability === 'unavailable') bg-red-400
                            @elseif($availability === 'on_project') bg-blue-400
                            @endif"></div>
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $availability) }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No contractors found</div>
                @endforelse
            </div>
        </div>

        <!-- Top Specializations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Top Specializations</h3>
            <div class="space-y-4">
                @forelse($specializationBreakdown as $specialization)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3 bg-teal-400"></div>
                        <span class="text-sm font-medium text-gray-700">{{ Str::limit($specialization->specialization, 25) }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900">{{ $specialization->count }}</span>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">No specializations found</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent & Top Contractors -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Contractors -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Contractor Additions</h3>
            <div class="space-y-4">
                @forelse($recentContractors as $contractor)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-teal-600 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($contractor->display_name, 30) }}</p>
                            <p class="text-xs text-gray-500">{{ $contractor->creator->name }} • {{ $contractor->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $contractor->status_badge }}">
                            {{ ucfirst($contractor->status) }}
                        </span>
                        @if($contractor->rating)
                            <span class="text-sm font-medium text-gray-900">{{ $contractor->rating }}/5</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">No recent contractors</div>
                @endforelse
            </div>
        </div>

        <!-- Top Performing Contractors -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Top Performing Contractors</h3>
            <div class="space-y-4">
                @forelse($performingContractors as $contractor)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($contractor->display_name, 25) }}</p>
                            <p class="text-xs text-gray-500">{{ $contractor->total_projects }} projects • {{ $contractor->years_of_experience }}y exp</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($contractor->total_value, 0) }}</span>
                        @if($contractor->rating)
                            <span class="text-xs text-yellow-500">{{ str_repeat('★', floor($contractor->rating)) }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">No performing contractors</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
