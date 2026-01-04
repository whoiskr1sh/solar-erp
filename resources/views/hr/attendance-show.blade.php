@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Attendance Details</h1>
            <p class="text-gray-600 mt-1">View full information for this attendance record.</p>
        </div>
        <a href="{{ route('hr.attendance') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Back to Attendance
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Employee ID</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $attendance->employee_id }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Date</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $attendance->date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Check In</p>
                <p class="text-base text-gray-900 mt-1">{{ $attendance->check_in ? $attendance->check_in->format('h:i A') : '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Check Out</p>
                <p class="text-base text-gray-900 mt-1">{{ $attendance->check_out ? $attendance->check_out->format('h:i A') : '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Hours</p>
                <p class="text-base text-gray-900 mt-1">{{ $attendance->total_hours ? $attendance->total_hours . ' hrs' : '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status</p>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1 {{ $attendance->status_badge }}">
                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                </span>
            </div>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Remarks</p>
            <p class="text-sm text-gray-900">{{ $attendance->remarks ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection
