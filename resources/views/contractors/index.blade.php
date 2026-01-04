@extends('layouts.app')

@section('title', 'Contractor Management')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contractor Management</h1>
            <p class="text-gray-600">Manage contractors and service providers</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('contractors.dashboard') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Dashboard
            </a>
            <a href="{{ route('contractors.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Add Contractor
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-1">Total Contractors</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_contractors']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-green-600 uppercase mb-1">Active</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['active_contractors']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-purple-600 uppercase mb-1">Verified</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['verified_contractors']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Available</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['available_contractors']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-indigo-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-indigo-600 uppercase mb-1">Companies</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['companies']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-cyan-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-cyan-600 uppercase mb-1">Individuals</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['individuals']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-green-600 uppercase mb-1">Avg Rating</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($stats['avg_rating'], 1) }}/5</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-blue-600 uppercase mb-1">Avg Experience</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($stats['avg_experience'], 1) }} years</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-xs font-bold text-purple-600 uppercase mb-1">Total Value</p>
                    <p class="text-xl font-bold text-gray-800">₹{{ number_format($stats['total_projects_value'], 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-8 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="search" value="{{ request('search') }}" placeholder="Search contractors...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="individual" {{ request('type') == 'individual' ? 'selected' : '' }}>Individual</option>
                    <option value="company" {{ request('type') == 'company' ? 'selected' : '' }}>Company</option>
                    <option value="partnership" {{ request('type') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                    <option value="subcontractor" {{ request('type') == 'subcontractor' ? 'selected' : '' }}>Subcontractor</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                <select name="availability" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Availability</option>
                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="busy" {{ request('availability') == 'busy' ? 'selected' : '' }}>Busy</option>
                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    <option value="on_project" {{ request('availability') == 'on_project' ? 'selected' : '' }}>On Project</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="city" value="{{ request('city') }}" placeholder="City">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" name="specialization" value="{{ request('specialization') }}" placeholder="Specialization">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Verified</label>
                <select name="verified" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All</option>
                    <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>Verified</option>
                    <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>

            <div class="flex items-end">
                <div class="flex space-x-2">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                        Filter
                    </button>
                    <a href="{{ route('contractors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Code</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">Contractor Details</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Location</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Type</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Contact</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Experience</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Rating</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Availability</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contractors as $contractor)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-4 text-sm text-gray-900">{{ $contractor->contractor_code }}</td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            <div class="max-w-xs">
                                <div class="font-medium">{{ Str::limit($contractor->display_name, 25) }}</div>
                                @if($contractor->contact_person !== $contractor->company_name)
                                    <div class="text-gray-500 text-xs">{{ Str::limit($contractor->contact_person, 20) }}</div>
                                @endif
                                <div class="flex items-center mt-1">
                                    @if($contractor->is_verified)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unverified</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            <div>
                                <div>{{ Str::limit($contractor->city, 15) }},</div>
                                <div>{{ Str::limit($contractor->state, 15) }}</div>
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $contractor->type_badge }}">
                                {{ ucfirst($contractor->contractor_type) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            <div>
                                <div>{{ $contractor->phone }}</div>
                                @if($contractor->email)
                                    <div class="text-xs text-gray-500">{{ Str::limit($contractor->email, 15) }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm text-gray-900">{{ $contractor->years_of_experience }}y</td>
                        <td class="px-3 py-4 text-sm text-gray-900">
                            @if($contractor->rating)
                                <div class="flex items-center">
                                    <span class="text-yellow-500">{{ str_repeat('★', floor($contractor->rating)) }}</span>
                                    <span class="text-gray-400">{{ str_repeat('☆', 5 - floor($contractor->rating)) }}</span>
                                    <span class="ml-1 text-xs">{{ $contractor->rating }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">Not Rated</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $contractor->status_badge }}">
                                {{ ucfirst($contractor->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $contractor->availability_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $contractor->availability)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('contractors.show', $contractor) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="View">V</a>
                                <a href="{{ route('contractors.edit', $contractor) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs" title="Edit">E</a>
                                @if(!$contractor->is_verified)
                                <form method="POST" action="{{ route('contractors.verify', $contractor) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs" title="Verify" onclick="return confirm('Verify this contractor?')">✓</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('contractors.destroy', $contractor) }}" class="inline" onsubmit="return confirm('Are you sure you want to deactivate this contractor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs" title="Deactivate">D</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                            No contractors found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            {{ $contractors->links() }}
        </div>
    </div>
</div>
@endsection
