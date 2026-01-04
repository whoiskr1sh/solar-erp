<!-- Status Update Modal -->
<div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Update Task Status</h2>
            <button onclick="closeStatusModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="statusForm" action="" method="POST" onsubmit="return validateStatusForm(event)">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                <select id="statusSelect" name="status" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="not_completed">Not Completed</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks * <span class="text-xs text-gray-500">(Minimum 2 characters)</span></label>
                <textarea id="remarksField" name="remarks" required minlength="2" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Enter your remarks..."></textarea>
                <p class="text-xs text-red-500 mt-1 hidden" id="remarksError">Remarks are required and must be at least 10 characters.</p>
            </div>
            <div id="notCompletedReason" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Not Completing * <span class="text-xs text-gray-500">(Minimum 2 characters)</span></label>
                <textarea id="not_completed_reason" name="not_completed_reason" minlength="2" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white" placeholder="Explain why this task was not completed..."></textarea>
                <p class="text-xs text-red-500 mt-1 hidden" id="reasonError">Reason for not completing is required and must be at least 2 characters.</p>
            </div>
            <div class="mb-4 bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded-lg">
                <p class="text-xs text-yellow-700 dark:text-yellow-300">
                    <strong>Note:</strong> You must provide remarks for all status updates. If marking as "Not Completed", you must also provide a reason. You cannot logout until all tasks have proper status updates.
                </p>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">Update Status</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusModal = document.getElementById('statusModal');
    const statusForm = document.getElementById('statusForm');
    
    // Update form action when modal opens
    window.openStatusModal = function(todoId, currentStatus = null) {
        statusModal.setAttribute('data-todo-id', todoId);
        statusForm.action = '{{ route("todos.update-status", ":id") }}'.replace(':id', todoId);
        
        const statusSelect = document.getElementById('statusSelect');
        const reasonDiv = document.getElementById('notCompletedReason');
        const reasonField = document.getElementById('not_completed_reason');
        const remarksField = document.getElementById('remarksField');
        
        // Pre-populate status if provided
        if (currentStatus && statusSelect) {
            statusSelect.value = currentStatus;
            // Trigger change event to show/hide reason field
            const event = new Event('change', { bubbles: true });
            statusSelect.dispatchEvent(event);
        }
        
        // Reset form fields
        if (remarksField) remarksField.value = '';
        if (reasonField) {
            reasonField.value = '';
            if (currentStatus !== 'not_completed') {
                reasonField.required = false;
            }
        }
        
        statusModal.classList.remove('hidden');
    };
    
    // Handle status change
    document.getElementById('statusSelect').addEventListener('change', function() {
        const reasonDiv = document.getElementById('notCompletedReason');
        const reasonField = document.getElementById('not_completed_reason');
        const reasonError = document.getElementById('reasonError');
        if (this.value === 'not_completed') {
            reasonDiv.classList.remove('hidden');
            reasonField.required = true;
            reasonField.setAttribute('required', 'required');
        } else {
            reasonDiv.classList.add('hidden');
            reasonField.required = false;
            reasonField.removeAttribute('required');
            reasonField.value = '';
            if (reasonError) {
                reasonError.classList.add('hidden');
            }
        }
    });
    
    // Validate form before submission
    window.validateStatusForm = function(event) {
        const status = document.getElementById('statusSelect').value;
        const remarks = document.getElementById('remarksField').value.trim();
        const reasonField = document.getElementById('not_completed_reason');
        const reason = reasonField ? reasonField.value.trim() : '';
        const remarksError = document.getElementById('remarksError');
        const reasonError = document.getElementById('reasonError');
        
        let isValid = true;
        
        // Check remarks (minimum 2 characters)
        if (!remarks || remarks.length < 2) {
            if (remarksError) {
                remarksError.classList.remove('hidden');
            }
            isValid = false;
        } else {
            if (remarksError) {
                remarksError.classList.add('hidden');
            }
        }
        
        // Check reason if not completed
        if (status === 'not_completed') {
            if (!reason || reason.length < 2) {
                if (reasonError) {
                    reasonError.classList.remove('hidden');
                }
                isValid = false;
            } else {
                if (reasonError) {
                    reasonError.classList.add('hidden');
                }
            }
        }
        
        if (!isValid) {
            event.preventDefault();
            alert('Please fill in all required fields with at least 2 characters.');
            return false;
        }
        
        return true;
    };
});
</script>


