@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Edit Leave Request Status</h2>
                <p class="text-gray-600 mt-1">You can only update the status of this leave request</p>
            </div>

            <form action="{{ route('hr.leave-request.update', $leaveRequest->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Read-only fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                        <input type="text" value="{{ $leaveRequest->leave_type }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" value="{{ $leaveRequest->start_date->format('Y-m-d') }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" value="{{ $leaveRequest->end_date->format('Y-m-d') }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                        <textarea rows="4" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">{{ $leaveRequest->reason }}</textarea>
                    </div>

                    <!-- Editable Status Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required onchange="toggleRejectionReason()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="pending" {{ $leaveRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $leaveRequest->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $leaveRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rejection Reason Field (shown only when status is rejected) -->
                    <div id="rejectionReasonDiv" @if($leaveRequest->status != 'rejected') style="display: none;" @endif>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason <span class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a reason for rejection...">{{ $leaveRequest->comments ?? '' }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Please provide a reason why this leave request is being rejected.</p>
                        @error('rejection_reason')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hidden fields to preserve original values -->
                    <input type="hidden" name="leave_type" value="{{ $leaveRequest->leave_type }}">
                    <input type="hidden" name="start_date" value="{{ $leaveRequest->start_date->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $leaveRequest->end_date->format('Y-m-d') }}">
                    <input type="hidden" name="reason" value="{{ $leaveRequest->reason }}">
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'hr-manager'))]) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleRejectionReason() {
    const statusSelect = document.getElementById('status');
    const rejectionReasonDiv = document.getElementById('rejectionReasonDiv');
    const rejectionReasonField = document.getElementById('rejection_reason');
    
    if (statusSelect.value === 'rejected') {
        rejectionReasonDiv.style.display = 'block';
        rejectionReasonField.required = true;
    } else {
        rejectionReasonDiv.style.display = 'none';
        rejectionReasonField.required = false;
        rejectionReasonField.value = ''; // Clear the field when not rejected
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleRejectionReason();
});
</script>
@endsection
