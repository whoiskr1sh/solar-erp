@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Document Details</h1>
            <p class="text-gray-600">{{ $document->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('documents.download', $document) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download
            </a>
            <a href="{{ route('documents.edit', $document) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('documents.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    <!-- Expiry Alert -->
    @if($document->is_expired)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Expired:</strong> This document expired on {{ $document->expiry_date->format('M d, Y') }}.
        </div>
    @elseif($document->expiry_date && $document->expiry_date->diffInDays(now()) <= 30)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
            <strong>Expiring Soon:</strong> This document will expire on {{ $document->expiry_date->format('M d, Y') }}.
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Document Preview -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Document Preview</h3>
                <div class="text-center">
                    <div class="mb-4 flex justify-center">{!! $document->file_icon !!}</div>
                    <h4 class="text-lg font-medium text-gray-900">{{ $document->title }}</h4>
                    <p class="text-sm text-gray-500">{{ $document->file_name }}</p>
                    <p class="text-sm text-gray-500">{{ $document->file_size_formatted }}</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('documents.download', $document) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download File
                        </a>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($document->description)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                    <p class="text-sm text-gray-900">{{ $document->description }}</p>
                </div>
            @endif

            <!-- Tags -->
            @if($document->tags && count($document->tags) > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($document->tags as $tag)
                            <span class="inline-flex px-3 py-1 text-sm font-medium bg-teal-100 text-teal-800 rounded-full">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Document Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Document Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $document->category_badge }}">
                            {{ ucfirst(str_replace('_', ' ', $document->category)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $document->status_badge }}">
                            {{ ucfirst($document->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">File Type</label>
                        <p class="text-sm text-gray-900">{{ $document->file_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">File Size</label>
                        <p class="text-sm text-gray-900">{{ $document->file_size_formatted }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created Date</label>
                        <p class="text-sm text-gray-900">{{ $document->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created By</label>
                        <p class="text-sm text-gray-900">{{ $document->creator->name }}</p>
                    </div>
                    @if($document->expiry_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <p class="text-sm text-gray-900">{{ $document->expiry_date->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Related Information</h3>
                <div class="space-y-4">
                    @if($document->lead)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Related Lead</label>
                            <p class="text-sm text-gray-900">{{ $document->lead->name }}</p>
                            <p class="text-sm text-gray-500">{{ $document->lead->company }}</p>
                        </div>
                    @endif
                    
                    @if($document->project)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Related Project</label>
                            <p class="text-sm text-gray-900">{{ $document->project->name }}</p>
                            <p class="text-sm text-gray-500">{{ $document->project->project_code }}</p>
                        </div>
                    @endif
                    
                    @if(!$document->lead && !$document->project)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Related To</label>
                            <p class="text-sm text-gray-500">No related lead or project</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('documents.download', $document) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download
                    </a>
                    
                    <a href="{{ route('documents.edit', $document) }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Document
                    </a>
                    
                    <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Are you sure you want to delete this document?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Document
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
