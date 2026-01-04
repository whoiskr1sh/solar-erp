@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Warehouse Location</h1>
                    <p class="text-gray-600 mt-1">Manage warehouse locations and zones</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('inventory.warehouse-location.export') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-download mr-2"></i>Export
                    </a>
                    <button class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>New Location
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Warehouse Locations -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Main Warehouse -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-teal-50">
                    <h3 class="text-lg font-semibold text-gray-900">Main Warehouse - Mumbai</h3>
                    <p class="text-sm text-gray-600">WH-001 | Capacity: 85%</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Zone A - Solar Panels</h4>
                                <p class="text-sm text-gray-600">Rack 1-10</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">85% Full</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Zone B - Inverters</h4>
                                <p class="text-sm text-gray-600">Rack 11-15</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">60% Full</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Zone C - Accessories</h4>
                                <p class="text-sm text-gray-600">Rack 16-20</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">40% Full</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-2">
                        <button class="px-3 py-1 rounded-md border border-teal-500 text-teal-600 hover:bg-teal-50 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View Details
                        </button>
                        <button class="px-3 py-1 rounded-md border border-blue-500 text-blue-600 hover:bg-blue-50 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- Secondary Warehouse -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <h3 class="text-lg font-semibold text-gray-900">Secondary Warehouse - Delhi</h3>
                    <p class="text-sm text-gray-600">WH-002 | Capacity: 62%</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Zone A - Solar Panels</h4>
                                <p class="text-sm text-gray-600">Rack 1-8</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">75% Full</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Zone B - Inverters</h4>
                                <p class="text-sm text-gray-600">Rack 9-12</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">50% Full</span>
                        </div>
                        
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">Zone C - Accessories</h4>
                                <p class="text-sm text-gray-600">Rack 13-16</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">35% Full</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-2">
                        <button class="px-3 py-1 rounded-md border border-teal-500 text-teal-600 hover:bg-teal-50 transition-colors">
                            <i class="fas fa-eye mr-1"></i>View Details
                        </button>
                        <button class="px-3 py-1 rounded-md border border-blue-500 text-blue-600 hover:bg-blue-50 transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection








