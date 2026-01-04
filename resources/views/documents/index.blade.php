@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Documents</h1>
            <p class="text-gray-600">Manage your documents and files</p>
        </div>
        <a href="{{ route('documents.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Upload Document
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-lg shadow p-3">
            <div class="flex items-center">
                <div class="p-1.5 bg-blue-100 rounded-lg">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-2">
                    <p class="text-xs font-medium text-gray-600">Total</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-3">
            <div class="flex items-center">
                <div class="p-1.5 bg-green-100 rounded-lg">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-2">
                    <p class="text-xs font-medium text-gray-600">Active</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $stats['active'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-3">
            <div class="flex items-center">
                <div class="p-1.5 bg-yellow-100 rounded-lg">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-2">
                    <p class="text-xs font-medium text-gray-600">Draft</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $stats['draft'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-3">
            <div class="flex items-center">
                <div class="p-1.5 bg-purple-100 rounded-lg">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <div class="ml-2">
                    <p class="text-xs font-medium text-gray-600">Size</p>
                    <p class="text-sm font-semibold text-gray-900">{{ number_format($stats['total_size'] / 1024 / 1024, 1) }} MB</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                <select name="category" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="">All Categories</option>
                    <option value="proposal" {{ request('category') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                    <option value="contract" {{ request('category') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="invoice" {{ request('category') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                    <option value="quotation" {{ request('category') == 'quotation' ? 'selected' : '' }}>Quotation</option>
                    <option value="report" {{ request('category') == 'report' ? 'selected' : '' }}>Report</option>
                    <option value="presentation" {{ request('category') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                    <option value="technical_spec" {{ request('category') == 'technical_spec' ? 'selected' : '' }}>Technical Spec</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    <option value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Lead</label>
                <select name="lead_id" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="">All Leads</option>
                    @foreach($leads as $lead)
                        <option value="{{ $lead->id }}" {{ request('lead_id') == $lead->id ? 'selected' : '' }}>{{ Str::limit($lead->name, 20) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Project</label>
                <select name="project_id" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>{{ Str::limit($project->name, 20) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 text-sm rounded">Filter</button>
            </div>
        </form>
    </div>

    <!-- Documents Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Document</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Category</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Size</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Related To</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Created</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($documents as $document)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-4">
                                <div class="flex items-center">
                                    <div class="mr-2">{!! $document->file_icon !!}</div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $document->title }}</div>
                                        <div class="text-xs text-gray-500 truncate">{{ $document->file_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $document->category_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $document->category)) }}
                                </span>
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-900">
                                {{ $document->file_size_formatted }}
                            </td>
                            <td class="px-3 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $document->status_badge }}">
                                    {{ ucfirst($document->status) }}
                                </span>
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-900">
                                @if($document->lead)
                                    <div class="text-sm text-gray-900 truncate">{{ $document->lead->name }}</div>
                                    <div class="text-xs text-gray-500">Lead</div>
                                @elseif($document->project)
                                    <div class="text-sm text-gray-900 truncate">{{ $document->project->name }}</div>
                                    <div class="text-xs text-gray-500">Project</div>
                                @else
                                    <span class="text-sm text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-900">
                                {{ $document->created_at->format('M d') }}
                            </td>
                            <td class="px-3 py-4 text-sm font-medium">
                                <div class="flex flex-col space-y-1">
                                    <a href="{{ route('documents.show', $document) }}" class="text-teal-600 hover:text-teal-900 text-xs">View</a>
                                    <a href="{{ route('documents.download', $document) }}" class="text-blue-600 hover:text-blue-900 text-xs">Download</a>
                                    <a href="{{ route('documents.edit', $document) }}" class="text-green-600 hover:text-green-900 text-xs">Edit</a>
                                    <form method="POST" action="{{ route('documents.destroy', $document) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-4 text-center text-gray-500">No documents found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
