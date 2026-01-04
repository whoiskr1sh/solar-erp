@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Purchase Orders</h2>
                        <p class="text-gray-600 mt-1">Manage purchase orders and vendor transactions</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('purchase-orders.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            Dashboard
                        </a>
                        <a href="{{ route('purchase-orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create PO
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Filters & Search</h3>
            </div>
            
            <!-- Quick Filters -->
            <div class="flex flex-wrap gap-2 mb-4">
                <a href="{{ route('purchase-orders.index') }}" class="px-3 py-1 text-sm rounded-full {{ !request()->hasAny(['status', 'vendor_id', 'project_id', 'search']) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All POs
                </a>
                <a href="{{ route('purchase-orders.index', ['status' => 'draft']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') == 'draft' ? 'bg-gray-100 text-gray-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Draft
                </a>
                <a href="{{ route('purchase-orders.index', ['status' => 'sent']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') == 'sent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Sent
                </a>
                <a href="{{ route('purchase-orders.index', ['status' => 'acknowledged']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') == 'acknowledged' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Acknowledged
                </a>
                <a href="{{ route('purchase-orders.index', ['status' => 'received']) }}" class="px-3 py-1 text-sm rounded-full {{ request('status') == 'received' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Received
                </a>
            </div>
            
            <!-- Search Bar -->
            <div class="mb-4">
                <div class="flex space-x-3">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by PO number, vendor, or project..." class="block w-full pl-10 pr-3 py-2 px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @if(request()->hasAny(['vendor_id', 'project_id', 'status', 'search']))
                    <a href="{{ route('purchase-orders.index') }}" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear All Filters
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Advanced Filters -->
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                    <select name="vendor_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Vendors</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Project</label>
                    <select name="project_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="acknowledged" {{ request('status') == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                        <option value="partially_received" {{ request('status') == 'partially_received' ? 'selected' : '' }}>Partially Received</option>
                        <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="md:col-span-3 flex justify-end space-x-3">
                    <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Clear All
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-blue-700">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Purchase Orders Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">PO Details</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Vendor</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Amount</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Delivery</th>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($purchaseOrders as $po)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-900">{{ $po->po_number }}</div>
                                <div class="text-xs text-gray-500">{{ $po->po_date->format('M d') }}</div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-900">{{ Str::limit($po->vendor->name, 20) }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($po->vendor->email, 15) }}</div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="text-xs font-medium text-gray-900">₹{{ number_format($po->final_amount, 0) }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($po->payment_terms, 8) }}</div>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $po->status_badge }}">
                                    {{ Str::limit(ucfirst(str_replace('_', ' ', $po->status)), 8) }}
                                </span>
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap">
                                <div class="text-xs text-gray-900">{{ $po->expected_delivery_date->format('M d') }}</div>
                                @if($po->is_overdue)
                                    <span class="text-xs text-red-600">Overdue</span>
                                @elseif($po->days_until_delivery <= 3)
                                    <span class="text-xs text-yellow-600">Due Soon</span>
                                @endif
                            </td>
                            <td class="px-2 py-2 whitespace-nowrap text-xs font-medium">
                                <div class="flex flex-col space-y-1">
                                    <a href="{{ route('purchase-orders.show', $po) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    @if($po->status === 'draft')
                                        <a href="{{ route('purchase-orders.edit', $po) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form method="POST" action="{{ route('purchase-orders.approve', $po) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('purchase-orders.destroy', $po) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this PO?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">No Purchase Orders found</p>
                                    <p class="text-sm">Get started by creating your first purchase order.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($purchaseOrders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $purchaseOrders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

