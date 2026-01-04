@extends('layouts.app')

@section('title', 'Company Policies')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Company Policies</h1>
            <p class="text-gray-600">Manage and maintain company policies and procedures</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('company-policies.dashboard') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Dashboard
            </a>
            <a href="{{ route('company-policies.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Create Policy
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
            <div>
                <p class="text-xs font-bold text-blue-600 uppercase mb-1">Total Policies</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['total_policies']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
            <div>
                <p class="text-xs font-bold text-green-600 uppercase mb-1">Active</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['active_policies']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
            <div>
                <p class="text-xs font-bold text-yellow-600 uppercase mb-1">Draft</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['draft_policies']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-4">
            <div>
                <p class="text-xs font-bold text-red-600 uppercase mb-1">Mandatory</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['mandatory_policies']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-orange-500 p-4">
            <div>
                <p class="text-xs font-bold text-orange-600 uppercase mb-1">Expiring Soon</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['expiring_soon']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-4">
            <div>
                <p class="text-xs font-bold text-purple-600 uppercase mb-1">Needs Review</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['needs_review']) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-indigo-500 p-4">
            <div>
                <p class="text-xs font-bold text-indigo-600 uppercase mb-1">This Month</p>
                <p class="text-xl font-bold text-gray-800">{{ number_format($stats['this_month_created']) }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search policies..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    <option value="hr_policies" {{ request('category') == 'hr_policies' ? 'selected' : '' }}>HR Policies</option>
                    <option value="safety_policies" {{ request('category') == 'safety_policies' ? 'selected' : '' }}>Safety Policies</option>
                    <option value="it_policies" {{ request('category') == 'it_policies' ? 'selected' : '' }}>IT Policies</option>
                    <option value="financial_policies" {{ request('category') == 'financial_policies' ? 'selected' : '' }}>Financial Policies</option>
                    <option value="operational_policies" {{ request('category') == 'operational_policies' ? 'selected' : '' }}>Operational Policies</option>
                    <option value="quality_policies" {{ request('category') == 'quality_policies' ? 'selected' : '' }}>Quality Policies</option>
                    <option value="environmental_policies" {{ request('category') == 'environmental_policies' ? 'selected' : '' }}>Environmental Policies</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mandatory</label>
                <select name="is_mandatory" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="1" {{ request('is_mandatory') == '1' ? 'selected' : '' }}>Mandatory</option>
                    <option value="0" {{ request('is_mandatory') == '0' ? 'selected' : '' }}>Optional</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Policies Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Policy</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effective Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($policies as $policy)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-file-alt text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $policy->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $policy->policy_code }}</div>
                                    @if($policy->is_mandatory)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Mandatory
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $policy->category_badge }}">
                                {{ ucfirst(str_replace('_', ' ', $policy->category)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $policy->status_badge }}">
                                {{ ucfirst($policy->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $policy->priority_badge }}">
                                {{ ucfirst($policy->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $policy->effective_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $policy->creator->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('company-policies.show', $policy) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('company-policies.edit', $policy) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($policy->status == 'draft')
                                    <form method="POST" action="{{ route('company-policies.approve', $policy) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Are you sure you want to approve this policy?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($policy->status == 'active')
                                    <form method="POST" action="{{ route('company-policies.archive', $policy) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to archive this policy?')">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-file-alt text-4xl text-gray-300 mb-2"></i>
                                <p>No policies found</p>
                                <a href="{{ route('company-policies.create') }}" class="text-blue-600 hover:text-blue-800 mt-2">Create your first policy</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($policies->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $policies->links() }}
        </div>
        @endif
    </div>
</div>
@endsection





