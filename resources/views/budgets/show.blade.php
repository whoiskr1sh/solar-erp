@extends('layouts.app')

@section('title', 'View Budget')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Budget Details</h1>
            <p class="text-gray-600">{{ $budget->title }} - {{ $budget->budget_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('budgets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Budgets
            </a>
            <a href="{{ route('budgets.edit', $budget) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Edit Budget
            </a>
        </div>
    </div>

    <!-- Budget Overview -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $budget->status_badge }}">
                    {{ ucfirst($budget->status) }}
                </span>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full" style="background-color: {{ $budget->category->color }}20; color: {{ $budget->category->color }};">
                    {{ $budget->category->name }}
                </span>
            </div>
            <div class="text-sm text-gray-500">
                Created {{ $budget->created_at->diffForHumans() }}
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <h3 class="text-sm font-medium text-blue-600 uppercase mb-2">Budget Amount</h3>
                <p class="text-2xl font-bold text-blue-800">{{ $budget->formatted_budget_amount }}</p>
            </div>
            
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <h3 class="text-sm font-medium text-green-600 uppercase mb-2">Actual Amount</h3>
                <p class="text-2xl font-bold text-green-800">{{ $budget->formatted_actual_amount }}</p>
            </div>
            
            <div class="text-center p-4 {{ $budget->variance_amount >= 0 ? 'bg-red-50' : 'bg-green-50' }} rounded-lg">
                <h3 class="text-sm font-medium {{ $budget->variance_amount >= 0 ? 'text-red-600' : 'text-green-600' }} uppercase mb-2">Variance</h3>
                <p class="text-2xl font-bold {{ $budget->variance_amount >= 0 ? 'text-red-800' : 'text-green-800' }}">
                    {{ $budget->variance_amount >= 0 ? '+' : '' }}{{ $budget->variance_amount >= 0 ? number_format($budget->variance_amount) : number_format($budget->variance_amount) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Budget Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Budget Information</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-700">Budget Number:</span>
                    <span class="font-medium text-gray-900">{{ $budget->budget_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Period:</span>
                    <span class="font-medium text-gray-900">{{ ucfirst($budget->budget_period) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Currency:</span>
                    <span class="font-medium text-gray-900">{{ $budget->currency }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Start Date:</span>
                    <span class="font-medium text-gray-900">{{ $budget->start_date->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">End Date:</span>
                    <span class="font-medium text-gray-900">{{ $budget->end_date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Project & Category -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Categories & Project</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category:</label>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full" style="background-color: {{ $budget->category->color }}20; color: {{ $budget->category->color }};">
                        {{ $budget->category->name }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project:</label>
                    <span class="font-medium text-gray-900">{{ $budget->project->name ?? 'No project assigned' }}</span>
                    @if($budget->project)
                        <p class="text-sm text-gray-500">{{ $budget->project->client_name }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Created By:</label>
                    <span class="font-medium text-gray-900">{{ $budget->creator->name }}</span>
                </div>
                @if($budget->approver)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Approved By:</label>
                    <span class="font-medium text-gray-900">{{ $budget->approver->name }}</span>
                    <p class="text-sm text-gray-500">{{ $budget->approved_at->format('M d, Y') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Financial Details -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Financial Details</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-700">Budget Amount:</span>
                    <span class="font-medium text-gray-900">{{ $budget->formatted_budget_amount }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Actual Amount:</span>
                    <span class="font-medium text-gray-900">{{ $budget->formatted_actual_amount }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Variance Amount:</span>
                    <span class="font-medium {{ $budget->variance_amount >= 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $budget->variance_amount >= 0 ? '+' : '' }}{{ number_format($budget->variance_amount) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Variance Percentage:</span>
                    <span class="font-medium {{ $budget->variance_percentage >= 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $budget->variance_percentage >= 0 ? '+' : '' }}{{ $budget->variance_percentage }}%
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Progress:</span>
                    <span class="font-medium text-gray-900">{{ $budget->progress_percentage }}%</span>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Budget Progress</span>
                        <span>{{ $budget->progress_percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $budget->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Status Information</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-700">Status:</span>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $budget->status_badge }}">
                        {{ ucfirst($budget->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Is Approved:</span>
                    <span class="font-medium text-gray-900">{{ $budget->is_approved ? 'Yes' : 'No' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Created:</span>
                    <span class="font-medium text-gray-900">{{ $budget->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Updated:</span>
                    <span class="font-medium text-gray-900">{{ $budget->updated_at->format('M d, Y') }}</span>
                </div>
                @if($budget->approved_at)
                <div class="flex justify-between">
                    <span class="text-gray-700">Approved At:</span>
                    <span class="font-medium text-gray-900">{{ $budget->approved_at->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($budget->description || $budget->notes)
    <!-- Description & Notes -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Description & Notes</h3>
        <div class="space-y-4">
            @if($budget->description)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description:</label>
                <p class="text-gray-900">{{ $budget->description }}</p>
            </div>
            @endif
            @if($budget->notes)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes:</label>
                <p class="text-gray-900">{{ $budget->notes }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('budgets.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-300">
            Back to Budgets
        </a>
        <a href="{{ route('budgets.edit', $budget) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg transition duration-300">
            Edit Budget
        </a>
        @if($budget->status === 'pending')
        <form method="POST" action="{{ route('budgets.approve', $budget) }}" class="inline">
            @csrf
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-300">
                Approve Budget
            </button>
        </form>
        <form method="POST" action="{{ route('budgets.reject', $budget) }}" class="inline">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition duration-300">
                Reject Budget
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
