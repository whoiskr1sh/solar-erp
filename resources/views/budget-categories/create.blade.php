@extends('layouts.app')

@section('title', 'Create Budget Category')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Budget Category</h1>
            <p class="text-gray-600">Add a new budget category</p>
        </div>
        <a href="{{ route('budget-categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
            Back to Categories
        </a>
    </div>

    <!-- Create Form -->
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="{{ route('budget-categories.store') }}">
            @csrf
            
            <!-- Category Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Category Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                       placeholder="Enter category name">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                          placeholder="Enter category description">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Category Color <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-4 gap-3">
                    @php
                    $colorOptions = [
                        '#3B82F6' => 'Blue',
                        '#10B981' => 'Green', 
                        '#F59E0B' => 'Yellow',
                        '#EF4444' => 'Red',
                        '#8B5CF6' => 'Purple',
                        '#F97316' => 'Orange',
                        '#06B6D4' => 'Cyan',
                        '#84CC16' => 'Lime',
                        '#EC4899' => 'Pink',
                        '#6B7280' => 'Gray',
                        '#1F2937' => 'Dark Gray',
                        '#DC2626' => 'Dark Red'
                    ];
                    @endphp
                    @foreach($colorOptions as $color => $name)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="color" value="{{ $color }}" 
                               {{ old('color', '#3B82F6') === $color ? 'checked' : '' }}
                               class="sr-only">
                        <div class="w-full h-12 rounded-lg border-2 transition-all duration-200
                                    {{ old('color', '#3B82F6') === $color ? 'border-gray-900 shadow-lg' : 'border-gray-200 hover:border-gray-300' }}"
                             style="background-color: {{ $color }};">
                            <div class="text-center text-white text-xs font-bold py-3">
                                {{ $name }}
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                <input type="text" id="color" name="color" hidden value="{{ old('color', '#3B82F6') }}">
                @error('color')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <div class="flex items-center space-x-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_active" value="1" 
                               {{ old('is_active', '1') === '1' ? 'checked' : '' }}
                               class="form-radio text-teal-600 focus:ring-teal-500">
                        <span class="ml-2 text-gray-700">Active</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_active" value="0" 
                               {{ old('is_active') === '0' ? 'checked' : '' }}
                               class="form-radio text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-gray-700">Inactive</span>
                    </label>
                </div>
            </div>

            <!-- Preview -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Preview:</h3>
                <div class="flex items-center">
                    <div id="previewColor" class="w-8 h-8 rounded-full mr-3" style="background-color: {{ old('color', '#3B82F6') }};"></div>
                    <div>
                        <span id="previewName" class="text-gray-900 font-medium">{{ old('name') ?: 'Category Name' }}</span>
                        <p id="previewDescription" class="text-sm text-gray-500">{{ old('description') ?: 'Category description' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('budget-categories.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition duration-300">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg transition duration-300">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Update preview when form changes
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const colorInput = document.getElementById('color');
    const previewName = document.getElementById('previewName');
    const previewDescription = document.getElementById('previewDescription');
    const previewColor = document.getElementById('previewColor');

    nameInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Category Name';
    });

    descriptionInput.addEventListener('input', function() {
        previewDescription.textContent = this.value || 'Category description';
    });

    // Update preview color when radio buttons change
    const colorRadios = document.querySelectorAll('input[name="color"]');
    colorRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            colorInput.value = this.value;
            previewColor.style.backgroundColor = this.value;
        });
    });
});
</script>
@endsection
