@extends('layouts.app')

@section('title', 'Leads Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    @php
        $viewMode = $viewMode ?? 'all';
    @endphp
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Leads Management</h1>
            <p class="mt-2 text-gray-600">Manage your sales leads and prospects</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            @php
                $assignedLeadsCount = isset($viewedLeadIds) ? count($viewedLeadIds) : 0;
                $isUnavailable = !auth()->user()->isAvailableForFollowup();

                $hasPendingRequest = false;
                if (class_exists(\App\Models\LeadReassignmentRequest::class) && \Illuminate\Support\Facades\Schema::hasTable('lead_reassignment_requests')) {
                    $hasPendingRequest = \App\Models\LeadReassignmentRequest::where('requested_by', auth()->id())
                        ->whereIn('status', ['pending_manager_approval', 'pending_admin_approval'])
                        ->exists();
                }
            @endphp
            
            {{-- Show reassignment button to all users (only hide if pending request exists) --}}
            @if(!$hasPendingRequest)
                @if($assignedLeadsCount > 0)
                    {{-- User has assigned leads --}}
                    @if($isUnavailable)
                        {{-- Unavailable - show with orange/warning styling --}}
                        <a href="{{ route('leads.request-reassignment') }}" class="inline-flex items-center px-4 py-2 border border-orange-300 rounded-md shadow-sm text-sm font-medium text-orange-700 bg-orange-50 hover:bg-orange-100">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Request Reassignment ({{ $assignedLeadsCount }} viewed leads)
                        </a>
                    @else
                        {{-- Available - show with blue/info styling --}}
                        <a href="{{ route('leads.request-reassignment') }}" class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Request Reassignment ({{ $assignedLeadsCount }} viewed leads)
                        </a>
                    @endif
                @else
                    {{-- User has no assigned leads - still show button but with different styling --}}
                    <a href="{{ route('leads.request-reassignment') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        Request Reassignment
                    </a>
                @endif
            @endif
            
            @if($hasPendingRequest)
                <span class="inline-flex items-center px-4 py-2 border border-yellow-300 rounded-md shadow-sm text-sm font-medium text-yellow-700 bg-yellow-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Reassignment Request Pending
                </span>
            @endif
            
            <button onclick="openImportModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
                Import via Excel
            </button>
            <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Lead
            </a>
        </div>
    </div>

    <!-- Stats Cards (always at top under header) -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Interested</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($stats['interested']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Partially Interested</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($stats['partially_interested']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Needs Follow-up</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($stats['needs_followup']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Not Interested</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($stats['not_interested']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Lookup & Filters (shown below stats for all views) -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Check Lead Details via Email</h3>
        <form method="POST" action="{{ route('leads.lookup-by-email') }}" class="flex gap-4">
            @csrf
            <div class="flex-1">
                <input type="email" name="email" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                       placeholder="Enter email address to lookup lead...">
            </div>
            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Lookup
            </button>
        </form>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('leads.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="hidden" name="view" value="{{ $viewMode }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                       placeholder="Search leads...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Status</option>
                    <option value="interested" {{ request('status') == 'interested' ? 'selected' : '' }}>Interested</option>
                    <option value="not_interested" {{ request('status') == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                    <option value="partially_interested" {{ request('status') == 'partially_interested' ? 'selected' : '' }}>Partially Interested</option>
                    <option value="not_reachable" {{ request('status') == 'not_reachable' ? 'selected' : '' }}>Not Reachable</option>
                    <option value="not_answered" {{ request('status') == 'not_answered' ? 'selected' : '' }}>Not Answered</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                <select name="source" id="source_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Sources</option>
                    <option value="website" {{ request('source') == 'website' ? 'selected' : '' }}>Website</option>
                    <option value="indiamart" {{ request('source') == 'indiamart' ? 'selected' : '' }}>IndiaMart</option>
                    <option value="justdial" {{ request('source') == 'justdial' ? 'selected' : '' }}>JustDial</option>
                    <option value="meta_ads" {{ request('source') == 'meta_ads' ? 'selected' : '' }}>Meta Ads</option>
                    <option value="referral" {{ request('source') == 'referral' ? 'selected' : '' }}>Referral</option>
                    <option value="cold_call" {{ request('source') == 'cold_call' ? 'selected' : '' }}>Cold Call</option>
                    <option value="other" {{ request('source') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            @if($viewMode === 'all' && (auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('PROJECT MANAGER')))
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                <select name="assigned_to" id="assigned_to_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Users</option>
                    @if(isset($users) && $users->count() > 0)
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            @endif
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Follow-up Leads Filter Section -->
    @if(($viewMode === 'all') && (auth()->user()->hasRole('TELE SALES') || auth()->user()->hasRole('FIELD SALES') || auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('SALES MANAGER')))
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-yellow-900 mb-4">Follow-up Leads Filter</h3>
        <p class="text-sm text-yellow-800 mb-4">Filter follow-up leads by name, status, or sub-coordinator</p>
        <form method="GET" action="{{ route('leads.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Preserve other filters -->
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('source'))
                <input type="hidden" name="source" value="{{ request('source') }}">
            @endif
            @if(request('assigned_to') && (auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('PROJECT MANAGER')))
                <input type="hidden" name="assigned_to" value="{{ request('assigned_to') }}">
            @endif
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <input type="hidden" name="view" value="{{ $viewMode }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Name</label>
                <input type="text" name="follow_up_name" value="{{ request('follow_up_name') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                       placeholder="Enter lead name to search...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Follow-up Status</label>
                <select name="follow_up_status" id="follow_up_status_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Follow-up Leads</option>
                    <option value="partially_interested" {{ request('follow_up_status') == 'partially_interested' ? 'selected' : '' }}>Partially Interested</option>
                    <option value="not_reachable" {{ request('follow_up_status') == 'not_reachable' ? 'selected' : '' }}>Not Reachable</option>
                    <option value="not_answered" {{ request('follow_up_status') == 'not_answered' ? 'selected' : '' }}>Not Answered</option>
                    <option value="overdue" {{ request('follow_up_status') == 'overdue' ? 'selected' : '' }}>Overdue Follow-up</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sub-Coordinator</label>
                <select name="sub_coordinator" id="sub_coordinator_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                    <option value="">All Sub-Coordinators</option>
                    @if(isset($subCoordinators) && $subCoordinators->count() > 0)
                        @foreach($subCoordinators as $coordinator)
                            <option value="{{ $coordinator->id }}" {{ request('sub_coordinator') == $coordinator->id ? 'selected' : '' }}>
                                {{ $coordinator->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    Filter Follow-up
                </button>
                @if(request('follow_up_name') || request('follow_up_status') || request('sub_coordinator'))
                <a href="{{ route('leads.index', array_merge(request()->except(['follow_up_name', 'follow_up_status', 'sub_coordinator']), ['view' => $viewMode])) }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>
    @endif

    

    <!-- Assigned (Viewed) Leads Section -->
    @php
        $viewedLeadIds = is_array($viewedLeadIds ?? null) ? $viewedLeadIds : [];
        $assignedViewedLeads = $leads->filter(function ($lead) use ($viewedLeadIds) {
            return in_array($lead->id, $viewedLeadIds);
        });
    @endphp

    @if($viewMode === 'assigned')
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Assigned Leads (Viewed Contacts)</h3>
                <span class="text-sm text-gray-500">Total: {{ $assignedViewedLeads->count() }} lead(s)</span>
            </div>

            @if($assignedViewedLeads->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead Stage</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($assignedViewedLeads as $lead)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">{{ substr($lead->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-2">
                                            <div class="text-sm font-medium text-gray-900">{{ $lead->name }}</div>
                                            @if($lead->company)
                                                <div class="text-xs text-gray-500">{{ $lead->company }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    @php 
                                        $isSuperAdmin = auth()->user()->hasRole('SUPER ADMIN');
                                        $isAssignedUser = $lead->assigned_user_id == auth()->id();
                                        $isUnassigned = $lead->assigned_user_id === null;
                                    @endphp
                                    @if($isSuperAdmin)
                                        <span class="text-gray-900">{{ $lead->phone ?? 'N/A' }}</span>
                                    @elseif($isAssignedUser)
                                        <span class="text-gray-900">{{ $lead->phone ?? 'N/A' }}</span>
                                    @elseif($isUnassigned)
                                        <form method="POST" action="{{ route('leads.reveal-contact', $lead->id) }}" style="display:inline" onsubmit="event.preventDefault(); revealContactAjax({{ $lead->id }});">
                                            @csrf
                                            <span id="phone-blur-{{ $lead->id }}" style="filter: blur(4px); -webkit-filter: blur(4px);">Contact hidden</span>
                                            <button type="submit" class="ml-1 cursor-pointer bg-transparent border-none p-0" style="background:none;outline:none;" title="Show phone">👁️</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic">Contact restricted</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    @php 
                                        $isSuperAdmin = auth()->user()->hasRole('SUPER ADMIN');
                                        $isAssignedUser = $lead->assigned_user_id == auth()->id();
                                        $isUnassigned = $lead->assigned_user_id === null;
                                    @endphp
                                    @if($isSuperAdmin)
                                        <span class="text-gray-900" id="email-blur-{{ $lead->id }}">{{ $lead->email ?? 'N/A' }}</span>
                                    @elseif($isAssignedUser)
                                        <span class="text-gray-900" id="email-blur-{{ $lead->id }}">{{ $lead->email ?? 'N/A' }}</span>
                                    @elseif($isUnassigned)
                                        <form method="POST" action="{{ route('leads.reveal-contact', $lead->id) }}" style="display:inline" onsubmit="event.preventDefault(); revealContactAjax({{ $lead->id }});">
                                            @csrf
                                            <span id="email-blur-{{ $lead->id }}" style="filter: blur(4px); -webkit-filter: blur(4px);">Contact hidden</span>
                                            <button type="submit" class="ml-1 cursor-pointer bg-transparent border-none p-0" style="background:none;outline:none;" title="Show email">👁️</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic">Contact restricted</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $isAssignedUser = $lead->assigned_user_id == auth()->id();
                                    @endphp
                                    @if($isAssignedUser)
                                        <form method="POST" action="{{ route('leads.updateStatus', $lead->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium border-gray-300 focus:ring-teal-500 focus:border-teal-500 {{ $lead->status_badge }}">
                                                <option value="interested" {{ $lead->status == 'interested' ? 'selected' : '' }}>Interested</option>
                                                <option value="partially_interested" {{ $lead->status == 'partially_interested' ? 'selected' : '' }}>Partially Interested</option>
                                                <option value="not_interested" {{ $lead->status == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                                                <option value="not_reachable" {{ $lead->status == 'not_reachable' ? 'selected' : '' }}>Not Reachable</option>
                                                <option value="not_answered" {{ $lead->status == 'not_answered' ? 'selected' : '' }}>Not Answered</option>
                                            </select>
                                        </form>
                                        @if($lead->needsFollowUp())
                                            @if($lead->isFollowUpOverdue())
                                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Overdue
                                            </span>
                                            @else
                                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                                    </svg>
                                                    Follow-up Needed
                                                </span>
                                            @endif
                                            @if($lead->follow_up_date)
                                                <span class="text-xs {{ $lead->isFollowUpOverdue() ? 'text-red-600 font-semibold' : 'text-gray-500' }} mt-0.5">
                                                    Due: {{ $lead->follow_up_date->format('M d, Y') }}
                                                </span>
                                            @endif
                                        @elseif(in_array($lead->status, ['interested', 'partially_interested']) && $lead->follow_up_date)
                                            <span class="text-xs text-blue-600 mt-1">
                                                Follow-up: {{ $lead->follow_up_date->format('M d, Y') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium border-gray-300 {{ $lead->status_badge }}">{{ ucfirst(str_replace('_', ' ', $lead->status)) }}</span>
                                        @if($lead->needsFollowUp())
                                            @if($lead->isFollowUpOverdue())
                                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Overdue
                                            </span>
                                            @else
                                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                                    </svg>
                                                    Follow-up Needed
                                                </span>
                                            @endif
                                            @if($lead->follow_up_date)
                                                <span class="text-xs {{ $lead->isFollowUpOverdue() ? 'text-red-600 font-semibold' : 'text-gray-500' }} mt-0.5">
                                                    Due: {{ $lead->follow_up_date->format('M d, Y') }}
                                                </span>
                                            @endif
                                        @elseif(in_array($lead->status, ['interested', 'partially_interested']) && $lead->follow_up_date)
                                            <span class="text-xs text-blue-600 mt-1">
                                                Follow-up: {{ $lead->follow_up_date->format('M d, Y') }}
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($lead->assigned_user_id == auth()->id())
                                        <form method="POST" action="{{ route('leads.updateStage', $lead->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="lead_stage" onchange="this.form.submit()" class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium border-gray-300 focus:ring-teal-500 focus:border-teal-500 {{ $lead->lead_stage_badge }}">
                                                <option value="not_set" {{ $lead->lead_stage == 'not_set' ? 'selected' : '' }}>Not Set</option>
                                                <option value="new" {{ $lead->lead_stage == 'new' ? 'selected' : '' }}>New</option>
                                                <option value="contacted" {{ $lead->lead_stage == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                <option value="qualified" {{ $lead->lead_stage == 'qualified' ? 'selected' : '' }}>Qualified</option>
                                                <option value="proposal_sent" {{ $lead->lead_stage == 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                                                <option value="negotiation" {{ $lead->lead_stage == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                                                <option value="won" {{ $lead->lead_stage == 'won' ? 'selected' : '' }}>Won</option>
                                                <option value="lost" {{ $lead->lead_stage == 'lost' ? 'selected' : '' }}>Lost</option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium border-gray-300">{{ ucfirst(str_replace('_', ' ', $lead->lead_stage ?? 'Not Set')) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lead->priority_badge }}">
                                        {{ ucfirst($lead->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                    {{ $lead->creator->name ?? 'Unknown' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('leads.show', $lead) }}" class="text-teal-600 hover:text-teal-900">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500">No viewed leads found yet. View a lead's contact number to have it appear here.</p>
            @endif
        </div>
    @endif


    @if($viewMode === 'all')
    <!-- Leads Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">All Your Assigned Leads</h3>
                <span class="text-sm text-gray-500">Total: {{ $leads->total() }} lead(s)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-32 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead</th>
                            <th class="w-24 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            @if(auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('PROJECT MANAGER'))
                            <th class="w-20 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                            @endif
                            <th class="w-20 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                            <th class="w-20 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="w-32 max-w-xs px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lead Stage</th>
                            <th class="w-20 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                            <th class="w-16 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="w-20 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="w-20 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Lead</th>
                            <th class="w-24 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quotations</th>
                            <th class="w-28 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revised Quotations</th>
                            <th class="w-24 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($leads as $lead)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">{{ substr($lead->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-2">
                                        <div class="text-xs font-medium text-gray-900 truncate" title="{{ $lead->name }}">{{ Str::limit($lead->name, 15) }}</div>
                                        <div class="text-xs text-gray-500 truncate" title="{{ $lead->company ?? 'No Company' }}">{{ Str::limit($lead->company ?? 'No Company', 12) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @php
                                    $isAssignedUser = $lead->assigned_user_id == auth()->id();
                                    $isAdminOrManager = auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('SALES MANAGER');
                                    $isSuperAdmin = auth()->user()->hasRole('SUPER ADMIN');
                                    $isUnassigned = $lead->assigned_user_id === null;
                                    $contactViewsArray = isset($contactViews) && is_array($contactViews) ? $contactViews : [];
                                    $hasViewedContact = in_array($lead->id, $contactViewsArray);
                                    // Can view if: assigned to user, OR admin/manager, OR lead is unassigned (anyone can claim it)
                                    $canViewContact = $isAssignedUser || $isAdminOrManager || $isUnassigned;
                                    $contactViewer = isset($contactViewsWithUsers[$lead->id]) ? $contactViewsWithUsers[$lead->id] : null;
                                @endphp
                                
                                @if($isSuperAdmin)
                                    {{-- Super Admin - show contact directly without blur --}}
                                    <div class="text-xs">
                                        <div class="text-gray-900 truncate" title="{{ $lead->phone }}">{{ Str::limit($lead->phone, 12) }}</div>
                                        <div class="text-xs text-gray-400 italic">Contact restricted</div>
                                    </div>
                                @elseif($canViewContact && !$hasViewedContact)
                                    {{-- Blurred contact - can be clicked to reveal (for assigned user, admin/manager, or unassigned leads) --}}
                                    <div class="text-xs" id="contact-container-{{ $lead->id }}">
                                        <div class="relative flex items-center">
                                            <span class="text-gray-900 cursor-pointer transition-all duration-200 select-none" 
                                                  id="phone-blur-{{ $lead->id }}"
                                                  onclick="revealContactAjax({{ $lead->id }}, this)"
                                                  title="Click to reveal contact number{{ $isUnassigned ? ' (will assign lead to you)' : '' }}"
                                                  data-revealed="false"
                                                  style="filter: blur(4px); -webkit-filter: blur(4px);">
                                                {{ Str::limit($lead->phone, 12) }}
                                            </span>
                                            <span class="text-xs text-blue-600 ml-1 cursor-pointer" title="Click to reveal{{ $isUnassigned ? ' (will assign lead to you)' : '' }}" onclick="revealContactAjax({{ $lead->id }}, document.getElementById('phone-blur-{{ $lead->id }}'))">👁️</span>
                                        </div>
                                        @php $isAssignedUser = $lead->assigned_user_id == auth()->id(); @endphp
                                        @if($isAssignedUser)
                                            <div class="text-xs text-gray-900 truncate mt-1 flex items-center">
                                                @if($isAssignedUser)
                                                    <span class="text-gray-400 italic">Contact restricted</span>
                                                @elseif($lead->assigned_user_id === null)
                                                    <form method="POST" action="{{ route('leads.reveal-contact', $lead->id) }}" style="display:inline">
                                                        @csrf
                                                        <span class="text-gray-400 italic">Contact restricted</span>
                                                        <button type="submit" class="ml-1 cursor-pointer bg-transparent border-none p-0" style="background:none;outline:none;" title="Show email">👁️</button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 italic">Contact restricted</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-xs text-gray-400 italic mt-1">Contact restricted</div>
                                        @endif
                                        @if($isUnassigned)
                                            <div class="text-xs text-orange-600 mt-1 italic">Unassigned - Click to claim</div>
                                        @endif
                                    </div>
                                @elseif($canViewContact && $hasViewedContact)
                                    {{-- Already viewed - show normally with viewer name --}}
                                    <div class="text-xs">
                                        <div class="text-gray-900 truncate" title="{{ $lead->phone }}">{{ Str::limit($lead->phone, 12) }}</div>
                                        @php $isAssignedUser = $lead->assigned_user_id == auth()->id(); @endphp
                                        @if($isAssignedUser)
                                            <div class="text-xs text-gray-900 truncate" title="Email revealed">Email revealed</div>
                                        @else
                                            <div class="text-xs text-gray-400 italic">Contact restricted</div>
                                        @endif
                                        @if($contactViewer)
                                            <div class="text-xs text-blue-600 mt-1" title="Viewed by {{ $contactViewer['user_name'] }} on {{ $contactViewer['viewed_at'] ? \Carbon\Carbon::parse($contactViewer['viewed_at'])->format('M d, Y g:i A') : 'N/A' }}">
                                                👁️ Viewed by {{ Str::limit($contactViewer['user_name'], 15) }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    {{-- Not assigned to user and not admin/manager and lead is assigned to someone else - show restricted message --}}
                                    <div class="text-xs">
                                        <div class="text-gray-500 italic mb-1">Contact restricted</div>
                                        <div class="text-xs text-gray-500 truncate" title="{{ $lead->email ?? 'No Email' }}">{{ Str::limit($lead->email ?? 'No Email', 12) }}</div>
                                        @if($contactViewer)
                                            <div class="text-xs text-gray-400 mt-1" title="Viewed by {{ $contactViewer['user_name'] }} on {{ $contactViewer['viewed_at'] ? \Carbon\Carbon::parse($contactViewer['viewed_at'])->format('M d, Y g:i A') : 'N/A' }}">
                                                Viewed by {{ Str::limit($contactViewer['user_name'], 15) }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            @if(auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('PROJECT MANAGER'))
                            <td class="px-3 py-3 whitespace-nowrap" id="assigned-to-{{ $lead->id }}">
                                @if($lead->assignedUser)
                                    <div class="text-xs">
                                        <div class="font-medium text-gray-900 truncate" title="{{ $lead->assignedUser->name }}">{{ Str::limit($lead->assignedUser->name, 15) }}</div>
                                        @if(auth()->user()->hasRole('SALES MANAGER') || auth()->user()->hasRole('SUPER ADMIN'))
                                            @if(!$lead->assignedUser->isAvailableForFollowup())
                                                <div class="flex items-center mt-1">
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Not Available
                                                    </span>
                                                </div>
                                                @if($lead->assignedUser->unavailability_reason)
                                                    <div class="text-xs text-gray-500 mt-0.5" title="{{ $lead->assignedUser->unavailability_reason }}">
                                                        {{ Str::limit($lead->assignedUser->unavailability_reason, 20) }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Available
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Unassigned</span>
                                @endif
                            </td>
                            @endif
                            <td class="px-3 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $lead->source)) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <div>
                                        @if($lead->assigned_user_id == auth()->id())
                                            <form method="POST" action="{{ route('leads.updateStatus', $lead->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium border-gray-300 focus:ring-teal-500 focus:border-teal-500 {{ $lead->status_badge }}">
                                                    <option value="interested" {{ $lead->status == 'interested' ? 'selected' : '' }}>Interested</option>
                                                    <option value="partially_interested" {{ $lead->status == 'partially_interested' ? 'selected' : '' }}>Partially Interested</option>
                                                    <option value="not_interested" {{ $lead->status == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                                                    <option value="not_reachable" {{ $lead->status == 'not_reachable' ? 'selected' : '' }}>Not Reachable</option>
                                                    <option value="not_answered" {{ $lead->status == 'not_answered' ? 'selected' : '' }}>Not Answered</option>
                                                </select>
                                            </form>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium border-gray-300 {{ $lead->status_badge }}">{{ ucfirst(str_replace('_', ' ', $lead->status)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        @if($lead->needsFollowUp())
                                            @if($lead->isFollowUpOverdue())
                                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Overdue
                                            </span>
                                            @else
                                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                                    </svg>
                                                    Follow-up Needed
                                                </span>
                                            @endif
                                            @if($lead->follow_up_date)
                                                <span class="text-xs {{ $lead->isFollowUpOverdue() ? 'text-red-600 font-semibold' : 'text-gray-500' }} block">
                                                    Due: {{ $lead->follow_up_date->format('M d, Y') }}
                                                </span>
                                            @endif
                                        @elseif(in_array($lead->status, ['interested', 'partially_interested']) && $lead->follow_up_date)
                                            <span class="text-xs text-blue-600 block">
                                                Follow-up: {{ $lead->follow_up_date->format('M d, Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($lead->assigned_user_id == auth()->id())
                                    <form method="POST" action="{{ route('leads.updateStage', $lead->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="lead_stage" onchange="this.form.submit()" class="inline-flex px-1 py-0.5 rounded-full text-xs font-medium border-gray-300 focus:ring-teal-500 focus:border-teal-500 min-w-0 w-auto {{ $lead->lead_stage_badge }}">
                                            <option value="not_set" {{ $lead->lead_stage == 'not_set' ? 'selected' : '' }}>Not Set</option>
                                            <option value="quotation_sent" {{ $lead->lead_stage == 'quotation_sent' ? 'selected' : '' }}>QUOTATION SENT</option>
                                            <option value="site_survey" {{ $lead->lead_stage == 'site_survey' ? 'selected' : '' }}>SITE SURVEY</option>
                                            <option value="document_collected" {{ $lead->lead_stage == 'document_collected' ? 'selected' : '' }}>DOCUMENT COLLECTED</option>
                                            <option value="loan_document_collected" {{ $lead->lead_stage == 'loan_document_collected' ? 'selected' : '' }}>LOAN DOCUMENT COLLECTED</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium border-gray-300">{{ ucfirst(str_replace('_', ' ', $lead->lead_stage ?? 'Not Set')) }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $lead->priority_badge }}">
                                    {{ ucfirst($lead->priority) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-900">
                                {{ $lead->creator->name ?? 'Unknown' }}
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-900">
                                {{ $lead->estimated_value ? '₹' . number_format($lead->estimated_value) : 'N/A' }}
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-xs">
                                @php
                                    $isNewLead = $lead->created_at >= now()->subDays(7);
                                @endphp
                                @if($isNewLead)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        New
                                    </span>
                                @else
                                    <span class="text-gray-500 text-xs">
                                        {{ $lead->created_at ? $lead->created_at->format('M d, Y') : '-' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-xs">
                                @php $quotationCount = $lead->latestQuotations->count(); @endphp
                                @if($quotationCount > 0)
                                    <a href="{{ route('leads.show', $lead) }}#quotations" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $quotationCount }} {{ Str::plural('Quotation', $quotationCount) }}
                                    </a>
                                @endif
                                <a href="{{ route('quotations.create', ['client_id' => $lead->id]) }}" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors duration-200 border border-blue-200 ml-2">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create
                                </a>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-xs">
                                @if($lead->selectedRevisedQuotation)
                                    <a href="{{ route('quotations.show', $lead->selectedRevisedQuotation) }}" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800" target="_blank">
                                        {{ $lead->selectedRevisedQuotation->quotation_number }} (Revised)
                                    </a>
                                @else
                                    @php $revisedCount = $lead->revised_quotations_count ?? 0; @endphp
                                    @if($revisedCount > 0)
                                        <a href="{{ route('leads.show', $lead) }}#quotations" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                            {{ $revisedCount }} {{ Str::plural('Revision', $revisedCount) }}
                                        </a>
                                    @else
                                        @php 
                                            $existingQuotations = \App\Models\Quotation::where('client_id', $lead->id)
                                                ->where('is_revision', false)
                                                ->orWhere(function($q) use ($lead) {
                                                    $q->where('client_id', $lead->id)
                                                      ->whereNull('parent_quotation_id');
                                                })
                                                ->distinct('id')
                                                ->get()
                                                ->unique('id');
                                        @endphp
                                        @if($existingQuotations->count() > 0)
                                            <button type="button" onclick="openSelectQuotationModal({{ $lead->id }}, '{{ $lead->name }}')" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors duration-200 border border-amber-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Select
                                            </button>
                                        @else
                                            <span class="text-gray-400 text-xs">No Quotations</span>
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('leads.show', $lead) }}" class="text-teal-600 hover:text-teal-900 text-xs leading-none">View</a>
                                    <a href="{{ route('leads.documents', $lead) }}" class="text-blue-600 hover:text-blue-900 text-xs leading-none font-semibold">UPLOAD DOCUMENT</a>
                                    @if($lead->status === 'not_reachable')
                                        <button type="button" onclick="openDeleteModal({{ $lead->id }}, '{{ $lead->name }}', {{ (in_array($lead->priority, ['high', 'urgent']) || ($lead->estimated_value && $lead->estimated_value >= 100000)) ? 'true' : 'false' }})" class="text-red-600 hover:text-red-900 text-xs leading-none bg-transparent border-none p-0">Delete</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="px-3 py-4 text-center text-gray-500">No leads found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $leads->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Excel Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Import Leads from Excel</h3>
            
            <!-- Demo File Download Section -->
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-medium text-green-900 mb-1">Need a sample file?</h4>
                        <p class="text-xs text-green-700">Download our demo CSV template</p>
                    </div>
                    <a href="{{ route('leads.download-template') }}" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Template
                    </a>
                </div>
            </div>
            
            <form method="POST" action="{{ route('leads.import') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="excel_file" class="block text-sm font-medium text-gray-700 mb-2">Select Excel File *</label>
                    <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <p class="text-xs text-gray-500 mt-1">Supported formats: .xlsx, .xls, .csv</p>
                </div>
                
                <div class="mb-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Required Columns:</h4>
                        <ul class="text-xs text-blue-800 space-y-1">
                            <li>• Name (required)</li>
                            <li>• Phone (required)</li>
                            <li>• Email (optional)</li>
                            <li>• Company (optional)</li>
                            <li>• Source (required)</li>
                            <li>• Status (required)</li>
                            <li>• Priority (required)</li>
                        </ul>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeImportModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                        Import Leads
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    // Reset form
    document.querySelector('#importModal form').reset();
}

// Close modal when clicking outside
document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImportModal();
    }
});

function openDeleteModal(leadId, leadName) {
    document.getElementById('deleteLeadName').textContent = leadName;
    const form = document.getElementById('deleteForm');
    form.action = '{{ url("/leads") }}/' + leadId;
    // Clear the reason field
    document.getElementById('delete_reason').value = '';
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteForm').reset();
}

// Close delete modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Reveal contact/email via AJAX and update both phone and email spans
function revealContactAjax(leadId, element) {
    // element may be undefined; find phone/email spans
    const phoneSpan = document.getElementById('phone-blur-' + leadId);
    const emailSpan = document.getElementById('email-blur-' + leadId);

    // If already revealed, skip
    if ((phoneSpan && phoneSpan.getAttribute('data-revealed') === 'true') || (emailSpan && emailSpan.getAttribute('data-revealed') === 'true')) {
        return;
    }

    // Disable element if provided
    if (element && element.style) {
        element.style.pointerEvents = 'none';
        element.style.cursor = 'wait';
        element.style.opacity = '0.6';
    }

    fetch(`/leads/${leadId}/reveal-contact`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (phoneSpan && data.phone) {
                phoneSpan.textContent = data.phone;
                phoneSpan.style.filter = '';
                phoneSpan.style.webkitFilter = '';
                phoneSpan.setAttribute('data-revealed', 'true');
            }
            if (emailSpan && data.email) {
                emailSpan.textContent = data.email;
                emailSpan.style.filter = '';
                emailSpan.style.webkitFilter = '';
                emailSpan.setAttribute('data-revealed', 'true');
            }
        } else {
            alert(data.message || 'Failed to reveal contact/email.');
            if (element && element.style) {
                element.style.pointerEvents = 'auto';
                element.style.cursor = 'pointer';
                element.style.opacity = '1';
            }
        }
    })
    .catch(() => {
        alert('Failed to reveal contact/email.');
        if (element && element.style) {
            element.style.pointerEvents = 'auto';
            element.style.cursor = 'pointer';
            element.style.opacity = '1';
        }
    });
}

function updateSelectedRevisedQuotation(leadId, quotationId) {
    if (!quotationId) return;
    fetch(`/api/leads/${leadId}/select-revised-quotation`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({ quotation_id: quotationId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Failed to select revised quotation.');
        }
    })
    .catch(() => {
        alert('Failed to select revised quotation.');
    });
}
</script>

<!-- Delete Lead Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Lead</h3>
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete lead: <strong id="deleteLeadName"></strong>?</p>
            <p class="text-xs text-red-600 mb-2 font-semibold">Note: Only leads with status "Not Reachable" can be deleted.</p>
            @if(auth()->user()->hasRole('SUPER ADMIN'))
            <p class="text-xs text-yellow-600 mb-4">The lead will be backed up for 40 days and can be restored from Lead Backups.</p>
            @else
            <p class="text-xs text-yellow-600 mb-4" id="approvalMessage">This will require approval. The lead will be backed up for 40 days after approval.</p>
            @endif
            
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label for="delete_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Deletion @if(!auth()->user()->hasRole('SUPER ADMIN'))<span class="text-red-500">*</span>@endif</label>
                    <textarea id="delete_reason" name="reason" rows="3" @if(!auth()->user()->hasRole('SUPER ADMIN'))required minlength="10"@endif
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                              placeholder="Enter reason for deletion{{ !auth()->user()->hasRole('SUPER ADMIN') ? ' (minimum 10 characters)' : ' (optional)' }}..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        @if(auth()->user()->hasRole('SUPER ADMIN'))
                            Reason is optional for Super Admin.
                        @else
                            Please provide a detailed reason for deletion (minimum 10 characters).
                        @endif
                    </p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        @if(auth()->user()->hasRole('SUPER ADMIN'))
                            Delete Lead
                        @else
                            Request Deletion
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Select Quotation Modal -->
<div id="selectQuotationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="w-96 h-96 bg-white rounded-lg shadow-xl border border-gray-200 flex flex-col p-5">
        <div class="flex-1 overflow-hidden flex flex-col">
            <h3 class="text-base font-semibold text-gray-900 mb-2">Select Quotation</h3>
            <p class="text-xs text-gray-600 mb-3">For <strong id="selectedLeadName"></strong>:</p>
            
            <div id="quotationsList" class="flex-1 overflow-y-auto space-y-2 mb-3">
                <!-- Quotations will be loaded here -->
            </div>
        </div>
        
        <div class="flex justify-end gap-2 pt-3 border-t border-gray-200">
            <button type="button" onclick="closeSelectQuotationModal()" class="bg-gray-400 hover:bg-gray-500 text-white px-3 py-1.5 rounded text-sm">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
function openSelectQuotationModal(leadId, leadName) {
        window.currentSelectLeadId = leadId;
    const modal = document.getElementById('selectQuotationModal');
    const quotationsList = document.getElementById('quotationsList');
    const selectedLeadName = document.getElementById('selectedLeadName');
    
    if (!modal || !quotationsList || !selectedLeadName) {
        console.error('Modal elements not found');
        return;
    }
    
    selectedLeadName.textContent = leadName;
    quotationsList.innerHTML = '<p class="text-center text-gray-500">Loading quotations...</p>';
    
    // Fetch quotations for this lead
    fetch(`/api/leads/${leadId}/quotations-list`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Quotations data:', data);
            if (data.quotations && data.quotations.length > 0) {
                quotationsList.innerHTML = data.quotations.map(quotation => `
                    <div class="p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer" onclick="selectQuotationForRevision(${quotation.id})">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900">${quotation.quotation_number}</p>
                                <p class="text-xs text-gray-600">Date: ${new Date(quotation.quotation_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</p>
                                <p class="text-xs text-gray-600">Type: ${quotation.quotation_type.replace(/_/g, ' ').toUpperCase()}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">₹${parseFloat(quotation.total_amount).toLocaleString('en-IN')}</p>
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-medium ${quotation.status === 'accepted' ? 'bg-green-100 text-green-800' : quotation.status === 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'}">${quotation.status}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                quotationsList.innerHTML = '<p class="text-center text-gray-500">No quotations found for this lead.</p>';
            }
        })
        .catch(error => {
            console.error('Error loading quotations:', error);
            quotationsList.innerHTML = '<p class="text-center text-red-500">Error loading quotations. Please refresh and try again.</p>';
        });
    
    modal.classList.remove('hidden');
}

function closeSelectQuotationModal() {
    const modal = document.getElementById('selectQuotationModal');
    modal.classList.add('hidden');
}

function selectQuotationForRevision(quotationId) {
    // Save the selected revised quotation for the lead via AJAX
    if (window.currentSelectLeadId) {
        fetch(`/api/leads/${window.currentSelectLeadId}/select-revised-quotation`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ quotation_id: quotationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Failed to select revised quotation.');
            }
        })
        .catch(() => {
            alert('Failed to select revised quotation.');
        });
    }
}
</script>
<script>
function revealContact(leadId, element) {
    // Check if already revealed
    if (element.getAttribute('data-revealed') === 'true') {
        return;
    }
    // Prevent multiple clicks
    element.style.pointerEvents = 'none';
    element.style.cursor = 'wait';
    element.style.opacity = '0.6';
    // Make AJAX request to reveal contact
    fetch(`/leads/${leadId}/reveal-contact`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.phone) {
            const contactContainer = document.getElementById('contact-container-' + leadId);
            if (contactContainer) {
                contactContainer.innerHTML = `<div class="text-xs text-gray-900 truncate" title="${data.phone}">${data.phone.length > 12 ? data.phone.substring(0, 12) + '...' : data.phone}</div>`;
            }
        } else {
            alert(data.message || 'Failed to reveal contact/email.');
            element.style.pointerEvents = 'auto';
            element.style.cursor = 'pointer';
            element.style.opacity = '1';
        }
    })
    .catch(error => {
        alert('Failed to reveal contact/email.');
        element.style.pointerEvents = 'auto';
        element.style.cursor = 'pointer';
        element.style.opacity = '1';
    });
}
</script>
@endsection
