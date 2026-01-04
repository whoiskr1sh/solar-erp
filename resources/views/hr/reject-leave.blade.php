@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Reject Leave Request</h2>
            <p class="text-gray-600 mt-2">Employee ID: {{ $leaveRequest->employee_id }}</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="space-y-2 text-sm">
                <div><strong>Leave Type:</strong> {{ $leaveRequest->leave_type }}</div>
                <div><strong>Start Date:</strong> {{ $leaveRequest->start_date->format('d M, Y') }}</div>
                <div><strong>End Date:</strong> {{ $leaveRequest->end_date->format('d M, Y') }}</div>
                <div><strong>Total Days:</strong> {{ $leaveRequest->total_days }}</div>
                <div><strong>Reason:</strong> {{ $leaveRequest->reason }}</div>
            </div>
        </div>

        <form action="{{ route('hr.leave-request.reject', $leaveRequest->id) }}{{ request()->get('from') === 'email' ? '?from=email' : '' }}" method="POST">
            @csrf
            @if(request()->get('from') === 'email')
                <input type="hidden" name="from" value="email">
            @endif
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea name="rejection_reason" id="rejection_reason" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a reason for rejection..."></textarea>
                <p class="text-sm text-gray-500 mt-1">Please provide a reason why this leave request is being rejected.</p>
                @error('rejection_reason')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'hr-manager'))]) }}" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Reject Leave Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

