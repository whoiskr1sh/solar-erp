@extends('layouts.app')

@section('title', 'Daily Progress Report - ' . $dpr->project->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Daily Progress Report</h1>
                    <p class="mt-2 text-gray-600">{{ $dpr->project->name }} - {{ $dpr->report_date->format('M d, Y') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('dpr.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to DPR
                    </a>
                    @if($dpr->status === 'pending' && $dpr->submitted_by === auth()->id())
                        <a href="{{ route('dpr.edit', $dpr) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit DPR
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="px-6 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Status Banner -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $dpr->status_badge }}">
                            {{ ucfirst($dpr->status) }}
                        </span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $dpr->weather_badge }}">
                            {{ ucfirst($dpr->weather_condition) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500">
                        Submitted by {{ $dpr->submittedBy->name }} on {{ $dpr->created_at->format('M d, Y g:i A') }}
                    </div>
                </div>
            </div>

            <!-- Project Information -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Project Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Project</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $dpr->project->name }}</p>
                        <p class="text-sm text-gray-500">{{ $dpr->project->project_code }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Report Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $dpr->report_date->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Work Details -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Work Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Work Hours</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $dpr->work_hours }} hours</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Workers Present</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $dpr->workers_present }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Weather Condition</label>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($dpr->weather_condition) }}</p>
                    </div>
                </div>
            </div>

            <!-- Work Performed -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Work Performed</h3>
                <div class="prose max-w-none">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $dpr->work_performed }}</p>
                </div>
            </div>

            <!-- Materials & Equipment -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Materials & Equipment</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Materials Used</label>
                        <div class="text-gray-900 whitespace-pre-wrap">{{ $dpr->materials_used ?: 'Not specified' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Equipment Used</label>
                        <div class="text-gray-900 whitespace-pre-wrap">{{ $dpr->equipment_used ?: 'Not specified' }}</div>
                    </div>
                </div>
            </div>

            <!-- Challenges & Next Day Plan -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Challenges & Planning</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Challenges Faced</label>
                        <div class="text-gray-900 whitespace-pre-wrap">{{ $dpr->challenges_faced ?: 'No challenges reported' }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Next Day Plan</label>
                        <div class="text-gray-900 whitespace-pre-wrap">{{ $dpr->next_day_plan ?: 'No plan specified' }}</div>
                    </div>
                </div>
            </div>

            <!-- Photos -->
            @if($dpr->photos && count($dpr->photos) > 0)
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Photos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($dpr->photos as $photo)
                    <div class="relative">
                        <img src="{{ Storage::url($photo) }}" alt="DPR Photo" class="w-full h-48 object-cover rounded-lg">
                        <a href="{{ Storage::url($photo) }}" target="_blank" class="absolute top-2 right-2 bg-black bg-opacity-50 text-white p-1 rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Approval Section -->
            @if($dpr->status === 'pending' && auth()->user()->can('approve', $dpr))
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Actions</h3>
                
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-red-800">Please correct the following errors:</h4>
                                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="max-w-md">
                    <form action="{{ route('dpr.approve', $dpr) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="approve_remarks" class="block text-sm font-medium text-gray-700 mb-2">Approval Remarks (Optional)</label>
                            <textarea name="remarks" id="approve_remarks" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Add approval remarks...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                            Approve DPR
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Approval Information -->
            @if($dpr->status !== 'pending')
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Approved/Rejected By</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $dpr->approvedBy->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $dpr->approved_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
                @if($dpr->remarks)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-500 mb-2">Remarks</label>
                    <div class="text-gray-900 whitespace-pre-wrap">{{ $dpr->remarks }}</div>
                </div>
                @endif
            </div>
            @endif

            <!-- Additional Remarks -->
            @if($dpr->remarks && $dpr->status === 'pending')
            <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Remarks</h3>
                <div class="text-gray-900 whitespace-pre-wrap">{{ $dpr->remarks }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

