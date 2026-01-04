@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-6">
        <div class="col-12">
            <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Delete Purchase Order</h1>
                        <p class="text-red-100 text-lg">Confirm deletion of purchase order</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('purchase-manager.purchase-orders.show', $purchaseOrder) }}" class="bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-red-50 transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Warning Section -->
    <div class="row mb-6">
        <div class="col-12">
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-red-800">Warning: This action cannot be undone</h3>
                        <p class="mt-2 text-sm text-red-700">
                            You are about to permanently delete Purchase Order #{{ $purchaseOrder->id }}. This will remove all associated data including items and vendor information. Please make sure you have backed up any important data before proceeding.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Information -->
    <div class="row mb-6">
        <div class="col-lg-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Purchase Order Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Purchase Order Number</label>
                        <p class="text-lg font-semibold text-gray-900">PO-{{ $purchaseOrder->id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($purchaseOrder->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($purchaseOrder->status == 'approved') bg-green-100 text-green-800
                            @elseif($purchaseOrder->status == 'rejected') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($purchaseOrder->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Vendor</label>
                        <p class="text-gray-900">{{ $purchaseOrder->vendor->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Order Date</label>
                        <p class="text-gray-900">{{ $purchaseOrder->order_date ? $purchaseOrder->order_date->format('M d, Y') : 'Not set' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Expected Delivery</label>
                        <p class="text-gray-900">{{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('M d, Y') : 'Not set' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Total Amount</label>
                        <p class="text-lg font-semibold text-gray-900">₹{{ number_format($purchaseOrder->items->sum('total_price'), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Impact Summary</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span class="text-sm text-gray-700">Purchase Order will be permanently deleted</span>
                    </div>
                    
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span class="text-sm text-gray-700">{{ $purchaseOrder->items->count() }} items will be removed</span>
                    </div>
                    
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-700">All associated records will be lost</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Form -->
    <div class="row">
        <div class="col-12">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('purchase-manager.purchase-orders.delete', $purchaseOrder) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="approve_delete" value="1" required class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">
                                I understand that this action cannot be undone and I want to permanently delete Purchase Order #{{ $purchaseOrder->id }}
                            </span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('purchase-manager.purchase-orders.show', $purchaseOrder) }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Purchase Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
