@extends('layouts.app')

@section('title', 'View Category')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-lg font-bold mr-4" 
                 style="background-color: {{ $budgetCategory->color }};">
                {{ substr($budgetCategory->name, 0, 2) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $budgetCategory->name }}</h1>
                <p class="text-gray-600">{{ $budgetCategory->budgets_count }} budget{{ $budgetCategory->budgets_count > 1 ? 's' : '' }}</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('budget-categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Categories
            </a>
            <a href="{{ route('budget-categories.edit', $budgetCategory) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Edit Category
            </a>
            <button onclick="deleteCategory({{ $budgetCategory->id }})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Delete Category
            </button>
        </div>
    </div>

    <!-- Category Details -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Category Information</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-700">Name:</span>
                        <span class="font-medium text-gray-900">{{ $budgetCategory->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Status:</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $budgetCategory->status_badge }}">
                            {{ $budgetCategory->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Created:</span>
                        <span class="font-medium text-gray-900">{{ $budgetCategory->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Updated:</span>
                        <span class="font-medium text-gray-900">{{ $budgetCategory->updated_at->format('M d, Y') }}</span>
                    </div>
                    @if($budgetCategory->description)
                    <div>
                        <span class="text-gray-700 block mb-2">Description:</span>
                        <p class="text-gray-900">{{ $budgetCategory->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Color & Stats -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Visual & Statistics</h3>
                <div class="bg-gray-50 rounded-lg p-6 mb-4">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold" 
                             style="background-color: {{ $budgetCategory->color }};">
                            {{ substr($budgetCategory->name, 0, 2) }}
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Category Color</p>
                        <p class="text-lg font-medium" style="color: {{ $budgetCategory->color }};">{{ $budgetCategory->color }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Budget Statistics</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Budgets:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $budgetCategory->budgets_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Budget Amount:</span>
                            <span class="text-sm font-medium text-gray-900">${{ number_format($budgetCategory->budgets()->where('status', '!=', 'rejected')->sum('budget_amount'), 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Actual:</span>
                            <span class="text-sm font-medium text-gray-900">${{ number_format($budgetCategory->budgets()->sum('actual_amount'), 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Associated Budgets -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Associated Budgets</h3>
            <a href="{{ route('budgets.index', ['category' => $budgetCategory->id]) }}" class="text-teal-600 hover:text-teal-800 text-sm font-medium">
                View All →
            </a>
        </div>

        @if($budgetCategory->budgets()->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Budget #</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Title</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Amount</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Created</th>
                        <th class="px-3 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($budgetCategory->budgets()->latest()->take(5)->get() as $budget)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-1 text-xs text-gray-900">{{ $budget->budget_number }}</td>
                        <td class="px-3 py-1 text-xs text-gray-900">{{ Str::limit($budget->title, 30) }}</td>
                        <td class="px-3 py-1 text-xs text-gray-900">{{ $budget->formatted_budget_amount }}</td>
                        <td class="px-3 py-1 text-xs">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $budget->status_badge }}">
                                {{ ucfirst($budget->status) }}
                            </span>
                        </td>
                        <td class="px-3 py-1 text-xs text-gray-900">{{ $budget->created_at->format('M d') }}</td>
                        <td class="px-3 py-1 text-xs text-gray-900">
                            <div class="flex space-x-1">
                                <a href="{{ route('budgets.show', $budget) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">V</a>
                                <a href="{{ route('budgets.edit', $budget) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">E</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No budgets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No budgets</h3>
            <p class="mt-1 text-sm text-gray-500">No budgets have been created for this category yet.</p>
            <div class="mt-6">
                <a href="{{ route('budgets.create', ['category' => $budgetCategory->id]) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700">
                    Create Budget
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteCategory(categoryId) {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone and will affect all associated budgets.')) {
        const form = document.getElementById('deleteForm');
        form.action = '{{ route("budget-categories.index") }}/' + categoryId;
        form.submit();
    }
}
</script>
@endsection
