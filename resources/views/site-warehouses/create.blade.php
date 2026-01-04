@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Create Site Warehouse</h2>
                        <p class="text-gray-600 mt-1">Add a new warehouse for project storage</p>
                    </div>
                    <a href="{{ route('site-warehouses.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        Back to List
                    </a>
                </div>

                <form method="POST" action="{{ route('site-warehouses.store') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Project -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">Project *</label>
                            <select name="project_id" id="project_id" required class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                                <option value="">Select Project</option>
                                @if($projects && $projects->count() > 0)
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ $project->project_code }})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No projects available</option>
                                @endif
                            </select>
                            @error('project_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($projects && $projects->count() == 0)
                                <p class="mt-1 text-sm text-red-600">No projects found. Please create a project first.</p>
                            @endif
                        </div>

                        <!-- Warehouse Name -->
                        <div>
                            <label for="warehouse_name" class="block text-sm font-medium text-gray-700 mb-2">Warehouse Name *</label>
                            <input type="text" name="warehouse_name" id="warehouse_name" value="{{ old('warehouse_name') }}" required class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                            @error('warehouse_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                            <input type="text" name="location" id="location" value="{{ old('location') }}" required class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" required class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Person -->
                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person</label>
                            <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person') }}" class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Phone -->
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}" class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Managed By -->
                        <div>
                            <label for="managed_by" class="block text-sm font-medium text-gray-700 mb-2">Managed By *</label>
                            <select name="managed_by" id="managed_by" required class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                                <option value="">Select Manager</option>
                                @if($managers && $managers->count() > 0)
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}" {{ old('managed_by') == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }} ({{ $manager->department ?? 'No Department' }})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No managers available</option>
                                @endif
                            </select>
                            @error('managed_by')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($managers && $managers->count() == 0)
                                <p class="mt-1 text-sm text-red-600">No users found. Please create users first.</p>
                            @endif
                        </div>

                        <!-- Total Capacity -->
                        <div>
                            <label for="total_capacity" class="block text-sm font-medium text-gray-700 mb-2">Total Capacity (sq ft)</label>
                            <input type="number" name="total_capacity" id="total_capacity" value="{{ old('total_capacity') }}" step="0.01" min="0" class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                            @error('total_capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Used Capacity -->
                        <div>
                            <label for="used_capacity" class="block text-sm font-medium text-gray-700 mb-2">Used Capacity (sq ft)</label>
                            <input type="number" name="used_capacity" id="used_capacity" value="{{ old('used_capacity', 0) }}" step="0.01" min="0" class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">
                            @error('used_capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" id="address" rows="3" class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-colors duration-200">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Facilities -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Facilities</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @php
                                $facilities = ['Security', 'CCTV', 'Fire Safety', 'Loading Dock', 'Climate Control', 'Inventory Management', 'Access Control', 'Insurance', 'Maintenance'];
                            @endphp
                            @foreach($facilities as $facility)
                                <label class="flex items-center">
                                    <input type="checkbox" name="facilities[]" value="{{ $facility }}" {{ in_array($facility, old('facilities', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">{{ $facility }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('facilities')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('site-warehouses.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-blue-700">
                            Create Warehouse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
