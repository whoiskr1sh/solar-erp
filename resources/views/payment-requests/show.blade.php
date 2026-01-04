@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $paymentRequest->pr_number }}</h1>
                    <p class="text-gray-600 mt-1">Payment Request Details</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('payment-requests.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                    @if($paymentRequest->status === 'draft')
                        <a href="{{ route('payment-requests.edit', $paymentRequest) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PR Number</label>
                            <p class="text-gray-900 font-medium">{{ $paymentRequest->pr_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                            <p class="text-gray-900">{{ $paymentRequest->vendor->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                            <p class="text-gray-900">{{ $paymentRequest->project?->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentRequest->payment_type_badge }}">
                                {{ ucfirst($paymentRequest->payment_type) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $paymentRequest->status_badge }}">
                                {{ ucfirst($paymentRequest->status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                            <p class="text-gray-900 font-medium">₹{{ number_format($paymentRequest->amount, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Request Date</label>
                            <p class="text-gray-900">{{ $paymentRequest->request_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <p class="text-gray-900 {{ $paymentRequest->is_overdue ? 'text-red-600' : '' }}">
                                {{ $paymentRequest->due_date->format('M d, Y') }}
                                @if($paymentRequest->is_overdue)
                                    <span class="text-xs text-red-600 ml-2">(Overdue)</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <p class="text-gray-900">{{ $paymentRequest->description }}</p>
                    </div>
                    
                    @if($paymentRequest->justification)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Justification</label>
                            <p class="text-gray-900">{{ $paymentRequest->justification }}</p>
                        </div>
                    @endif
                </div>

                <!-- Invoice Information -->
                @if($paymentRequest->invoice_number || $paymentRequest->invoice_date || $paymentRequest->invoice_amount)
                    <div class="bg-white rounded-lg shadow-sm border p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if($paymentRequest->invoice_number)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Number</label>
                                    <p class="text-gray-900">{{ $paymentRequest->invoice_number }}</p>
                                </div>
                            @endif
                            
                            @if($paymentRequest->invoice_date)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Date</label>
                                    <p class="text-gray-900">{{ $paymentRequest->invoice_date->format('M d, Y') }}</p>
                                </div>
                            @endif
                            
                            @if($paymentRequest->invoice_amount)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Amount</label>
                                    <p class="text-gray-900">₹{{ number_format($paymentRequest->invoice_amount, 2) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($paymentRequest->status === 'draft')
                            <form method="POST" action="{{ route('payment-requests.submit', $paymentRequest) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-paper-plane mr-2"></i>Submit for Approval
                                </button>
                            </form>
                        @endif
                        
                        @if($paymentRequest->status === 'submitted')
                            <form method="POST" action="{{ route('payment-requests.approve', $paymentRequest) }}" class="w-full">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Approval Notes</label>
                                    <textarea name="approval_notes" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                              placeholder="Optional approval notes..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i>Approve
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('payment-requests.reject', $paymentRequest) }}" class="w-full">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                                    <textarea name="rejection_reason" rows="3" required
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                                              placeholder="Please provide reason for rejection..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Reject
                                </button>
                            </form>
                        @endif
                        
                        @if($paymentRequest->status === 'approved')
                            <form method="POST" action="{{ route('payment-requests.mark-paid', $paymentRequest) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-check-circle mr-2"></i>Mark as Paid
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Request Details -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Details</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Requested By</label>
                            <p class="text-gray-900">{{ $paymentRequest->requester->name }}</p>
                        </div>
                        
                        @if($paymentRequest->approver)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                                <p class="text-gray-900">{{ $paymentRequest->approver->name }}</p>
                            </div>
                        @endif
                        
                        @if($paymentRequest->approved_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approved At</label>
                                <p class="text-gray-900">{{ $paymentRequest->approved_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($paymentRequest->approval_notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Approval Notes</label>
                                <p class="text-gray-900">{{ $paymentRequest->approval_notes }}</p>
                            </div>
                        @endif
                        
                        @if($paymentRequest->rejection_reason)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                                <p class="text-gray-900 text-red-600">{{ $paymentRequest->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
