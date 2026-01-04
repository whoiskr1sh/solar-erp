@props(['itemName', 'itemId', 'deleteRoute', 'itemType' => 'item'])

<!-- Delete Modal -->
<div id="deleteModal{{ $itemId }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete {{ ucfirst($itemType) }}</h3>
            <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete: <strong id="deleteItemName{{ $itemId }}"></strong>?</p>
            <p class="text-xs text-yellow-600 mb-4">This will require approval from Admin.</p>
            
            <form id="deleteForm{{ $itemId }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label for="delete_reason{{ $itemId }}" class="block text-sm font-medium text-gray-700 mb-2">Reason for Deletion <span class="text-red-500">*</span></label>
                    <textarea id="delete_reason{{ $itemId }}" name="reason" rows="3" required minlength="10"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-teal-500 focus:border-teal-500"
                              placeholder="Enter reason for deletion (minimum 10 characters)..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Please provide a detailed reason for deletion (minimum 10 characters).</p>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal{{ $itemId }}()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                        Request Deletion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteModal{{ $itemId }}(id, name) {
    document.getElementById('deleteItemName{{ $itemId }}').textContent = name;
    document.getElementById('deleteForm{{ $itemId }}').action = '{{ $deleteRoute }}'.replace(':id', id);
    document.getElementById('deleteModal{{ $itemId }}').classList.remove('hidden');
}

function closeDeleteModal{{ $itemId }}() {
    document.getElementById('deleteModal{{ $itemId }}').classList.add('hidden');
    document.getElementById('deleteForm{{ $itemId }}').reset();
}
</script>

