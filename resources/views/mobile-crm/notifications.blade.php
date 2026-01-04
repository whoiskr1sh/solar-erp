@extends('layouts.app')

@section('content')
<div class="p-4">
    <!-- Mobile Header -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('mobile-crm.index') }}" class="mr-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Notifications</h1>
                    <p class="text-sm text-gray-600">{{ count($notifications) }} notifications</p>
                </div>
            </div>
            <button onclick="markAllRead()" class="bg-teal-600 hover:bg-teal-700 text-white px-3 py-2 rounded-lg text-sm transition-colors duration-200">
                Mark All Read
            </button>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex space-x-1">
            <button onclick="filterNotifications('all')" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn active" data-filter="all">All</button>
            <button onclick="filterNotifications('unread')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn" data-filter="unread">Unread</button>
            <button onclick="filterNotifications('task')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn" data-filter="task">Tasks</button>
            <button onclick="filterNotifications('lead')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 filter-btn" data-filter="lead">Leads</button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3" id="notifications-list">
        @forelse($notifications as $notification)
        <div class="bg-white rounded-lg shadow-sm p-4 {{ !$notification['read'] ? 'border-l-4 border-teal-500' : '' }} notification-item" 
             data-type="{{ $notification['type'] }}" 
             data-read="{{ $notification['read'] ? 'true' : 'false' }}">
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-3">
                    @if($notification['type'] === 'lead')
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    @elseif($notification['type'] === 'task')
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    @elseif($notification['type'] === 'invoice')
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    @elseif($notification['type'] === 'quotation')
                    <div class="bg-orange-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    @else
                    <div class="bg-gray-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    @endif
                </div>
                
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900 {{ !$notification['read'] ? 'font-semibold' : '' }}">
                                {{ $notification['title'] }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $notification['time'] }}</p>
                        </div>
                        
                        @if(!$notification['read'])
                        <div class="ml-2">
                            <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
            <p class="text-gray-600">You're all caught up! No new notifications.</p>
        </div>
        @endforelse
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
        }
        
        if (show) {
            notification.style.display = 'block';
        } else {
            notification.style.display = 'none';
        }
    });
    
    // Update count
    const visibleCount = document.querySelectorAll('.notification-item[style="display: block;"], .notification-item:not([style*="display: none"])').length;
    const totalCount = notifications.length;
    
    // Update header count if needed
    const headerCount = document.querySelector('p.text-sm.text-gray-600');
    if (headerCount) {
        if (filter === 'all') {
            headerCount.textContent = `${totalCount} notifications`;
        } else {
            headerCount.textContent = `${visibleCount} ${filter} notifications`;
        }
    }
}

function markAllRead() {
    if (confirm('Are you sure you want to mark all notifications as read?')) {
        // Update all notifications to read
        const notifications = document.querySelectorAll('.notification-item');
        
        notifications.forEach(notification => {
            // Remove unread styling
            notification.classList.remove('border-l-4', 'border-teal-500');
            
            // Update data attribute
            notification.setAttribute('data-read', 'true');
            
            // Remove unread indicator
            const unreadDot = notification.querySelector('.bg-teal-500');
            if (unreadDot) {
                unreadDot.remove();
            }
        });
        
        // Update count
        const headerCount = document.querySelector('p.text-sm.text-gray-600');
        if (headerCount) {
            headerCount.textContent = `${notifications.length} notifications`;
        }
        
        alert('All notifications marked as read!');
        
        // Refresh filter if needed
        const activeFilter = document.querySelector('.filter-btn.bg-teal-600').getAttribute('data-filter');
        filterNotifications(activeFilter);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial state
    filterNotifications('all');
});
</script>
@endsection
