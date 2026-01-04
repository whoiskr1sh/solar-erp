@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Employee Self Service Portal</h1>
                    <p class="text-gray-600 mt-1">Employee self-service dashboard and tools</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Apply Leave</h3>
                        <p class="text-sm text-gray-500">Submit leave requests</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Mark Attendance</h3>
                        <p class="text-sm text-gray-500">Check in/out daily</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-invoice text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">View Payslips</h3>
                        <p class="text-sm text-gray-500">Download salary slips</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-receipt text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Expense Claims</h3>
                        <p class="text-sm text-gray-500">Submit expense claims</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Employee ID:</span>
                        <span class="text-sm font-medium text-gray-900">EMP001</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Name:</span>
                        <span class="text-sm font-medium text-gray-900">Rajesh Kumar</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Department:</span>
                        <span class="text-sm font-medium text-gray-900">Operations</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Designation:</span>
                        <span class="text-sm font-medium text-gray-900">Senior Technician</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Joining Date:</span>
                        <span class="text-sm font-medium text-gray-900">Jan 15, 2024</span>
                    </div>
                </div>
            </div>

            <!-- Leave Balance -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Leave Balance</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Annual Leave:</span>
                        <span class="text-sm font-medium text-gray-900">15 days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Sick Leave:</span>
                        <span class="text-sm font-medium text-gray-900">7 days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Personal Leave:</span>
                        <span class="text-sm font-medium text-gray-900">5 days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Total Available:</span>
                        <span class="text-sm font-medium text-green-600">27 days</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activities</h3>
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Leave Request Submitted</p>
                        <p class="text-sm text-gray-500">Sick leave for 2 days - Oct 15-16, 2025</p>
                    </div>
                    <div class="ml-auto">
                        <span class="text-xs text-gray-500">2 days ago</span>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Expense Claim Approved</p>
                        <p class="text-sm text-gray-500">Travel expenses - ₹2,500</p>
                    </div>
                    <div class="ml-auto">
                        <span class="text-xs text-gray-500">5 days ago</span>
                    </div>
                </div>

                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-invoice text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Payslip Generated</p>
                        <p class="text-sm text-gray-500">September 2025 salary slip</p>
                    </div>
                    <div class="ml-auto">
                        <span class="text-xs text-gray-500">1 week ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




