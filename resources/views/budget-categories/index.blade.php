@extends('layouts.app')

@section('title', 'Budget Categories')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Budget Categories</h1>
            <p class="text-gray-600">Manage your budget categories</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('budget-categories.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition duration-300">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Category
            </a>
            <a href="{{ route('budgets.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Budgets
            </a>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($categories as $category)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition duration-300">
            <!-- Category Header -->
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3" 
                             style="background-color: {{ $category->color }};">
                            {{ substr($category->name, 0, 2) }}
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $category->name }}</h3>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $category->status_badge }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                @if($category->description)
                <p class="text-gray-600 text-sm mb-4">{{ $category->description }}</p>
                @endif
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">
                        {{ $category->budgets_count }} budget{{ $category->budgets_count > 1 ? 's' : '' }}
                    </span>
                    <div class="flex space-x-2">
                        <a href="{{ route('budget-categories.show', $category) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View
                        </a>
                        <a href="{{ route('budget-categories.edit', $category) }}" 
                           class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                            Edit
                        </a>
                        <button onclick="deleteCategory({{ $category->id }})" 
                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new budget category.</p>
                <div class="mt-6">
                    <a href="{{ route('budget-categories.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700">
                        Add Category
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Quick Add Form -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Add Category</h3>
        <form method="POST" action="{{ route('budget-categories.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" id="description" name="description" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                    <select id="color" name="color" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="#3B82F6">Blue</option>
                        <option value="#10B981">Green</option>
                        <option value="#F59E0B">Yellow</option>
                        <option value="#EF4444">Red</option>
                        <option value="#8B5CF6">Purple</option>
                        <option value="#F97316">Orange</option>
                        <option value="#06B6D4">Cyan</option>
                        <option value="#84CC16">Lime</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-md transition duration-300">
                        Add Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteCategory(categoryId) {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        const form = document.getElementById('deleteForm');
        form.action = '{{ route("budget-categories.index") }}/' + categoryId;
        form.submit();
    }
}
</script>
@endsection
