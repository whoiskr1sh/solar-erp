@extends('layouts.app')

@section('title', 'Delete Approval Requests')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Delete Approval Requests</h1>
            <p class="mt-2 text-gray-600">Review and approve/reject deletion requests</p>
        </div>
    </div>

    <!-- Pending Requests -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Pending Deletion Requests</h2>
            
            @if($deleteApprovals->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested By</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($deleteApprovals as $approval)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $approval->model_name }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ class_basename($approval->getCorrectedModelType()) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $approval->requester->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $approval->requester->email ?? '' }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $approval->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $approval->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="text-sm text-gray-900">{{ Str::limit($approval->reason ?? 'No reason provided', 50) }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    @if($approval->getCorrectedModelType() === \App\Models\Lead::class)
                                    <a href="{{ route('admin.delete-approval.view', $approval->id) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                        View Lead
                                    </a>
                                    @endif
                                    @php
                                        $isLead = $approval->getCorrectedModelType() === \App\Models\Lead::class;
                                        $canApprove = auth()->user()->hasRole('SUPER ADMIN') || ($isLead && auth()->user()->hasRole('SALES MANAGER'));
                                        $canReject = auth()->user()->hasRole('SUPER ADMIN') || ($isLead && auth()->user()->hasRole('SALES MANAGER'));
                                    @endphp
                                    @if($canApprove)
                                    <a href="{{ route('admin.delete-approval.approve', $approval->id) }}" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs"
                                       onclick="return confirm('Are you sure you want to approve this deletion? The item will be deleted{{ $isLead ? ' and backed up for 40 days' : '' }}.')">
                                        Approve
                                    </a>
                                    @endif
                                    @if($canReject)
                                    <a href="{{ route('admin.delete-approval.reject', $approval->id) }}" 
                                       class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                        Reject
                                    </a>
                                    @endif
                                    @if(!$canApprove && !$canReject)
                                    <span class="text-xs text-gray-500">No action available</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $deleteApprovals->links() }}
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No pending requests</h3>
                <p class="mt-1 text-sm text-gray-500">There are no pending deletion requests at this time.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

