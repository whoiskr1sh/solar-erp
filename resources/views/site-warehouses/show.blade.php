@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $siteWarehouse->warehouse_name }}</h2>
                        <p class="text-gray-600 mt-1">{{ $siteWarehouse->project->name }} - {{ $siteWarehouse->location }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('site-warehouses.edit', $siteWarehouse) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            Edit Warehouse
                        </a>
                        <a href="{{ route('site-warehouses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            Back to List
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Details -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Warehouse Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->warehouse_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Location</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->location }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $siteWarehouse->status_badge }}">
                                        {{ ucfirst($siteWarehouse->status) }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Manager</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->manager->name }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        @if($siteWarehouse->contact_person || $siteWarehouse->contact_phone || $siteWarehouse->contact_email)
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($siteWarehouse->contact_person)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contact Person</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->contact_person }}</p>
                                </div>
                                @endif
                                @if($siteWarehouse->contact_phone)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->contact_phone }}</p>
                                </div>
                                @endif
                                @if($siteWarehouse->contact_email)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->contact_email }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Address -->
                        @if($siteWarehouse->address)
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Address</h3>
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $siteWarehouse->address }}</p>
                        </div>
                        @endif

                        <!-- Description -->
                        @if($siteWarehouse->description)
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                            <p class="text-sm text-gray-900 whitespace-pre-line">{{ $siteWarehouse->description }}</p>
                        </div>
                        @endif

                        <!-- Facilities -->
                        @if($siteWarehouse->facilities && count($siteWarehouse->facilities) > 0)
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Facilities</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($siteWarehouse->facilities as $facility)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $facility }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Capacity Information -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Capacity Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Total Capacity</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->total_capacity ?? 'Not specified' }} sq ft</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Used Capacity</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->used_capacity }} sq ft</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Available Capacity</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->available_capacity }} sq ft</p>
                                </div>
                                @if($siteWarehouse->total_capacity > 0)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Usage Percentage</label>
                                    <div class="mt-2">
                                        <div class="bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $siteWarehouse->capacity_percentage }}%"></div>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->capacity_percentage }}%</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Project Information -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Project Details</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Project Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->project->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Project Code</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->project->project_code }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Project Status</label>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $siteWarehouse->project->status_badge }}">
                                        {{ ucfirst($siteWarehouse->project->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Timestamps</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Created</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $siteWarehouse->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection








