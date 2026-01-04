<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ (auth()->check() && isset(auth()->user()->settings['general']['theme']) && auth()->user()->settings['general']['theme'] === 'dark') ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Solar ERP') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets (CSS & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Apply theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check localStorage first (for immediate updates), then fall back to server setting
            const savedTheme = localStorage.getItem('theme');
            const serverTheme = '{{ auth()->check() && isset(auth()->user()->settings["general"]["theme"]) ? auth()->user()->settings["general"]["theme"] : "light" }}';
            const theme = savedTheme || serverTheme;
            
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
    <style>
        /* Global dark mode styles for common elements */
        .dark .bg-white {
            background-color: rgb(31 41 55) !important; /* gray-800 */
        }
        .dark .text-gray-900:not(.dark\\:text-white):not(.dark\\:text-gray-300) {
            color: rgb(255 255 255) !important;
        }
        .dark .text-gray-700:not(.dark\\:text-gray-300):not(.dark\\:text-white) {
            color: rgb(209 213 219) !important; /* gray-300 */
        }
        .dark .text-gray-600:not(.dark\\:text-gray-400):not(.dark\\:text-gray-300) {
            color: rgb(156 163 175) !important; /* gray-400 */
        }
        .dark .border-gray-200:not(.dark\\:border-gray-700) {
            border-color: rgb(55 65 81) !important; /* gray-700 */
        }
        .dark .border-gray-300:not(.dark\\:border-gray-600) {
            border-color: rgb(75 85 99) !important; /* gray-600 */
        }

        /* Disable all animations and transitions while Daily To-Do modal is active */
        body.no-animations,
        body.no-animations * {
            -webkit-animation: none !important;
            animation: none !important;
            -webkit-transition: none !important;
            transition: none !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <!-- Mobile Menu Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" onclick="closeMobileMenu()"></div>
    
    <!-- Mobile Menu Button -->
    <button onclick="toggleMobileMenu()" class="fixed top-4 left-4 z-50 lg:hidden bg-white dark:bg-gray-800 p-2 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>
    
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out" id="sidebar">
            <!-- Logo -->
            <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between lg:justify-start">
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <!-- Solar Panel Grid -->
                            <rect x="2" y="2" width="4" height="4" fill="rgba(255,255,255,0.8)"/>
                            <rect x="7" y="2" width="4" height="4" fill="rgba(255,255,255,0.8)"/>
                            <rect x="12" y="2" width="4" height="4" fill="rgba(255,255,255,0.8)"/>
                            <rect x="17" y="2" width="4" height="4" fill="rgba(255,255,255,0.8)"/>
                            
                            <rect x="2" y="7" width="4" height="4" fill="rgba(255,255,255,0.6)"/>
                            <rect x="7" y="7" width="4" height="4" fill="rgba(255,255,255,0.6)"/>
                            <rect x="12" y="7" width="4" height="4" fill="rgba(255,255,255,0.6)"/>
                            <rect x="17" y="7" width="4" height="4" fill="rgba(255,255,255,0.6)"/>
                            
                            <rect x="2" y="12" width="4" height="4" fill="rgba(255,255,255,0.4)"/>
                            <rect x="7" y="12" width="4" height="4" fill="rgba(255,255,255,0.4)"/>
                            <rect x="12" y="12" width="4" height="4" fill="rgba(255,255,255,0.4)"/>
                            <rect x="17" y="12" width="4" height="4" fill="rgba(255,255,255,0.4)"/>
                            
                            <rect x="2" y="17" width="4" height="4" fill="rgba(255,255,255,0.2)"/>
                            <rect x="7" y="17" width="4" height="4" fill="rgba(255,255,255,0.2)"/>
                            <rect x="12" y="17" width="4" height="4" fill="rgba(255,255,255,0.2)"/>
                            <rect x="17" y="17" width="4" height="4" fill="rgba(255,255,255,0.2)"/>
                            
                            <!-- Sun rays -->
                            <path d="M12 0v2M12 22v2M0 12h2M22 12h2M3.52 3.52l1.42 1.42M19.07 19.07l1.42 1.42M3.52 20.48l1.42-1.42M19.07 4.93l1.42-1.42" stroke="rgba(255,255,255,0.5)" stroke-width="1" fill="none"/>
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">Solar ERP</h1>
                        <p class="text-gray-500 dark:text-gray-400 dark:text-gray-400 text-xs sm:text-sm">Enterprise Management</p>
                    </div>
                </div>
                <!-- Close button for mobile -->
                <button onclick="closeMobileMenu()" class="lg:hidden text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 p-3 sm:p-4 overflow-y-auto">
                <ul class="space-y-2 sm:space-y-3">
                    <!-- Role-Based Dashboard (Always at Top) -->
                    @if(auth()->user()->hasRole('SUPER ADMIN'))
                    <!-- Super Admin Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Admin Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('todos.all') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('todos.all') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('todos.all') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium text-sm">All To-Do Lists</span>
                            @php
                                $totalTodos = \App\Models\Todo::whereIn('status', ['pending', 'in_progress'])->count();
                            @endphp
                            @if($totalTodos > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                    {{ $totalTodos }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <!-- Approvals Section (Admin) -->
                    <li class="pt-4 pb-2 px-3 border-t border-gray-100 dark:border-gray-700/50 mt-4">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Approvals</span>
                    </li>
                    <li>
                        <a href="{{ route('approvals.admin.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('approvals.admin.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border-r-2 border-indigo-600 dark:border-indigo-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('approvals.admin.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span class="font-medium text-sm">Admin Final Approvals</span>
                            @php
                                $totalPendingAdmin = \App\Models\SiteExpense::where('status', 'pending')->where('approval_level', 'admin')->count() +
                                                    \App\Models\Advance::where('status', 'pending')->where('approval_level', 'admin')->count();
                            @endphp
                            @if($totalPendingAdmin > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200">
                                    {{ $totalPendingAdmin }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.delete-approvals.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.delete-approvals.*') || request()->routeIs('admin.delete-approval.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.delete-approvals.*') || request()->routeIs('admin.delete-approval.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Delete Approvals</span>
                            @php
                                $pendingCount = \App\Models\DeleteApproval::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.duplicate-lead-approvals.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.duplicate-lead-approvals.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.duplicate-lead-approvals.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Duplicate Lead Approvals</span>
                            @php
                                $pendingCount = \App\Models\DuplicateLeadApproval::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.lead-reassignment-requests.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.lead-reassignment-requests.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.lead-reassignment-requests.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <span class="font-medium text-sm">Lead Reassignment Requests</span>
                            @php
                                $pendingReassignmentCount = \App\Models\LeadReassignmentRequest::where('status', 'pending')->count();
                            @endphp
                            @if($pendingReassignmentCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $pendingReassignmentCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.task-assignment-approvals.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.task-assignment-approvals.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.task-assignment-approvals.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="font-medium text-sm">Task Assignment Approvals</span>
                            @php
                                $pendingAssignmentCount = \App\Models\TaskAssignmentApproval::whereIn('status', ['pending_manager_approval', 'pending_admin_approval'])->count();
                            @endphp
                            @if($pendingAssignmentCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $pendingAssignmentCount }}</span>
                            @endif
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'super-admin'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.model-backups.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.model-backups.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.model-backups.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                            </svg>
                            <span class="font-medium text-sm">All Backups</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.unavailable-users.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.unavailable-users.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.unavailable-users.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="font-medium text-sm">Unavailable Users</span>
                            @php
                                $unavailableCount = \App\Models\User::where('is_active', true)->where('is_available_for_followup', false)->count();
                            @endphp
                            @if($unavailableCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $unavailableCount }}</span>
                            @endif
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('SALES MANAGER'))
                    <!-- Sales Manager Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.sales-manager') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.sales-manager') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.sales-manager') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Sales Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('todos.all') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('todos.all') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('todos.all') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium text-sm">All To-Do Lists</span>
                            @php
                                $totalTodos = \App\Models\Todo::whereIn('status', ['pending', 'in_progress'])->count();
                            @endphp
                            @if($totalTodos > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                    {{ $totalTodos }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'sales-manager'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    
                    @elseif(auth()->user()->hasRole('PROJECT MANAGER'))
                            @php
                                $pendingCount = \App\Models\DeleteApproval::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.model-backups.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.model-backups.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.model-backups.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                            </svg>
                            <span class="font-medium text-sm">All Backups</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.duplicate-lead-approvals.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.duplicate-lead-approvals.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.duplicate-lead-approvals.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Duplicate Lead Approvals</span>
                            @php
                                $pendingCount = \App\Models\DuplicateLeadApproval::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.lead-reassignment-requests.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.lead-reassignment-requests.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.lead-reassignment-requests.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <span class="font-medium text-sm">Reassignment Requests</span>
                            @php
                                $pendingReassignmentCount = \App\Models\LeadReassignmentRequest::whereIn('status', ['pending_manager_approval', 'pending_admin_approval'])->count();
                            @endphp
                            @if($pendingReassignmentCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $pendingReassignmentCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.task-assignment-approvals.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.task-assignment-approvals.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.task-assignment-approvals.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="font-medium text-sm">Task Assignment Approvals</span>
                            @php
                                $pendingAssignmentCount = \App\Models\TaskAssignmentApproval::whereIn('status', ['pending_manager_approval', 'pending_admin_approval'])->count();
                            @endphp
                            @if($pendingAssignmentCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $pendingAssignmentCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.unavailable-users.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('admin.unavailable-users.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.unavailable-users.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="font-medium text-sm">Unavailable Users</span>
                            @php
                                $unavailableCount = \App\Models\User::where('is_active', true)->where('is_available_for_followup', false)->count();
                            @endphp
                            @if($unavailableCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $unavailableCount }}</span>
                            @endif
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('PROJECT MANAGER'))
                    <!-- Project Manager Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.project-manager') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.project-manager') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.project-manager') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="font-medium text-sm">Project Dashboard</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'project-manager'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('TELE SALES'))
                    <!-- Tele Sales Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.tele-sales') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.tele-sales') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.tele-sales') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="font-medium text-sm">Tele Sales Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'tele-sales'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('FIELD SALES'))
                    <!-- Field Sales Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.field-sales') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.field-sales') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.field-sales') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Field Sales Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('PROJECT ENGINEER'))
                    <!-- Project Engineer Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.project-engineer') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.project-engineer') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.project-engineer') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                            <span class="font-medium text-sm">Project Engineer Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    
                    <!-- PROJECT ENGINEER - Individual Modules -->
                    <li>
                        <a href="{{ route('activities.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('activities.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('activities.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Activity Planning</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('documents.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('documents.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('documents.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Project Documents</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('mobile.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('mobile.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('mobile.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Mobile App</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Expense Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('material-requests.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('material-requests.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('material-requests.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Material Request</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('material-consumptions.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('material-consumptions.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('material-consumptions.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Material Consumption</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('site-warehouses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-warehouses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-warehouses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Warehouse</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('contractors.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('contractors.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('contractors.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Contractors</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('LIASONING EXECUTIVE'))
                    <!-- Liaisoning Executive Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.liaisoning') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.liaisoning') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.liaisoning') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                            <span class="font-medium text-sm">Liaisoning Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    
                    <!-- LIASONING EXECUTIVE - Individual Modules -->
                    <li>
                        <a href="{{ route('activities.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('activities.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('activities.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Activity Planning</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('documents.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('documents.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('documents.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Project Documents</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('mobile.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('mobile.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('mobile.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Mobile App</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Expense Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('material-requests.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('material-requests.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('material-requests.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Material Request</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('material-consumptions.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('material-consumptions.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('material-consumptions.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Material Consumption</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('QUALITY ENGINEER'))
                    <!-- Quality Engineer Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.quality-engineer') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.quality-engineer') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.quality-engineer') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Quality Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    
                    <!-- QUALITY ENGINEER - Individual Modules -->
                    <li>
                        <a href="{{ route('activities.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('activities.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('activities.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Activity Planning</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('documents.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('documents.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('documents.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Project Documents</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('mobile.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('mobile.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('mobile.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Mobile App</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Expense Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('material-requests.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('material-requests.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('material-requests.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Material Request</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('material-consumptions.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('material-consumptions.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('material-consumptions.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Material Consumption</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('PURCHASE MANAGER/EXECUTIVE'))
                    <!-- Purchase Manager Dashboard -->
                    <li>
                        <a href="{{ route('purchase-manager.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('purchase-manager.dashboard') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('purchase-manager.dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="font-medium text-sm">Purchase Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    
                    <!-- PURCHASE MANAGER/EXECUTIVE - Purchase Modules -->
                    <li>
                        <a href="{{ route('purchase-manager.purchase-orders.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('purchase-manager.purchase-orders.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('purchase-manager.purchase-orders.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="font-medium text-sm">Purchase Orders</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('purchase-manager.vendors.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('purchase-manager.vendors.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('purchase-manager.vendors.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Vendors</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('purchase-manager.products.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('purchase-manager.products.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('purchase-manager.products.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Products</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('purchase-manager.rfqs.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('purchase-manager.rfqs.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('purchase-manager.rfqs.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">RFQs</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('purchase-manager.purchase-requisitions.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('purchase-manager.purchase-requisitions.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('purchase-manager.purchase-requisitions.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span class="font-medium text-sm">Purchase Requisitions</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('purchase-manager.vendor-registrations.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('purchase-manager.vendor-registrations.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('purchase-manager.vendor-registrations.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            <span class="font-medium text-sm">Vendor Registrations</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace([' ', '/'], ['-', '-'], auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('ACCOUNT EXECUTIVE'))
                    <!-- Account Executive Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.account-executive') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.account-executive') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.account-executive') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Account Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('STORE EXECUTIVE'))
                    <!-- Store Executive Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.store-executive') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.store-executive') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.store-executive') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Store Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('SERVICE ENGINEER'))
                    <!-- Service Engineer Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.service-engineer') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.service-engineer') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.service-engineer') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Service Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @elseif(auth()->user()->hasRole('HR MANAGER'))
                    <!-- HR Manager Dashboard -->
                    <li>
                        <a href="{{ route('dashboard.hr-manager') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard.hr-manager') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.hr-manager') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">HR Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- HR MANAGER - Individual Modules -->
                    <li>
                        <a href="{{ route('hr.employee-management') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.employee-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.employee-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Employee Management</span>
                        </a>
                    </li>
                    
                    
                    <!-- Approvals Section (HR Manager) -->
                    <li class="pt-4 pb-2 px-3 border-t border-gray-100 dark:border-gray-700/50 mt-4">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Approvals</span>
                    </li>
                    <li>
                        <a href="{{ route('approvals.hr.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('approvals.hr.*') ? 'bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 border-r-2 border-orange-600 dark:border-orange-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('approvals.hr.*') ? 'text-orange-600 dark:text-orange-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-sm"> Approvals</span>
                            @php
                                $totalPendingHR = \App\Models\SiteExpense::where('status', 'pending')->where('approval_level', 'hr')->count() +
                                                 \App\Models\Advance::where('status', 'pending')->where('approval_level', 'hr')->count();
                            @endphp
                            @if($totalPendingHR > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-200">
                                    {{ $totalPendingHR }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">HR Leave Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.attendance') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.attendance') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.attendance') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Attendance</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.salary-payroll') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.salary-payroll') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.salary-payroll') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Salary & Payroll</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.auto-salary-slip') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.auto-salary-slip') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.auto-salary-slip') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Auto Salary Slip</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.expense-reimbursement') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.expense-reimbursement') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.expense-reimbursement') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Expenses & Reimbursement</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.employee-self-service') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.employee-self-service') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.employee-self-service') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Employee Self Service Portal</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.recruitment') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.recruitment') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.recruitment') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Recruitment</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.appraisal-meetings') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.appraisal-meetings') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.appraisal-meetings') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Appraisal & Meetings</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('hr.performance-management') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.performance-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.performance-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Performance Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('company-policies.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('company-policies.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('company-policies.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Company Policies</span>
                        </a>
                    </li>
                    @else
                    <!-- Default Dashboard for other roles -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                            <span class="font-medium text-sm">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('todos.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('todos.index') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('todos.index') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <span class="font-medium text-sm">My To-Do List</span>
                            @php
                                $myIncompleteTodos = \App\Models\Todo::where('user_id', auth()->id())
                                    ->where(function($query) {
                                        $query->where('task_date', now()->toDateString())
                                              ->orWhere(function($q) {
                                                  $q->where('task_date', '<', now()->toDateString())
                                                    ->whereIn('status', ['pending', 'in_progress'])
                                                    ->where('is_carried_over', true);
                                              });
                                    })
                                    ->whereIn('status', ['pending', 'in_progress'])
                                    ->count();
                            @endphp
                            @if($myIncompleteTodos > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                    {{ $myIncompleteTodos }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Expenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advances.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('advances.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('advances.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Advance Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @endif

                    <!-- CRM - Different display for SUPER ADMIN vs Other Roles -->
                    @if(auth()->user()->hasRole('SALES MANAGER') || auth()->user()->hasRole('SUPER ADMIN'))

                    @if(auth()->user()->hasRole('SUPER ADMIN') || auth()->user()->hasRole('TELE SALES'))
                    <!-- SUPER ADMIN & TELE SALES: Show main module + submodules -->
                    <li>
                        <div class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer {{ request()->routeIs('leads.*') || request()->routeIs('quotations.*') || request()->routeIs('tasks.*') || request()->routeIs('invoices.*') || request()->routeIs('crm.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}" onclick="toggleSubmenu('crm')">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('leads.*') || request()->routeIs('quotations.*') || request()->routeIs('tasks.*') || request()->routeIs('invoices.*') || request()->routeIs('crm.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm flex-1">CRM</span>
                            <svg class="w-4 h-4 transition-transform duration-300 arrow-icon {{ request()->routeIs('leads.*') || request()->routeIs('quotations.*') || request()->routeIs('tasks.*') || request()->routeIs('invoices.*') || request()->routeIs('crm.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <ul id="crm" class="ml-8 mt-2 space-y-1 hidden">
                            <li>
                                <a href="{{ route('leads.index', ['view' => 'assigned']) }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('leads.index') && request('view') === 'assigned' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Assigned Leads (Viewed Contacts)</a>
                            </li>
                            <li>
                                <a href="{{ route('leads.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('leads.*') && request('view') !== 'assigned' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">All Leads</a>
                            </li>
                            <li><a href="{{ route('quotations.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('quotations.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Quotations</a></li>
                            <li><a href="{{ route('documents.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('documents.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Documents</a></li>
                            <li><a href="{{ route('tasks.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('tasks.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Tasks</a></li>
                            <li><a href="{{ route('invoices.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('invoices.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Invoicing</a></li>
                            <li><a href="{{ route('reports.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('reports.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Reports</a></li>
                            <li><a href="{{ route('crm.dashboard') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('crm.dashboard') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">CRM Dashboard</a></li>
                            <li><a href="{{ route('mobile-crm.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('mobile-crm.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Mobile CRM</a></li>
                            <li><a href="{{ route('notifications.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Notifications</a></li>
                            <li><a href="{{ route('costing.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('costing.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Costing/Budgeting</a></li>
                            <li><a href="{{ route('channel-partners.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('channel-partners.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Channel Partners</a></li>
                            <li><a href="{{ route('commissions.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('commissions.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Commissions</a></li>
                            <li><a href="{{ route('escalations.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('escalations.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Escalations</a></li>
                            <li><a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200">Custom Reports</a></li>
                        </ul>
                    </li>
                    @else
                    <!-- Other Roles (SALES MANAGER): Leads split into Assigned and All -->
                    <li>
                        <a href="{{ route('leads.index', ['view' => 'assigned']) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('leads.index') && request('view') === 'assigned' ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('leads.index') && request('view') === 'assigned' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium text-sm">Assigned Leads (Viewed)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leads.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('leads.*') && request('view') !== 'assigned' ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('leads.*') && request('view') !== 'assigned' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium text-sm">All Leads</span>
                        </a>
                    </li>
                    <li><a href="{{ route('quotations.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('quotations.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('quotations.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium text-sm">Quotations</span>
                    </a></li>
                    <li><a href="{{ route('documents.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('documents.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('documents.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium text-sm">Documents</span>
                    </a></li>
                    <li><a href="{{ route('tasks.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('tasks.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('tasks.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <span class="font-medium text-sm">Tasks</span>
                    </a></li>
                    <li><a href="{{ route('invoices.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('invoices.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('invoices.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium text-sm">Invoicing</span>
                    </a></li>
                    <li><a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium text-sm">Reports</span>
                    </a></li>
                    <li><a href="{{ route('crm.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('crm.dashboard') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('crm.dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium text-sm">CRM Dashboard</span>
                    </a></li>
                    <li><a href="{{ route('mobile-crm.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('mobile-crm.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('mobile-crm.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-medium text-sm">Mobile CRM</span>
                    </a></li>
                    <li><a href="{{ route('notifications.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('notifications.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L15 4.172M4.828 7H9m-4.172 0a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9a2 2 0 00-2-2M9 7h6"></path>
                        </svg>
                        <span class="font-medium text-sm">Notifications</span>
                    </a></li>
                    <li><a href="{{ route('costing.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('costing.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('costing.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <span class="font-medium text-sm">Costing/Budgeting</span>
                    </a></li>
                    <li><a href="{{ route('channel-partners.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('channel-partners.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('channel-partners.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium text-sm">Channel Partners</span>
                    </a></li>
                    <li><a href="{{ route('commissions.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('commissions.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('commissions.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <span class="font-medium text-sm">Commissions</span>
                    </a></li>
                    <li><a href="{{ route('escalations.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('escalations.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('escalations.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span class="font-medium text-sm">Escalations</span>
                    </a></li>
                    <li><a href="#" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 transition-all duration-200 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium text-sm">Custom Reports</span>
                    </a></li>
                    @endif
                    
                    @endif

                    <!-- TELE SALES - Individual Modules Only -->
                    @if(auth()->user()->hasRole('TELE SALES'))
                    <li>
                        <a href="{{ route('leads.index', ['view' => 'assigned']) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('leads.index') && request('view') === 'assigned' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('leads.index') && request('view') === 'assigned' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Assigned Leads (Viewed)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leads.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('leads.*') && request('view') !== 'assigned' && !request()->routeIs('leads.reassigned') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('leads.*') && request('view') !== 'assigned' && !request()->routeIs('leads.reassigned') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">All Leads</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leads.reassigned') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('leads.reassigned') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('leads.reassigned') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <span class="font-medium text-sm">Reassigned Leads</span>
                            @php
                                $reassignedCount = \App\Models\Lead::where('is_reassigned', true)
                                    ->where('assigned_user_id', auth()->id())
                                    ->where('updated_at', '>=', now()->subDays(30))
                                    ->count();
                            @endphp
                            @if($reassignedCount > 0)
                                <span class="ml-auto bg-blue-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $reassignedCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('quotations.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('quotations.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('quotations.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Quotations</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('documents.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('documents.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('documents.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Documents</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @endif

                    <!-- FIELD SALES - Individual Modules Only -->
                    @if(auth()->user()->hasRole('FIELD SALES'))
                    <li>
                        <a href="{{ route('leads.index', ['view' => 'assigned']) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('leads.index') && request('view') === 'assigned' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('leads.index') && request('view') === 'assigned' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Assigned Leads (Viewed)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leads.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('leads.*') && request('view') !== 'assigned' && !request()->routeIs('leads.reassigned') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('leads.*') && request('view') !== 'assigned' && !request()->routeIs('leads.reassigned') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">All Leads</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('leads.reassigned') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('leads.reassigned') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('leads.reassigned') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <span class="font-medium text-sm">Reassigned Leads</span>
                            @php
                                $reassignedCount = \App\Models\Lead::where('is_reassigned', true)
                                    ->where('assigned_user_id', auth()->id())
                                    ->where('updated_at', '>=', now()->subDays(30))
                                    ->count();
                            @endphp
                            @if($reassignedCount > 0)
                                <span class="ml-auto bg-blue-500 text-white text-xs font-bold rounded-full px-2 py-0.5">{{ $reassignedCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('quotations.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('quotations.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('quotations.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Quotations</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('documents.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('documents.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('documents.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Client Documents</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Leave Management</span>
                        </a>
                    </li>
                    @endif

                    <!-- PROJECT MANAGER - Individual Modules Only -->
                    @if(auth()->user()->hasRole('PROJECT MANAGER'))
                    <li>
                        <a href="{{ route('activities.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('activities.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('activities.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            <span class="font-medium text-sm">Activity Planning</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('resource-allocations.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('resource-allocations.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('resource-allocations.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="font-medium text-sm">Resource Allocation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('documents.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('documents.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('documents.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Documents</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('expenses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('expenses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Expense Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('notifications.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('notifications.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828zM4.828 17h8l-2.586-2.586a2 2 0 00-2.828 0L4.828 17z"></path>
                            </svg>
                            <span class="font-medium text-sm">Notifications</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mobile.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('mobile.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('mobile.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Mobile App</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('project-profitabilities.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('project-profitabilities.index') || request()->routeIs('project-profitabilities.show') || request()->routeIs('project-profitabilities.create') || request()->routeIs('project-profitabilities.edit') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('project-profitabilities.index') || request()->routeIs('project-profitabilities.show') || request()->routeIs('project-profitabilities.create') || request()->routeIs('project-profitabilities.edit') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="font-medium text-sm">Project Profitability</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('project-profitabilities.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('project-profitabilities.dashboard') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('project-profitabilities.dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Profitability Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('budgets.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('budgets.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('budgets.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Budgeting</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('payment-milestones.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('payment-milestones.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('payment-milestones.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="font-medium text-sm">Payment Milestones</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contractors.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('contractors.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('contractors.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="font-medium text-sm">Contractors</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('material-requests.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('material-requests.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('material-requests.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Material Request</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('material-consumptions.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('material-consumptions.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('material-consumptions.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Material Consumption</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('analytics.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('analytics.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('analytics.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-medium text-sm">Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dpr.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('dpr.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dpr.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-medium text-sm">DPR</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('site-warehouses.index') }}" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 {{ request()->routeIs('site-warehouses.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-r-2 border-blue-600 dark:border-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('site-warehouses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm">Site Warehouse</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 transition-all duration-200 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="font-medium text-sm">Customer Portal</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 transition-all duration-200 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span class="font-medium text-sm">Escalations</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 transition-all duration-200 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="font-medium text-sm">Sub Projects</span>
                        </a>
                    </li>
                    @endif

                    <!-- Project Management - Hidden for SALES MANAGER, TELE SALES, FIELD SALES, PROJECT MANAGER, PROJECT ENGINEER, LIASONING EXECUTIVE, QUALITY ENGINEER, PURCHASE MANAGER/EXECUTIVE, Visible for SUPER ADMIN -->
                    @if(auth()->user()->hasRole('SUPER ADMIN') || (!auth()->user()->hasRole('SALES MANAGER') && !auth()->user()->hasRole('TELE SALES') && !auth()->user()->hasRole('FIELD SALES') && !auth()->user()->hasRole('PROJECT MANAGER') && !auth()->user()->hasRole('PROJECT ENGINEER') && !auth()->user()->hasRole('LIASONING EXECUTIVE') && !auth()->user()->hasRole('QUALITY ENGINEER') && !auth()->user()->hasRole('PURCHASE MANAGER/EXECUTIVE') && (auth()->user()->can('view_projects') || auth()->user()->can('manage_projects') || auth()->user()->can('view_tasks') || auth()->user()->can('manage_tasks'))))
                    <li>
                        <div class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer {{ request()->routeIs('projects.*') || request()->routeIs('activities.*') || request()->routeIs('budgets.*') || request()->routeIs('contractors.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}" onclick="toggleSubmenu('project')">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('projects.*') || request()->routeIs('activities.*') || request()->routeIs('budgets.*') || request()->routeIs('contractors.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="font-medium text-sm flex-1">Project Management</span>
                            <svg class="w-4 h-4 transition-transform duration-300 arrow-icon {{ request()->routeIs('projects.*') || request()->routeIs('activities.*') || request()->routeIs('budgets.*') || request()->routeIs('contractors.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <ul id="project" class="ml-8 mt-2 space-y-1 hidden">
                            <li><a href="{{ route('projects.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('projects.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Project Dashboard</a></li>
                            <li><a href="{{ route('activities.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('activities.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Activity Planning & Tracking</a></li>
                            <li><a href="{{ route('resource-allocations.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('resource-allocations.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Resource Allocation</a></li>
                            <li><a href="{{ route('documents.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('documents.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Documents</a></li>
                            <li><a href="{{ route('expenses.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Expense Management</a></li>
                            <li><a href="{{ route('notifications.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Notifications</a></li>
                            <li><a href="{{ route('mobile.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('mobile.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Mobile App</a></li>
                            <li><a href="{{ route('project-profitabilities.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('project-profitabilities.index') || request()->routeIs('project-profitabilities.show') || request()->routeIs('project-profitabilities.create') || request()->routeIs('project-profitabilities.edit') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Project Profitability</a></li>
                            <li><a href="{{ route('project-profitabilities.dashboard') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('project-profitabilities.dashboard') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Profitability Dashboard</a></li>
                            <li><a href="{{ route('budgets.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('budgets.*') || request()->routeIs('budget-categories.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Budgeting</a></li>
                            <li><a href="{{ route('payment-milestones.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('payment-milestones.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Payment Milestones</a></li>
                            <li><a href="{{ route('contractors.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('contractors.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Contractors</a></li>
                            <li><a href="{{ route('material-requests.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('material-requests.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Material Request</a></li>
                            <li><a href="{{ route('material-consumptions.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('material-consumptions.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Material Consumption</a></li>
                            <li><a href="{{ route('analytics.dashboard') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('analytics.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Analytics</a></li>
                            <li><a href="{{ route('dpr.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('dpr.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">DPR</a></li>
                            <li><a href="{{ route('site-warehouses.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('site-warehouses.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Site Warehouse</a></li>
                            <li><a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200">Customer Portal</a></li>
                            <li><a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200">Escalations</a></li>
                            <li><a href="#" class="block px-3 py-2 text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-all duration-200">Sub Projects</a></li>
                        </ul>
                    </li>
                    @endif

                    <!-- Purchase - Hidden for SALES MANAGER, TELE SALES, FIELD SALES, PROJECT ENGINEER, LIASONING EXECUTIVE, QUALITY ENGINEER, PURCHASE MANAGER/EXECUTIVE, Visible for SUPER ADMIN -->
                    @if(auth()->user()->hasRole('SUPER ADMIN') || (!auth()->user()->hasRole('SALES MANAGER') && !auth()->user()->hasRole('TELE SALES') && !auth()->user()->hasRole('FIELD SALES') && !auth()->user()->hasRole('PROJECT ENGINEER') && !auth()->user()->hasRole('LIASONING EXECUTIVE') && !auth()->user()->hasRole('QUALITY ENGINEER') && !auth()->user()->hasRole('PURCHASE MANAGER/EXECUTIVE') && (auth()->user()->can('view_purchase_orders') || auth()->user()->can('manage_purchase_orders') || auth()->user()->can('view_vendors') || auth()->user()->can('manage_vendors'))))
                    <li>
                        <div class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer {{ request()->routeIs('purchase-orders.*') || request()->routeIs('vendors.*') || request()->routeIs('products.*') || request()->routeIs('rfqs.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}" onclick="toggleSubmenu('purchase')">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('purchase-orders.*') || request()->routeIs('vendors.*') || request()->routeIs('products.*') || request()->routeIs('rfqs.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="font-medium text-sm flex-1">Purchase</span>
                            <svg class="w-4 h-4 transition-transform duration-300 arrow-icon {{ request()->routeIs('purchase-orders.*') || request()->routeIs('vendors.*') || request()->routeIs('products.*') || request()->routeIs('rfqs.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <ul id="purchase" class="ml-8 mt-2 space-y-1 hidden">
                            <li><a href="{{ route('purchase-orders.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('purchase-orders.index') || request()->routeIs('purchase-orders.show') || request()->routeIs('purchase-orders.create') || request()->routeIs('purchase-orders.edit') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Purchase Orders</a></li>
                            <li><a href="{{ route('purchase-requisitions.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('purchase-requisitions.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Purchase Requisitions</a></li>
                            <li><a href="{{ route('vendors.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('vendors.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Vendor Management</a></li>
                            <li><a href="{{ route('products.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('products.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Product Master Management</a></li>
                            <li><a href="{{ route('purchase-orders.dashboard') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('purchase-orders.dashboard') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Dashboard</a></li>
                            <li><a href="{{ route('payment-requests.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('payment-requests.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Payment Request</a></li>
                            <li><a href="{{ route('rfqs.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('rfqs.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">RFQ</a></li>
                            <li><a href="{{ route('vendor-registrations.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('vendor-registrations.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Online Vendor Registration</a></li>
                        </ul>
                    </li>
                    @endif

                    <!-- Inventory - Hidden for SALES MANAGER, TELE SALES, FIELD SALES, PROJECT ENGINEER, LIASONING EXECUTIVE, QUALITY ENGINEER, PURCHASE MANAGER/EXECUTIVE, Visible for SUPER ADMIN -->
                    @if(auth()->user()->hasRole('SUPER ADMIN') || (!auth()->user()->hasRole('SALES MANAGER') && !auth()->user()->hasRole('TELE SALES') && !auth()->user()->hasRole('FIELD SALES') && !auth()->user()->hasRole('PROJECT ENGINEER') && !auth()->user()->hasRole('LIASONING EXECUTIVE') && !auth()->user()->hasRole('QUALITY ENGINEER') && !auth()->user()->hasRole('PURCHASE MANAGER/EXECUTIVE') && (auth()->user()->can('view_inventory') || auth()->user()->can('manage_inventory') || auth()->user()->can('view_stock') || auth()->user()->can('manage_stock'))))
                    <li>
                        <div class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer {{ request()->routeIs('inventory.*') || request()->routeIs('grns.*') || request()->routeIs('warehouses.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}" onclick="toggleSubmenu('inventory')">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('inventory.*') || request()->routeIs('grns.*') || request()->routeIs('warehouses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium text-sm flex-1">Inventory</span>
                            <svg class="w-4 h-4 transition-transform duration-300 arrow-icon {{ request()->routeIs('inventory.*') || request()->routeIs('grns.*') || request()->routeIs('warehouses.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <ul id="inventory" class="ml-8 mt-2 space-y-1 hidden">
                            <li><a href="{{ route('inventory.inward-grn') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('inventory.inward-grn') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Inward-GRN</a></li>
                            <li><a href="{{ route('inventory.outward-delivery') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('inventory.outward-delivery') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Outward-Delivery Challan/Note</a></li>
                            <li><a href="{{ route('inventory.warehouse-management') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('inventory.warehouse-management') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Warehouse Management</a></li>
                            <li><a href="{{ route('inventory.stock-ledger') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('inventory.stock-ledger') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Stock Ledger</a></li>
                            <li><a href="{{ route('inventory.inward-quality-check') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('inventory.inward-quality-check') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Inward Quality Check</a></li>
                            <li><a href="{{ route('inventory.stock-valuation-summary') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('inventory.stock-valuation-summary') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Stock Valuation Summary</a></li>
                            <li><a href="{{ route('inventory.warehouse-location') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('inventory.warehouse-location') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Warehouse Location</a></li>
                            <li><a href="{{ route('inventory.inventory-audit') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('inventory.inventory-audit') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Inventory Audit</a></li>
                        </ul>
                    </li>
                    @endif

                    <!-- O&M - Hidden for SALES MANAGER, TELE SALES, FIELD SALES, PROJECT ENGINEER, LIASONING EXECUTIVE, QUALITY ENGINEER, PURCHASE MANAGER/EXECUTIVE, Visible for SUPER ADMIN -->
                    @if(auth()->user()->hasRole('SUPER ADMIN') || (!auth()->user()->hasRole('SALES MANAGER') && !auth()->user()->hasRole('TELE SALES') && !auth()->user()->hasRole('FIELD SALES') && !auth()->user()->hasRole('PROJECT ENGINEER') && !auth()->user()->hasRole('LIASONING EXECUTIVE') && !auth()->user()->hasRole('QUALITY ENGINEER') && !auth()->user()->hasRole('PURCHASE MANAGER/EXECUTIVE') && (auth()->user()->can('view_complaints') || auth()->user()->can('manage_complaints') || auth()->user()->can('view_amc') || auth()->user()->can('manage_amc'))))
                    <li>
                        <div class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer {{ request()->routeIs('om.*') || request()->routeIs('complaints.*') || request()->routeIs('amc.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}" onclick="toggleSubmenu('om')">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('om.*') || request()->routeIs('complaints.*') || request()->routeIs('amc.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium text-sm flex-1">O&M</span>
                            <svg class="w-4 h-4 transition-transform duration-300 arrow-icon {{ request()->routeIs('om.*') || request()->routeIs('complaints.*') || request()->routeIs('amc.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <ul id="om" class="ml-8 mt-2 space-y-1 hidden">
                            <li><a href="{{ route('om.complaint-management') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('om.complaint-management') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Complaint Management</a></li>
                            <li><a href="{{ route('om.amc') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('om.amc') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">AMC</a></li>
                            <li><a href="{{ route('om.om-project-management') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('om.om-project-management') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">O&M Project Management</a></li>
                        </ul>
                    </li>
                    @endif

                    <!-- HR - Hidden for SALES MANAGER, TELE SALES, FIELD SALES, PROJECT ENGINEER, LIASONING EXECUTIVE, QUALITY ENGINEER, PURCHASE MANAGER/EXECUTIVE, HR MANAGER, Visible for SUPER ADMIN -->
                    @if(auth()->user()->hasRole('SUPER ADMIN') || (!auth()->user()->hasRole('SALES MANAGER') && !auth()->user()->hasRole('TELE SALES') && !auth()->user()->hasRole('FIELD SALES') && !auth()->user()->hasRole('PROJECT ENGINEER') && !auth()->user()->hasRole('LIASONING EXECUTIVE') && !auth()->user()->hasRole('QUALITY ENGINEER') && !auth()->user()->hasRole('PURCHASE MANAGER/EXECUTIVE') && !auth()->user()->hasRole('HR MANAGER') && (auth()->user()->can('view_users') || auth()->user()->can('manage_users') || auth()->user()->can('view_employees') || auth()->user()->can('manage_employees'))))
                    <li>
                        <div class="flex items-center px-3 py-2.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer {{ request()->routeIs('hr.*') || request()->routeIs('employees.*') || request()->routeIs('leaves.*') || request()->routeIs('attendance.*') || request()->routeIs('payroll.*') ? 'bg-blue-50 text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}" onclick="toggleSubmenu('hr')">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('hr.*') || request()->routeIs('employees.*') || request()->routeIs('leaves.*') || request()->routeIs('attendance.*') || request()->routeIs('payroll.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <span class="font-medium text-sm flex-1">HR</span>
                            <svg class="w-4 h-4 transition-transform duration-300 arrow-icon {{ request()->routeIs('hr.*') || request()->routeIs('employees.*') || request()->routeIs('leaves.*') || request()->routeIs('attendance.*') || request()->routeIs('payroll.*') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <ul id="hr" class="ml-8 mt-2 space-y-1 hidden">
                            <li><a href="{{ route('hr.employee-management') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.employee-management') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Employee Management</a></li>
                            <li><a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'default'))]) }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.leave-management') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Leave Management</a></li>
                            <li><a href="{{ route('hr.attendance') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.attendance') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Attendance</a></li>
                            <li><a href="{{ route('hr.salary-payroll') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.salary-payroll') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Salary & Payroll</a></li>
                            <li><a href="{{ route('hr.auto-salary-slip') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.auto-salary-slip') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Auto Salary Slip</a></li>
                            <li><a href="{{ route('hr.expense-reimbursement') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.expense-reimbursement') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Expenses & Reimbursement</a></li>
                            <li><a href="{{ route('hr.employee-self-service') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.employee-self-service') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Employee Self Service Portal</a></li>
                            <li><a href="{{ route('hr.recruitment') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.recruitment') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Recruitment</a></li>
                            <li><a href="{{ route('hr.appraisal-meetings') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.appraisal-meetings') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Appraisal & Meetings</a></li>
                            <li><a href="{{ route('hr.performance-management') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('hr.performance-management') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Performance Management</a></li>
                            <li><a href="{{ route('company-policies.index') }}" class="block px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-all duration-200 {{ request()->routeIs('company-policies.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30' : '' }}">Company Policies</a></li>
                        </ul>
                    </li>
                    @endif
            </nav>
            
            <!-- User Info -->
            <div class="p-3 sm:p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg flex-shrink-0">
                        <span class="text-xs sm:text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-2 sm:ml-3 flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate hidden sm:block">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return checkTodosBeforeLogout(event)">
                        @csrf
                        <button type="submit" class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors p-1 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-0">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-primary-600 dark:text-primary-400 truncate pr-2">@yield('title', 'Dashboard')</h2>
                        <div class="flex items-center space-x-2 sm:space-x-4 flex-shrink-0">
                            <!-- To-Do List Icon -->
                            <a href="{{ route('todos.index') }}" class="relative p-1.5 sm:p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors touch-manipulation {{ request()->routeIs('todos.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                                @if(file_exists(public_path('to-do-list.png')))
                                    <img src="{{ asset('to-do-list.png') }}" alt="To-Do List" class="w-5 h-5 sm:w-6 sm:h-6 object-contain">
                                @else
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                @endif
                                @php
                                    $incompleteTodoCount = \App\Models\Todo::where('user_id', auth()->id())
                                        ->where(function($query) {
                                            $query->where('task_date', now()->toDateString())
                                                  ->orWhere(function($q) {
                                                      $q->where('task_date', '<', now()->toDateString())
                                                        ->whereIn('status', ['pending', 'in_progress'])
                                                        ->where('is_carried_over', true);
                                                  });
                                        })
                                        ->whereIn('status', ['pending', 'in_progress'])
                                        ->count();
                                @endphp
                                @if($incompleteTodoCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">{{ $incompleteTodoCount > 9 ? '9+' : $incompleteTodoCount }}</span>
                                @endif
                            </a>
                            
                            <!-- Notification Bell -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="relative p-1.5 sm:p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors touch-manipulation">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L15 4.172M4.828 7H9m-4.172 0a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9a2 2 0 00-2-2M9 7h6"></path>
                                    </svg>
                                    @php
                                        $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('read', false)->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                    @endif
                                </button>
                                
                                <!-- Notification Dropdown -->
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-72 sm:w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 max-h-[80vh] overflow-hidden">
                                    <div class="p-3 sm:p-4 border-b border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                                            <a href="{{ route('notifications.index') }}" @click="open = false" class="text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800">View All</a>
                                        </div>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        @php
                                            $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())
                                                ->orderBy('created_at', 'desc')
                                                ->limit(5)
                                                ->get();
                                        @endphp
                                        @forelse($recentNotifications as $notification)
                                            <a href="{{ route('notifications.index') }}" @click="open = false" class="block p-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer {{ !$notification->read ? 'bg-blue-50 dark:bg-blue-900/30' : '' }}">
                                                <div class="flex items-start space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-2 h-2 bg-blue-500 rounded-full {{ $notification->read ? 'opacity-0' : '' }}"></div>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $notification->title }}</p>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($notification->message, 60) }}</p>
                                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $notification->time_ago }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                                <p class="text-sm">No notifications yet</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            
                            <!-- User Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <span class="text-white text-sm font-medium">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('profile.show') }}" @click="open = false" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 mr-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Profile
                                    </a>
                                    <a href="{{ route('settings.index') }}" @click="open = false" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <svg class="w-4 h-4 mr-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Settings
                                    </a>
                                    <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                    <!-- Availability Toggle -->
                                    <div class="px-4 py-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Follow-up Status</span>
                                            @if(auth()->user()->isAvailableForFollowup())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Available
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Unavailable
                                                </span>
                                            @endif
                                        </div>
                                        <form method="POST" action="{{ route('user.availability.toggle') }}" class="mt-2" id="availabilityForm">
                                            @csrf
                                            <input type="hidden" name="is_available" id="is_available_input" value="{{ auth()->user()->isAvailableForFollowup() ? '0' : '1' }}">
                                            @if(!auth()->user()->isAvailableForFollowup())
                                                <!-- Currently Unavailable - Show fields with existing values -->
                                                <div class="mb-1">
                                                    <label for="reason" class="block text-xs text-gray-600 dark:text-gray-400 mb-0.5">Reason <span class="text-red-500">*</span></label>
                                                    <input type="text" name="reason" id="reason" value="{{ old('reason', auth()->user()->unavailability_reason) }}" placeholder="Enter reason for unavailability" required class="w-full px-2 py-1 text-xs border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 @error('reason') border-red-500 @enderror">
                                                    @error('reason')
                                                        <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="mb-1">
                                                    <label for="unavailable_until" class="block text-xs text-gray-600 dark:text-gray-400 mb-0.5">Unavailable Until</label>
                                                    <input type="date" name="unavailable_until" id="unavailable_until" value="{{ old('unavailable_until', auth()->user()->unavailable_until ? auth()->user()->unavailable_until->format('Y-m-d') : '') }}" class="w-full px-2 py-1 text-xs border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                                </div>
                                            @else
                                                <!-- Currently Available - Hide fields initially, show when clicking "Mark Unavailable" -->
                                                <div id="unavailableFields" style="display: none;" class="mb-1">
                                                    <div class="mb-1">
                                                        <label for="reason_new" class="block text-xs text-gray-600 dark:text-gray-400 mb-0.5">Reason <span class="text-red-500">*</span></label>
                                                        <input type="text" name="reason" id="reason_new" placeholder="Enter reason for unavailability" required class="w-full px-2 py-1 text-xs border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 @error('reason') border-red-500 @enderror">
                                                        @error('reason')
                                                            <p class="text-red-500 text-xs mt-0.5">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-1">
                                                        <label for="unavailable_until_new" class="block text-xs text-gray-600 dark:text-gray-400 mb-0.5">Unavailable Until</label>
                                                        <input type="date" name="unavailable_until" id="unavailable_until_new" class="w-full px-2 py-1 text-xs border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                                    </div>
                                                </div>
                                            @endif
                                            <button type="button" id="availabilityToggleBtn" class="w-full text-xs px-2 py-1 rounded {{ auth()->user()->isAvailableForFollowup() ? 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-200' }}">
                                                {{ auth()->user()->isAvailableForFollowup() ? 'Mark Unavailable' : 'Mark Available' }}
                                            </button>
                                        </form>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const form = document.getElementById('availabilityForm');
                                                const toggleBtn = document.getElementById('availabilityToggleBtn');
                                                const isAvailableInput = document.getElementById('is_available_input');
                                                const unavailableFields = document.getElementById('unavailableFields');
                                                
                                                if (toggleBtn && form && isAvailableInput) {
                                                    // Track if fields are currently shown
                                                    let fieldsShown = false;
                                                    
                                                    toggleBtn.addEventListener('click', function(e) {
                                                        e.preventDefault();
                                                        
                                                        // Check current button text to determine state
                                                        const buttonText = toggleBtn.textContent.trim();
                                                        
                                                        // If button says "Mark Unavailable", show fields first
                                                        if (buttonText === 'Mark Unavailable') {
                                                            if (unavailableFields) {
                                                                unavailableFields.style.display = 'block';
                                                                fieldsShown = true;
                                                                toggleBtn.textContent = 'Submit';
                                                                toggleBtn.className = 'w-full text-xs px-2 py-1 rounded bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-200';
                                                                
                                                                // Focus on reason input
                                                                setTimeout(function() {
                                                                    const reasonField = document.getElementById('reason_new');
                                                                    if (reasonField) {
                                                                        reasonField.focus();
                                                                    }
                                                                }, 100);
                                                            }
                                                            return; // Don't submit yet, wait for "Submit" click
                                                        }
                                                        
                                                        // Button says "Submit" or "Mark Available" - proceed with submission
                                                        if (buttonText === 'Submit') {
                                                            // Validating and submitting as unavailable
                                                            const reasonField = document.getElementById('reason_new') || document.getElementById('reason');
                                                            if (!reasonField || !reasonField.value.trim()) {
                                                                alert('Please provide a reason for unavailability.');
                                                                if (reasonField) {
                                                                    reasonField.focus();
                                                                }
                                                                return false;
                                                            }
                                                            isAvailableInput.value = '0'; // Set to unavailable
                                                            // Submit the form
                                                            form.submit();
                                                        } else if (buttonText === 'Mark Available') {
                                                            // Submitting as available - no reason needed
                                                            isAvailableInput.value = '1'; // Set to available
                                                            // Submit the form
                                                            form.submit();
                                                        }
                                                    });
                                                    
                                                    // Form submission validation (as backup)
                                                    form.addEventListener('submit', function(e) {
                                                        const isMarkingUnavailable = isAvailableInput.value === '0';
                                                        
                                                        if (isMarkingUnavailable) {
                                                            // Check both possible reason field IDs
                                                            const reasonField = document.getElementById('reason') || document.getElementById('reason_new');
                                                            const reason = reasonField ? reasonField.value.trim() : '';
                                                            
                                                            if (!reason) {
                                                                e.preventDefault();
                                                                alert('Please provide a reason for unavailability.');
                                                                if (reasonField) {
                                                                    reasonField.focus();
                                                                }
                                                                return false;
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        </script>
                                    </div>
                                    <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" @click="open = false" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <svg class="w-4 h-4 mr-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-3 sm:p-4 md:p-6 bg-gray-50 dark:bg-gray-900 min-h-screen overflow-x-hidden">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if (isset($showTodoBlur) && $showTodoBlur)
    <div class="blur-overlay">
        <div class="todo-card">
            @yield('content')
        </div>
    </div>
@else
    @yield('content')
@endif

            </main>
        </div>
    </div>

    @yield('scripts')
    
    <script>
        function toggleSubmenu(menuId) {
            const submenu = document.getElementById(menuId);
            if (!submenu) return;
            
            const menuItem = submenu.previousElementSibling;
            if (!menuItem) return;
            
            const arrow = menuItem.querySelector('.arrow-icon');
            if (!arrow) return;
            
            // Close all other submenus first
            const allSubmenus = document.querySelectorAll('ul[id]');
            allSubmenus.forEach(sub => {
                if (sub.id !== menuId) {
                    sub.classList.add('hidden');
                    const previousElement = sub.previousElementSibling;
                    if (previousElement) {
                        const otherArrow = previousElement.querySelector('.arrow-icon');
                    if (otherArrow) {
                        otherArrow.style.transform = 'rotate(0deg)';
                        }
                    }
                }
            });
            
            // Toggle current submenu
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
                arrow.style.transition = 'transform 0.3s ease';
            } else {
                submenu.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
                arrow.style.transition = 'transform 0.3s ease';
            }
        }

        function checkIfCurrentModule(menuId) {
            const currentRoute = window.location.pathname;
            const routeSegments = currentRoute.split('/').filter(segment => segment);
            
            // Define module route patterns
            const modulePatterns = {
                'crm': ['leads', 'quotations', 'documents', 'tasks', 'invoices', 'reports', 'crm', 'mobile-crm', 'notifications', 'costing', 'channel-partners', 'commissions', 'escalations'],
                'project': ['projects', 'activities', 'resource-allocations', 'expenses', 'mobile', 'project-profitabilities', 'budgets', 'budget-categories', 'payment-milestones', 'contractors', 'material-requests', 'material-consumptions', 'analytics', 'dpr', 'site-warehouses'],
                'purchase': ['purchase-orders', 'purchase-requisitions', 'vendors', 'products', 'payment-requests', 'rfqs', 'vendor-registrations'],
                'inventory': ['inventory', 'grns', 'warehouses', 'stock-ledger', 'quality-check', 'stock-valuation', 'inventory-audit'],
                'om': ['om', 'complaints', 'amc'],
                'hr': ['hr', 'employees', 'leaves', 'attendance', 'payroll', 'salary-slips', 'expense-claims', 'recruitment', 'appraisals', 'performance']
            };
            
            if (modulePatterns[menuId]) {
                return modulePatterns[menuId].some(pattern => {
                    return routeSegments.some(segment => segment.includes(pattern));
                });
            }
            
            return false;
        }

        // Auto-open the correct module on page load
        document.addEventListener('DOMContentLoaded', function() {
            const modules = ['crm', 'tele-sales-crm', 'project', 'purchase', 'inventory', 'om', 'hr'];
            
            modules.forEach(moduleId => {
                if (checkIfCurrentModule(moduleId === 'tele-sales-crm' ? 'crm' : moduleId)) {
                    const submenu = document.getElementById(moduleId);
                    if (!submenu) return;
                    
                    const menuItem = submenu.previousElementSibling;
                    if (!menuItem) return;
                    
                    const arrow = menuItem.querySelector('.arrow-icon');
                    if (!arrow) return;
                    
                    submenu.classList.remove('hidden');
                    arrow.style.transform = 'rotate(180deg)';
                    arrow.style.transition = 'transform 0.3s ease';
                }
            });
        });

        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            if (sidebar && overlay) {
                sidebar.classList.toggle('-translate-x-full');
                sidebar.classList.toggle('translate-x-0');
                overlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            }
        }

        function closeMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            if (sidebar && overlay) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
        
        // Close mobile menu when clicking on a link
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('#sidebar a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        closeMobileMenu();
                    }
                });
            });
        });

        function configureTodoLoginModal(data) {
            const modal = document.getElementById('todoLoginModal');
            if (!modal) return;

            const formContainer = document.getElementById('todoLoginFormContainer');
            const alreadyAddedContainer = document.getElementById('todoLoginAlreadyAdded');

            if (data.has_today_todos) {
                // User already has tasks for today – show info/shortcut instead of forcing new task
                if (formContainer) formContainer.classList.add('hidden');
                if (alreadyAddedContainer) alreadyAddedContainer.classList.remove('hidden');
            } else {
                // No tasks yet for today – show add-task form
                if (formContainer) formContainer.classList.remove('hidden');
                if (alreadyAddedContainer) alreadyAddedContainer.classList.add('hidden');
            }
        }

        // Todo Login Popup Modal
        window.showTodoLoginModal = function() {
            fetch('{{ route("todos.modal") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.show_modal || !data.has_today_todos) {
                        const modal = document.getElementById('todoLoginModal');
                        if (modal) {
                            configureTodoLoginModal(data);
                            modal.classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                            document.body.classList.add('no-animations');
                        }
                    }
                });
        };

        // Check for incomplete todos on page load - always enforce from server
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("todos.modal") }}')
                .then(response => response.json())
                .then(data => {
                    // Always show modal if user has no todos for today
                    if (data.show_modal || !data.has_today_todos) {
                        const modal = document.getElementById('todoLoginModal');
                        if (modal) {
                            configureTodoLoginModal(data);
                            modal.classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                            document.body.classList.add('no-animations');
                        }
                    }
                });
        });

        function closeTodoLoginModal() {
            const modal = document.getElementById('todoLoginModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                document.body.classList.remove('no-animations');
            }
        }

        // Check todos before logout
        function checkTodosBeforeLogout(event) {
            event.preventDefault();
            
            fetch('{{ route("todos.check") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.has_incomplete) {
                        let errorMessage = data.message || 'You cannot logout until all your to-do tasks are properly updated.';
                        
                        if (data.not_completed_without_reason > 0) {
                            errorMessage += '\n\nYou have ' + data.not_completed_without_reason + ' not completed task(s) without reason. Please provide a reason for not completing them.';
                        }
                        
                        if (data.completed_without_remarks > 0) {
                            errorMessage += '\n\nYou have ' + data.completed_without_remarks + ' completed task(s) without remarks. Please add remarks for completed tasks.';
                        }
                        
                        alert(errorMessage);
                        window.location.href = '{{ route("todos.index") }}';
                        return false;
                    } else {
                        // Proceed with logout
                        const form = event.target.closest('form');
                        if (form) {
                            form.submit();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking todos:', error);
                    // Allow logout if there's an error
                    const form = event.target.closest('form');
                    if (form) {
                        form.submit();
                    }
                });
            
            return false;
        }

        // Handle todo login form submission
        function handleTodoLoginSubmit(event) {
            const title = document.getElementById('todoLoginTitle').value.trim();
            if (!title) {
                event.preventDefault();
                alert('Please add at least one task before continuing.');
                return false;
            }
            // Form will submit normally, and server will redirect
            return true;
        }
    </script>

    <!-- Todo Login Popup Modal -->
    <div id="todoLoginModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daily To-Do List</h2>
            </div>

            <p class="text-yellow-600 dark:text-yellow-400 text-sm mb-4 bg-yellow-50 dark:bg-yellow-900/20 p-3 rounded">
                <strong>Important:</strong> When you update task status, make sure to:
                <ul class="list-disc list-inside mt-2 space-y-1">
                    <li>Add remarks for completed tasks</li>
                    <li>Provide a reason for not completed tasks</li>
                </ul>
                You cannot logout until all tasks have proper status updates with required information.
            </p>

            <!-- State when user has NOT yet added tasks for today -->
            <div id="todoLoginFormContainer">
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    Please add at least one task to your to-do list for today. You cannot proceed until you add at least one task.
                </p>

                <form id="todoLoginForm" action="{{ route('todos.store') }}" method="POST" onsubmit="handleTodoLoginSubmit(event)">
                    @csrf
                    <input type="hidden" name="from_modal" value="1">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Task Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="todoLoginTitle" name="title" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Enter your task title...">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Priority
                        </label>
                        <select name="priority" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="submit" name="add_more" value="1"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors">
                            Add Task & Add More
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            Add Task & Continue
                        </button>
                    </div>
                </form>
            </div>

            <!-- State when user has ALREADY added tasks for today -->
            <div id="todoLoginAlreadyAdded" class="hidden">
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    You have already added your daily to-do tasks for today.
                </p>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    You can review or update them from your "My To-Do List" page.
                </p>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('todos.index') }}" 
                       class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors">
                        View Today's Tasks
                    </a>
                    <button type="button" onclick="closeTodoLoginModal()"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
