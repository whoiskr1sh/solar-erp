@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Reject Deletion Request</h2>
            <p class="text-gray-600 mt-2">{{ $deleteApproval->model_name }}</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="space-y-2 text-sm">
                <div><strong>Item:</strong> {{ $deleteApproval->model_name }}</div>
                <div><strong>Type:</strong> {{ class_basename($deleteApproval->model_type) }}</div>
                <div><strong>Requested By:</strong> {{ $deleteApproval->requester->name ?? 'Unknown' }}</div>
                <div><strong>Requested At:</strong> {{ $deleteApproval->created_at->format('d M, Y h:i A') }}</div>
                @if($deleteApproval->reason)
                <div><strong>Reason:</strong> {{ $deleteApproval->reason }}</div>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.delete-approval.reject.post', $deleteApproval->id) }}{{ request()->get('from') === 'email' ? '?from=email' : '' }}" method="POST">
            @csrf
            @if(request()->get('from') === 'email')
                <input type="hidden" name="from" value="email">
            @endif
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason (Optional)</label>
                <textarea name="rejection_reason" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Please provide a reason for rejection..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('dashboard') }}" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Reject Deletion Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


