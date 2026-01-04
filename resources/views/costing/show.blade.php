@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Costing Details</h1>
            <p class="text-gray-600">{{ $costing->costing_number }} - {{ $costing->project_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('costing.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Costings
            </a>
            @if($costing->status === 'draft')
            <a href="{{ route('costing.edit', $costing) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Edit Costing
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Project Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project Name</label>
                        <p class="text-gray-900">{{ $costing->project_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Linked Project</label>
                        <p class="text-gray-900">{{ $costing->project ? $costing->project->name : 'Not linked' }}</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project Description</label>
                        <p class="text-gray-900">{{ $costing->project_description ?: 'No description provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <p class="text-gray-900">{{ $costing->location ?: 'Not specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Validity Date</label>
                        <p class="text-gray-900">{{ $costing->validity_date ? $costing->validity_date->format('M d, Y') : 'Not set' }}</p>
                    </div>
                </div>
            </div>

            <!-- Client Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                        <p class="text-gray-900">{{ $costing->client_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client Email</label>
                        <p class="text-gray-900">{{ $costing->client_email ?: 'Not provided' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client Phone</label>
                        <p class="text-gray-900">{{ $costing->client_phone ?: 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Cost Breakdown -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cost Breakdown</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Material Cost:</span>
                            <span class="text-sm font-medium">{{ $costing->formatted_material_cost }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Labor Cost:</span>
                            <span class="text-sm font-medium">{{ $costing->formatted_labor_cost }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Equipment Cost:</span>
                            <span class="text-sm font-medium">{{ $costing->formatted_equipment_cost }}</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Transportation Cost:</span>
                            <span class="text-sm font-medium">{{ $costing->formatted_transportation_cost }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Overhead Cost:</span>
                            <span class="text-sm font-medium">{{ $costing->formatted_overhead_cost }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Discount:</span>
                            <span class="text-sm font-medium text-red-600">-{{ $costing->formatted_discount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit & Tax -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Profit & Tax</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profit Margin</label>
                        <p class="text-gray-900">{{ $costing->profit_margin }}%</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rate</label>
                        <p class="text-gray-900">{{ $costing->tax_rate }}%</p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($costing->notes)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                <p class="text-gray-900">{{ $costing->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status & Actions</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Status</label>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $costing->status_badge }}">
                            {{ ucfirst($costing->status) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <p class="text-gray-900">{{ $costing->currency }}</p>
                    </div>
                    
                    @if($costing->is_expired)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-sm text-red-800">⚠️ This costing has expired</p>
                    </div>
                    @endif
                    
                    <div class="pt-4 border-t border-gray-200">
                        @if($costing->status === 'pending')
                        <form method="POST" action="{{ route('costing.approve', $costing) }}" class="mb-3">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                                Approve Costing
                            </button>
                        </form>
                        <form method="POST" action="{{ route('costing.reject', $costing) }}">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                Reject Costing
                            </button>
                        </form>
                        @endif
                        
                    </div>
                </div>
            </div>

            <!-- Cost Summary -->
            <div class="bg-teal-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cost Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Subtotal:</span>
                        <span class="text-sm font-medium">{{ $costing->formatted_total_cost }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Profit:</span>
                        <span class="text-sm font-medium">{{ $costing->formatted_total_cost }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tax:</span>
                        <span class="text-sm font-medium">{{ $costing->formatted_total_cost }}</span>
                    </div>
                    <hr class="border-gray-300">
                    <div class="flex justify-between">
                        <span class="text-lg font-semibold text-gray-900">Total Cost:</span>
                        <span class="text-lg font-bold text-teal-600">{{ $costing->formatted_total_cost }}</span>
                    </div>
                </div>
            </div>

            <!-- Created By -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Created By</h3>
                
                <div class="space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Creator</label>
                        <p class="text-gray-900">{{ $costing->creator->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Created At</label>
                        <p class="text-gray-900">{{ $costing->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    
                    @if($costing->approver)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                        <p class="text-gray-900">{{ $costing->approver->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Approved At</label>
                        <p class="text-gray-900">{{ $costing->approved_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
