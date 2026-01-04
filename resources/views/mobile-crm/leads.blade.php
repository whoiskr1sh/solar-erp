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
                    <h1 class="text-xl font-bold text-gray-900">Leads</h1>
                    <p class="text-sm text-gray-600">{{ $leads->total() }} total leads</p>
                </div>
            </div>
            <a href="{{ route('leads.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white p-2 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="relative">
            <input type="text" placeholder="Search leads..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Leads List -->
    <div class="space-y-3">
        @forelse($leads as $lead)
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <h3 class="text-lg font-medium text-gray-900">{{ $lead->name }}</h3>
                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $lead->status_badge }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </div>
                    
                    @if($lead->company)
                    <p class="text-sm text-gray-600 mb-1">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ $lead->company }}
                    </p>
                    @endif

                    @if($lead->email)
                    <p class="text-sm text-gray-600 mb-1">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ $lead->email }}
                    </p>
                    @endif

                    @if($lead->phone)
                    <p class="text-sm text-gray-600 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $lead->phone }}
                    </p>
                    @endif

                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $lead->created_at->format('M d, Y') }}
                        <span class="mx-2">•</span>
                        <span>{{ ucfirst($lead->source) }}</span>
                    </div>
                </div>
                
                <div class="flex flex-col space-y-2 ml-4">
                    <a href="{{ route('leads.show', $lead) }}" class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-1 rounded text-sm text-center transition-colors duration-200">
                        View
                    </a>
                    <a href="{{ route('leads.edit', $lead) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm text-center transition-colors duration-200">
                        Edit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No leads found</h3>
            <p class="text-gray-600 mb-4">Get started by creating your first lead.</p>
            <a href="{{ route('leads.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg">Create Lead</a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($leads->hasPages())
    <div class="mt-6">
        {{ $leads->links() }}
    </div>
    @endif
</div>
@endsection
