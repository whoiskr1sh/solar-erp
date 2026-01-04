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
                    <h1 class="text-xl font-bold text-gray-900">Profile</h1>
                    <p class="text-sm text-gray-600">Manage your account</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="text-center">
            <div class="bg-teal-100 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $user->name }}</h2>
            <p class="text-gray-600 mb-4">{{ $user->email }}</p>
            <span class="bg-teal-100 text-teal-800 px-3 py-1 rounded-full text-sm font-medium">
                {{ ucfirst($user->role ?? 'User') }}
            </span>
        </div>
    </div>

    <!-- Profile Form -->
    <form method="POST" action="{{ route('mobile-crm.profile.update') }}" class="space-y-6" onsubmit="return confirm('Are you sure you want to update your profile?')">
        @csrf
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Account Stats -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Statistics</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $user->leads()->count() }}</div>
                    <div class="text-sm text-gray-600">Leads Created</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $user->tasks()->count() }}</div>
                    <div class="text-sm text-gray-600">Tasks Assigned</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $user->projects()->count() }}</div>
                    <div class="text-sm text-gray-600">Projects Managed</div>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">{{ $user->created_at->diffInDays(now()) }}</div>
                    <div class="text-sm text-gray-600">Days Active</div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-4">
            <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 cursor-pointer">
                Update Profile
            </button>
            <a href="{{ route('mobile-crm.index') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors duration-200 cursor-pointer">
                Cancel
            </a>
        </div>
    </form>

    <!-- Additional Actions -->
    <div class="mt-6 space-y-3">
        <button type="button" onclick="changePassword()" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 cursor-pointer">
            Change Password
        </button>
        <button type="button" onclick="downloadUserData()" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 cursor-pointer">
            Download Data
        </button>
    </div>
</div>

<script>
function changePassword() {
    const newPassword = prompt('Enter new password:');
    if (newPassword && newPassword.length >= 6) {
        // Here you would typically make an AJAX call to update password
        alert('Password change functionality will be implemented soon!');
    } else if (newPassword) {
        alert('Password must be at least 6 characters long!');
    }
}

function downloadUserData() {
    // Ask user for format
    const format = confirm('Click OK for JSON format, Cancel for CSV format');
    
    if (format) {
        downloadJSON();
    } else {
        downloadCSV();
    }
}

function downloadJSON() {
    // Create user data object
    const userData = {
        name: '{{ $user->name }}',
        email: '{{ $user->email }}',
        phone: '{{ $user->phone }}',
        department: '{{ $user->department }}',
        designation: '{{ $user->designation }}',
        joining_date: '{{ $user->joining_date }}',
        last_login: '{{ $user->last_login_at }}',
        leads_created: {{ $user->leads()->count() }},
        tasks_assigned: {{ $user->tasks()->count() }},
        projects_managed: {{ $user->projects()->count() }},
        invoices_created: {{ $user->invoices()->count() }},
        quotations_created: {{ $user->quotations()->count() }},
        documents_created: {{ $user->documents()->count() }},
        vendors_created: {{ $user->vendors()->count() }},
        export_date: new Date().toISOString()
    };

    // Convert to JSON
    const dataStr = JSON.stringify(userData, null, 2);
    
    // Create blob and download
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    
    // Create download link
    const link = document.createElement('a');
    link.href = url;
    link.download = 'user_data_{{ $user->name }}_{{ date("Y-m-d") }}.json';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Clean up
    URL.revokeObjectURL(url);
    
    alert('User data downloaded as JSON successfully!');
}

function downloadCSV() {
    // Create CSV data
    const csvData = [
        ['Field', 'Value'],
        ['Name', '{{ $user->name }}'],
        ['Email', '{{ $user->email }}'],
        ['Phone', '{{ $user->phone }}'],
        ['Department', '{{ $user->department }}'],
        ['Designation', '{{ $user->designation }}'],
        ['Joining Date', '{{ $user->joining_date }}'],
        ['Last Login', '{{ $user->last_login_at }}'],
        ['Leads Created', {{ $user->leads()->count() }}],
        ['Tasks Assigned', {{ $user->tasks()->count() }}],
        ['Projects Managed', {{ $user->projects()->count() }}],
        ['Invoices Created', {{ $user->invoices()->count() }}],
        ['Quotations Created', {{ $user->quotations()->count() }}],
        ['Documents Created', {{ $user->documents()->count() }}],
        ['Vendors Created', {{ $user->vendors()->count() }}],
        ['Export Date', new Date().toISOString()]
    ];

    // Convert to CSV string
    const csvString = csvData.map(row => row.map(field => `"${field}"`).join(',')).join('\n');
    
    // Create blob and download
    const dataBlob = new Blob([csvString], {type: 'text/csv'});
    const url = URL.createObjectURL(dataBlob);
    
    // Create download link
    const link = document.createElement('a');
    link.href = url;
    link.download = 'user_data_{{ $user->name }}_{{ date("Y-m-d") }}.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Clean up
    URL.revokeObjectURL(url);
    
    alert('User data downloaded as CSV successfully!');
}
</script>
@endsection
