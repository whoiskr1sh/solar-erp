@extends('layouts.app')

@section('title', 'Material Consumption Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $materialConsumption->consumption_number }}</h1>
            <p class="text-gray-600">{{ $materialConsumption->activity_description }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('material-consumptions.edit', $materialConsumption) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Edit Consumption
            </a>
            <a href="{{ route('material-consumptions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Consumptions
            </a>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Status</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $materialConsumption->consumption_status_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $materialConsumption->consumption_status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Quality</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $materialConsumption->quality_status_badge }}">
                        {{ ucfirst($materialConsumption->quality_status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Activity Type</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ ucfirst($materialConsumption->activity_type) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Work Phase</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                        {{ ucfirst($materialConsumption->work_phase) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Consumption Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Consumption Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Consumption Number</label>
                        <p class="text-gray-900 font-medium">{{ $materialConsumption->consumption_number }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                        <p class="text-gray-900">{{ $materialConsumption->material ? $materialConsumption->material->item_name : 'No Material' }}</p>
                        <p class="text-sm text-gray-500">{{ $materialConsumption->material ? $materialConsumption->material->description : '' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                        <p class="text-gray-900">{{ $materialConsumption->project ? $materialConsumption->project->project_name : 'No Project Assigned' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Work Location</label>
                        <p class="text-gray-900">{{ $materialConsumption->work_location ?: 'Not specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Consumption Date</label>
                        <p class="text-gray-900">{{ $materialConsumption->consumption_date ? $materialConsumption->consumption_date->format('M d, Y') : 'Not set' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time Range</label>
                        <p class="text-gray-900">
                            @if($materialConsumption->start_time && $materialConsumption->end_time)
                                {{ $materialConsumption->start_time }} - {{ $materialConsumption->end_time }}
                            @else
                                Not specified
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Activity Description</label>
                    <p class="text-gray-900">{{ $materialConsumption->activity_description }}</p>
                </div>

                @if($materialConsumption->notes)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <p class="text-gray-900">{{ $materialConsumption->notes }}</p>
                </div>
                @endif

                @if($materialConsumption->quality_observations)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quality Observations</label>
                    <p class="text-gray-900">{{ $materialConsumption->quality_observations }}</p>
                </div>
                @endif
            </div>

            <!-- Documentation -->
            @if($materialConsumption->documentation_type || $materialConsumption->documentation_path)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Documentation</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($materialConsumption->documentation_type)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Documentation Type</label>
                        <p class="text-gray-900">{{ ucfirst($materialConsumption->documentation_type) }}</p>
                    </div>
                    @endif
                    
                    @if($materialConsumption->documentation_path)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Documentation Path</label>
                        <p class="text-gray-900">{{ $materialConsumption->documentation_path }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quantity & Cost Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quantity & Cost</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Quantity Consumed:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $materialConsumption->quantity_consumed }} {{ $materialConsumption->unit_of_measurement ?: 'units' }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Unit Cost:</span>
                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($materialConsumption->unit_cost, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Cost:</span>
                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($materialConsumption->total_cost, 2) }}</span>
                    </div>
                    
                    @if($materialConsumption->wastage_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Wastage Cost:</span>
                        <span class="text-sm font-medium text-red-600">₹{{ number_format($materialConsumption->wastage_cost, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($materialConsumption->cost_center)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Cost Center:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $materialConsumption->cost_center }}</span>
                    </div>
                    @endif
                </div>

                <!-- Efficiency Metrics -->
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Consumption Efficiency</span>
                        <span class="text-gray-900">{{ $materialConsumption->consumption_percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $materialConsumption->consumption_percentage }}%"></div>
                    </div>
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Wastage Rate</span>
                        <span class="text-gray-900">{{ $materialConsumption->wastage_percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: {{ $materialConsumption->wastage_percentage }}%"></div>
                    </div>
                    
                    @if($materialConsumption->return_percentage > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Return Rate</span>
                        <span class="text-gray-900">{{ $materialConsumption->return_percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $materialConsumption->return_percentage }}%"></div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- People -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">People</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Consumed By</label>
                        <p class="text-gray-900">{{ $materialConsumption->consumedBy ? $materialConsumption->consumedBy->name : 'Unknown' }}</p>
                        <p class="text-sm text-gray-500">{{ $materialConsumption->consumedBy ? $materialConsumption->consumedBy->designation : '' }}</p>
                    </div>
                    
                    @if($materialConsumption->supervisedBy)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supervised By</label>
                        <p class="text-gray-900">{{ $materialConsumption->supervisedBy->name }}</p>
                        <p class="text-sm text-gray-500">{{ $materialConsumption->supervisedBy->designation }}</p>
                    </div>
                    @endif
                    
                    @if($materialConsumption->approvedBy)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                        <p class="text-gray-900">{{ $materialConsumption->approvedBy->name }}</p>
                        <p class="text-sm text-gray-500">{{ $materialConsumption->approvedBy->designation }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if(in_array($materialConsumption->consumption_status, ['draft', 'in_progress']))
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-3">
                    @if($materialConsumption->consumption_status === 'draft')
                    <form method="POST" action="{{ route('material-consumptions.approve', $materialConsumption) }}" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Approve Consumption
                        </button>
                    </form>
                    @endif
                    
                    @if($materialConsumption->consumption_status === 'in_progress')
                    <form method="POST" action="{{ route('material-consumptions.mark-completed', $materialConsumption) }}" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Mark Completed
                        </button>
                    </form>
                    @endif
                    
                    @if($materialConsumption->wastage_percentage > 10)
                    <form method="POST" action="{{ route('material-consumptions.record-waste', $materialConsumption) }}" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Record Waste
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif


            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Consumption Created</p>
                            <p class="text-sm text-gray-500">{{ $materialConsumption->created_at ? $materialConsumption->created_at->format('M d, Y H:i') : 'Not set' }}</p>
                        </div>
                    </div>
                    
                    @if($materialConsumption->approved_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Consumption Approved</p>
                            <p class="text-sm text-gray-500">{{ $materialConsumption->approved_at ? $materialConsumption->approved_at->format('M d, Y H:i') : 'Not set' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
