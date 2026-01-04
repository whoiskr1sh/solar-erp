@extends('layouts.app')

@section('title', 'HR Manager Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div id="welcome-header" class="bg-gradient-to-r from-pink-600 to-purple-600 rounded-xl shadow-lg border-0 p-8 text-white transition-all duration-500 transform">
        <div class="flex items-center justify-between">
            <div class="space-y-2">
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-md">HR Active</span>
                    <span class="text-pink-100 text-sm opacity-80">{{ now()->format('l, M d, Y') }}</span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-pink-50 text-lg font-medium opacity-90">HR Manager - Human Resources Management</p>
                <div class="flex items-center space-x-4 mt-4 pt-4 border-t border-white/10">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse mr-2"></div>
                        <span class="text-sm text-pink-100 italic">Department: {{ auth()->user()->department ?? 'Human Resources' }}</span>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block relative">
                <div class="w-32 h-32 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20 shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-300">
                    <i class="fas fa-users-cog text-6xl text-white drop-shadow-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <a href="{{ route('hr.employee-management') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-pink-50 dark:bg-pink-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-user-plus text-pink-600 dark:text-pink-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Add Employee</span>
        </a>
        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'hr-manager'))]) }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-calendar-times text-yellow-600 dark:text-yellow-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Leave Request</span>
        </a>
        <a href="{{ route('hr.attendance') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-calendar-check text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Mark Attendance</span>
        </a>
        <a href="{{ route('hr.salary-payroll') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-rupee-sign text-emerald-600 dark:text-emerald-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Process Payroll</span>
        </a>
        <a href="{{ route('hr.performance-management') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-chart-bar text-orange-600 dark:text-orange-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Performance</span>
        </a>
        <button onclick="window.print()" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 bg-gray-50 dark:bg-gray-700 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-download text-gray-600 dark:text-gray-400 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">HR Report</span>
        </button>
    </div>

    <!-- HR Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Employees Card -->
        <a href="{{ route('hr.employee-management') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-pink-50 dark:bg-pink-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-pink-600 dark:text-pink-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Employees</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_employees']) }}</p>
                    </div>
                </div>
                <div class="text-pink-600 dark:text-pink-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-pink-600 dark:text-pink-400 font-medium">{{ $stats['active_employees'] }} Active</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-user-check mr-1"></i>
                    Workforce
                </div>
            </div>
        </a>

        <!-- Today's Attendance Card -->
        <a href="{{ route('hr.attendance') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-check text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Today's Attendance</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['today_attendance']) }}</p>
                    </div>
                </div>
                <div class="text-green-600 dark:text-green-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-green-600 dark:text-green-400 font-medium">{{ $stats['total_employees'] > 0 ? round(($stats['today_attendance'] / $stats['total_employees']) * 100, 1) : 0 }}% Present</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-percentage mr-1"></i>
                    Attendance Rate
                </div>
            </div>
        </a>

        <!-- Leave Requests Card -->
        <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'hr-manager'))]) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-times text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Leave Requests</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['leave_requests']) }}</p>
                    </div>
                </div>
                <div class="text-yellow-600 dark:text-yellow-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">{{ $stats['pending_leaves'] }} Pending</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-hourglass-half mr-1"></i>
                    Awaiting Approval
                </div>
            </div>
        </a>

        <!-- Total Payroll Card -->
        <a href="{{ route('hr.salary-payroll') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-rupee-sign text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Payroll</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($stats['total_payroll']) }}</p>
                    </div>
                </div>
                <div class="text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">Records</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-file-invoice mr-1"></i>
                    Salary Management
                </div>
            </div>
        </a>
    </div>

    <!-- HR Performance Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Employee Satisfaction Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-heart text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Employee Satisfaction</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">4.6/5</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">Excellent</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-star mr-1"></i>
                    Satisfaction Rating
                </div>
            </div>
        </div>

        <!-- Retention Rate Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-shield text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Retention Rate</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">94%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">High Retention</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-chart-line mr-1"></i>
                    Employee Loyalty
                </div>
            </div>
        </div>

        <!-- Training Completion Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-graduation-cap text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Training Completion</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">87%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-purple-600 dark:text-purple-400 font-medium">On Track</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-book mr-1"></i>
                    Skill Development
                </div>
            </div>
        </div>

        <!-- Performance Reviews Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chart-bar text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Performance Reviews</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">92%</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-orange-600 dark:text-orange-400 font-medium">Completed</span>
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-clipboard-check mr-1"></i>
                    Review Cycle
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Employees -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Employees</h3>
                <a href="{{ route('hr.employee-management') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentEmployees as $employee)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-pink-600 dark:text-pink-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->designation ?? 'No Designation' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full">
                        {{ ucfirst($employee->status ?? 'Active') }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No recent employees</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Leave Requests -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Leave Requests</h3>
                <a href="{{ route('hr.leave-management', ['role' => strtolower(str_replace(' ', '-', auth()->user()->roles->first()->name ?? 'hr-manager'))]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentLeaveRequests as $leave)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-times text-yellow-600 dark:text-yellow-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $leave->employee->name ?? 'Unknown Employee' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $leave->leave_type ?? 'Leave Request' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 rounded-full">
                        {{ ucfirst($leave->status) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No recent leave requests</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Attendance</h3>
                <a href="{{ route('hr.attendance') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentAttendance as $attendance)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $attendance->employee->name ?? 'Unknown Employee' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $attendance->date ? \Carbon\Carbon::parse($attendance->date)->format('M d, Y') : 'No Date' }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full">
                        {{ ucfirst($attendance->status ?? 'Present') }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No recent attendance</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection


