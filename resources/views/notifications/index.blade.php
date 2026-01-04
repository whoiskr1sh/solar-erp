@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-600">Manage your notifications and alerts</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="markAllRead()" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Mark All Read
            </button>
            <button onclick="refreshNotifications()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
            <button onclick="resetNotifications()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reset to Unread
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Notifications</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-count">4</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Unread</p>
                    <p class="text-2xl font-bold text-gray-900" id="unread-count">2</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tasks</p>
                    <p class="text-2xl font-bold text-gray-900" id="task-count">1</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Leads</p>
                    <p class="text-2xl font-bold text-gray-900" id="lead-count">1</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex space-x-1">
            <button onclick="filterNotifications('all')" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn active" data-filter="all">All</button>
            <button onclick="filterNotifications('unread')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn" data-filter="unread">Unread</button>
            <button onclick="filterNotifications('task')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn" data-filter="task">Tasks</button>
            <button onclick="filterNotifications('lead')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn" data-filter="lead">Leads</button>
            <button onclick="filterNotifications('invoice')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn" data-filter="invoice">Invoices</button>
            <button onclick="filterNotifications('quotation')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn" data-filter="quotation">Quotations</button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Notifications</h3>
        </div>
        
        <div class="divide-y divide-gray-200" id="notifications-list">
            @forelse($notifications as $notification)
            <div class="p-6 notification-item {{ !$notification->read ? 'border-l-4 border-teal-500' : '' }}" 
                 data-id="{{ $notification->id }}" 
                 data-type="{{ $notification->type }}" 
                 data-read="{{ $notification->read ? 'true' : 'false' }}">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        @if($notification->type === 'lead')
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        @elseif($notification->type === 'task')
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        @elseif($notification->type === 'invoice')
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        @elseif($notification->type === 'quotation')
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        @elseif($notification->type === 'project')
                        <div class="bg-indigo-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        @elseif($notification->type === 'payment')
                        <div class="bg-emerald-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        @else
                        <div class="bg-gray-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">{{ $notification->title }}</h4>
                                <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                                <p class="text-sm text-gray-500 mt-2">{{ $notification->time_ago }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if(!$notification->read)
                                <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                                <button onclick="markAsRead(this)" class="text-teal-600 hover:text-teal-700 text-sm font-medium">Mark as Read</button>
                                @else
                                <span class="text-sm text-gray-500">Read</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
                <p class="text-gray-600">You're all caught up! No new notifications.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function filterNotifications(filter) {
    // Update button styles
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-teal-600', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    
    // Highlight active button
    const activeBtn = document.querySelector(`[data-filter="${filter}"]`);
    activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
    activeBtn.classList.add('bg-teal-600', 'text-white');
    
    // Filter notifications
    const notifications = document.querySelectorAll('.notification-item');
    
    notifications.forEach(notification => {
        const type = notification.getAttribute('data-type');
        const read = notification.getAttribute('data-read');
        
        let show = false;
        
        switch(filter) {
            case 'all':
                show = true;
                break;
            case 'unread':
                show = read === 'false';
                break;
            case 'task':
                show = type === 'task';
                break;
            case 'lead':
                show = type === 'lead';
                break;
            case 'invoice':
                show = type === 'invoice';
                break;
            case 'quotation':
                show = type === 'quotation';
                break;
        }
        
        if (show) {
            notification.style.display = 'block';
        } else {
            notification.style.display = 'none';
        }
    });
    
    // Update counts
    updateCounts();
}

function markAsRead(button) {
    const notification = button.closest('.notification-item');
    const notificationId = notification.getAttribute('data-id');
    
    // Make API call to mark as read
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove unread styling
            notification.classList.remove('border-l-4', 'border-teal-500');
            
            // Update data attribute
            notification.setAttribute('data-read', 'true');
            
            // Remove unread indicator and button
            const indicator = notification.querySelector('.bg-teal-500');
            if (indicator) {
                indicator.remove();
            }
            
            // Replace button with "Read" text
            const buttonContainer = button.parentElement;
            buttonContainer.innerHTML = '<span class="text-sm text-gray-500">Read</span>';
            
            // Update counts
            updateCounts();
            
            // Show success message
            showToast('Notification marked as read');
        } else {
            showToast('Error marking notification as read');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error marking notification as read');
    });
}

function markAllRead() {
    if (confirm('Are you sure you want to mark all notifications as read?')) {
        // Make API call to mark all as read
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notifications = document.querySelectorAll('.notification-item');
                
                notifications.forEach(notification => {
                    // Remove unread styling
                    notification.classList.remove('border-l-4', 'border-teal-500');
                    
                    // Update data attribute
                    notification.setAttribute('data-read', 'true');
                    
                    // Remove unread indicator
                    const indicator = notification.querySelector('.bg-teal-500');
                    if (indicator) {
                        indicator.remove();
                    }
                    
                    // Update button to "Read" text
                    const button = notification.querySelector('button');
                    if (button) {
                        const buttonContainer = button.parentElement;
                        buttonContainer.innerHTML = '<span class="text-sm text-gray-500">Read</span>';
                    }
                });
                
                // Update counts
                updateCounts();
                
                // Show success message
                showToast('All notifications marked as read');
                
                // Refresh filter if needed
                const activeFilter = document.querySelector('.filter-btn.bg-teal-600').getAttribute('data-filter');
                filterNotifications(activeFilter);
            } else {
                showToast('Error marking all notifications as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error marking all notifications as read');
        });
    }
}

function refreshNotifications() {
    showToast('Notifications refreshed');
    // In a real app, this would make an API call to refresh notifications
}

function resetNotifications() {
    if (confirm('Are you sure you want to reset all notifications? This will mark all notifications as unread.')) {
        fetch('/notifications/reset', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`Notifications reset successfully! ${data.updated_count} notifications marked as unread.`);
                
                // Update all notifications to unread status
                const notifications = document.querySelectorAll('.notification-item');
                notifications.forEach(notification => {
                    // Add unread styling
                    notification.classList.add('border-l-4', 'border-teal-500');
                    
                    // Update data attribute
                    notification.setAttribute('data-read', 'false');
                    
                    // Update button
                    const buttonContainer = notification.querySelector('.flex.items-center.space-x-2');
                    if (buttonContainer) {
                        buttonContainer.innerHTML = `
                            <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                            <button onclick="markAsRead(this)" class="text-teal-600 hover:text-teal-700 text-sm font-medium">Mark as Read</button>
                        `;
                    }
                });
                
                // Update counts
                updateCounts();
                
                // Refresh filter if needed
                const activeFilter = document.querySelector('.filter-btn.bg-teal-600').getAttribute('data-filter');
                filterNotifications(activeFilter);
            } else {
                showToast('Error resetting notifications');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error resetting notifications');
        });
    }
}

function updateCounts() {
    const notifications = document.querySelectorAll('.notification-item');
    const unreadNotifications = document.querySelectorAll('.notification-item[data-read="false"]');
    const taskNotifications = document.querySelectorAll('.notification-item[data-type="task"]');
    const leadNotifications = document.querySelectorAll('.notification-item[data-type="lead"]');
    
    document.getElementById('total-count').textContent = notifications.length;
    document.getElementById('unread-count').textContent = unreadNotifications.length;
    document.getElementById('task-count').textContent = taskNotifications.length;
    document.getElementById('lead-count').textContent = leadNotifications.length;
}

function showToast(message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-teal-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.textContent = message;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    filterNotifications('all');
    updateCounts();
});
</script>
@endsection

