@extends('layouts.app')

@section('title', 'Add New Category')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Category</h1>
            <p class="text-gray-600">Create a new expense category</p>
        </div>
        <a href="{{ route('expense-categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
            Back to Categories
        </a>
    </div>

    <!-- Form -->
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('expense-categories.store') }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="Enter category name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Enter category description">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Category Color *</label>
                    <div class="flex items-center space-x-3">
                        <input type="color" id="color" name="color" value="{{ old('color', '#3B82F6') }}" required
                               class="w-12 h-12 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500 @error('color') border-red-500 @enderror">
                        <div class="flex-1">
                            <input type="text" placeholder="#3B82F6" value="{{ old('color', '#3B82F6') }}"
                                   class="w-full px-3 py-2 border border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                   onchange="document.getElementById('color').value = this.value">
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Choose a color to represent this category</p>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500 @error('is_active') border-red-500 @enderror">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Inactive categories won't appear in expense forms</p>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="{{ route('expense-categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-300">
                    Cancel
                </a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition duration-300">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
