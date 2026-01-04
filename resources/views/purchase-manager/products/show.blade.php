@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                        <p class="mt-2 text-gray-600">{{ $product->sku ?? 'Product Details' }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('purchase-manager.products.edit', $product) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Product
                        </a>
                        <a href="{{ route('purchase-manager.products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Product Name</label>
                            <p class="text-gray-900">{{ $product->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">SKU</label>
                            <p class="text-gray-900">{{ $product->sku ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Category</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($product->category === 'solar_panel') bg-blue-100 text-blue-800
                                @elseif($product->category === 'inverter') bg-green-100 text-green-800
                                @elseif($product->category === 'battery') bg-yellow-100 text-yellow-800
                                @elseif($product->category === 'mounting_structure') bg-purple-100 text-purple-800
                                @elseif($product->category === 'cables') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $product->category ?? 'N/A')) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Brand</label>
                            <p class="text-gray-900">{{ $product->brand ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Model</label>
                            <p class="text-gray-900">{{ $product->model ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($product->status === 'active') bg-green-100 text-green-800
                                @elseif($product->status === 'inactive') bg-red-100 text-red-800
                                @elseif($product->status === 'discontinued') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($product->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Pricing Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Selling Price</label>
                            <p class="text-lg font-semibold text-gray-900">₹{{ number_format($product->price, 2) }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Cost Price</label>
                            <p class="text-gray-900">₹{{ $product->cost_price ? number_format($product->cost_price, 2) : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tax Rate</label>
                            <p class="text-gray-900">{{ $product->tax_rate ? $product->tax_rate . '%' : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Discount</label>
                            <p class="text-gray-900">{{ $product->discount_percentage ? $product->discount_percentage . '%' : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Inventory Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Inventory Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Stock Quantity</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $product->stock_quantity ?? 0 }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Unit</label>
                            <p class="text-gray-900">{{ ucfirst($product->unit ?? 'N/A') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Minimum Stock Level</label>
                            <p class="text-gray-900">{{ $product->min_stock_level ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Maximum Stock Level</label>
                            <p class="text-gray-900">{{ $product->max_stock_level ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Product Specifications -->
                @if($product->description || $product->specifications || $product->warranty_period)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Product Specifications</h3>
                    <div class="space-y-4">
                        @if($product->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $product->description }}</p>
                        </div>
                        @endif
                        
                        @if($product->specifications)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Technical Specifications</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $product->specifications }}</p>
                        </div>
                        @endif
                        
                        @if($product->warranty_period)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Warranty Period</label>
                            <p class="text-gray-900">{{ $product->warranty_period }} months</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center
                            @if($product->status === 'active') bg-green-100
                            @elseif($product->status === 'inactive') bg-red-100
                            @elseif($product->status === 'discontinued') bg-gray-100
                            @else bg-gray-100 @endif">
                            <i class="fas fa-box text-2xl
                                @if($product->status === 'active') text-green-600
                                @elseif($product->status === 'inactive') text-red-600
                                @elseif($product->status === 'discontinued') text-gray-600
                                @else text-gray-600 @endif"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900">{{ ucfirst($product->status) }}</h4>
                        <p class="text-sm text-gray-600">Product Status</p>
                    </div>
                </div>

                <!-- Stock Alert -->
                @if($product->min_stock_level && $product->stock_quantity <= $product->min_stock_level)
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Low Stock Alert</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Current stock: {{ $product->stock_quantity }}</p>
                                <p>Minimum level: {{ $product->min_stock_level }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('purchase-manager.products.edit', $product) }}" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-center block">
                            <i class="fas fa-edit mr-2"></i>Edit Product
                        </a>
                        <button onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Delete Product
                        </button>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Product Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created</label>
                            <p class="text-gray-900">{{ $product->created_at ? $product->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $product->updated_at ? $product->updated_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Created By</label>
                            <p class="text-gray-900">{{ $product->createdBy->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Product</h3>
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete <span id="productName" class="font-medium"></span>? This action cannot be undone.</p>
                    
                    <form id="deleteForm" method="POST" class="space-y-4">
                        @csrf
                        @method('DELETE')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deletion Reason</label>
                            <textarea name="deletion_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Please provide a reason for deletion..." required></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="confirmation" id="confirmation" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded" required>
                            <label for="confirmation" class="ml-2 block text-sm text-gray-900">I understand this action cannot be undone</label>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="closeDeleteModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                Delete Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(productId, productName) {
    document.getElementById('productName').textContent = productName;
    document.getElementById('deleteForm').action = `/purchase-manager/products/${productId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteForm').reset();
}
</script>
@endsection





