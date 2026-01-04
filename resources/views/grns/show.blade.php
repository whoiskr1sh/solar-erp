@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">GRN Details</h1>
                    <p class="text-gray-600 mt-1">{{ $grn->grn_number }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('grns.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to GRNs
                    </a>
                    <a href="{{ route('grns.edit', $grn) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit GRN
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">GRN Status</h3>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $grn->status_badge }} mt-2">
                        {{ ucfirst($grn->status) }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">GRN Number</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $grn->grn_number }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">GRN Date</label>
                        <p class="text-sm text-gray-900">{{ $grn->grn_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Received Date</label>
                        <p class="text-sm text-gray-900">{{ $grn->received_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vendor</label>
                        <p class="text-sm text-gray-900">{{ $grn->vendor?->company ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $grn->vendor?->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Purchase Order</label>
                        <p class="text-sm text-gray-900">{{ $grn->purchaseOrder?->po_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Project</label>
                        <p class="text-sm text-gray-900">{{ $grn->project?->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Financial Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                        <p class="text-lg font-semibold text-gray-900">₹{{ number_format($grn->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tax Amount</label>
                        <p class="text-sm text-gray-900">₹{{ number_format($grn->tax_amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Discount Amount</label>
                        <p class="text-sm text-gray-900">₹{{ number_format($grn->discount_amount, 2) }}</p>
                    </div>
                    <div class="border-t pt-4">
                        <label class="block text-sm font-medium text-gray-700">Final Amount</label>
                        <p class="text-xl font-bold text-teal-600">₹{{ number_format($grn->final_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Information -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700">Delivery Address</label>
                <p class="text-sm text-gray-900 mt-1">{{ $grn->delivery_address }}</p>
            </div>
        </div>

        <!-- Notes -->
        @if($grn->notes)
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
            <p class="text-sm text-gray-900">{{ $grn->notes }}</p>
        </div>
        @endif

        <!-- People Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">People</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Received By</label>
                    <p class="text-sm text-gray-900">{{ $grn->receivedBy?->name ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">{{ $grn->created_at->format('M d, Y H:i') }}</p>
                </div>
                @if($grn->verifiedBy)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Verified By</label>
                    <p class="text-sm text-gray-900">{{ $grn->verifiedBy->name }}</p>
                    <p class="text-xs text-gray-500">{{ $grn->verified_at?->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    <a href="{{ route('grns.edit', $grn) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit GRN
                    </a>
                    @if($grn->status === 'received')
                    <form method="POST" action="{{ route('grns.verify', $grn) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-check mr-2"></i>Verify GRN
                        </button>
                    </form>
                    @endif
                </div>
                <form method="POST" action="{{ route('grns.destroy', $grn) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this GRN?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Delete GRN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
