@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Channel Partners</h1>
            <p class="text-gray-600">Manage your channel partners and distributors</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="exportChannelPartners()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Export CSV
            </button>
            <a href="{{ route('channel-partners.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                Add Partner
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-4">
        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-blue-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Total</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-green-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Active</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-yellow-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Pending</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['pending'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-red-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Suspended</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['suspended'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-purple-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">Commission</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['total_commission'] }}%</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="text-center">
                <div class="bg-indigo-100 p-2 rounded-lg w-fit mx-auto mb-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-600">This Month</p>
                <p class="text-lg font-bold text-gray-900">{{ $stats['this_month'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-3 mb-4">
        <form method="GET" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search partners..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Partner Type</label>
                <select name="partner_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Types</option>
                    <option value="distributor" {{ request('partner_type') == 'distributor' ? 'selected' : '' }}>Distributor</option>
                    <option value="dealer" {{ request('partner_type') == 'dealer' ? 'selected' : '' }}>Dealer</option>
                    <option value="installer" {{ request('partner_type') == 'installer' ? 'selected' : '' }}>Installer</option>
                    <option value="consultant" {{ request('partner_type') == 'consultant' ? 'selected' : '' }}>Consultant</option>
                    <option value="other" {{ request('partner_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                <select name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <option value="all">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            
            <div class="lg:col-span-6 flex justify-end space-x-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                    Apply Filters
                </button>
                <a href="{{ route('channel-partners.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Partners Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-xs table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Partner #</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Company</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Contact</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Type</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Status</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Commission</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Assigned To</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Created At</th>
                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($partners as $partner)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-2">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $partner->partner_code }}">{{ $partner->partner_code }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs text-gray-900 truncate" title="{{ $partner->company_name }}">{{ $partner->company_name }}</div>
                            <div class="text-xs text-gray-500 truncate" title="{{ $partner->email }}">{{ $partner->email }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs text-gray-900 truncate" title="{{ $partner->contact_person }}">{{ $partner->contact_person }}</div>
                            <div class="text-xs text-gray-500 truncate" title="{{ $partner->phone }}">{{ $partner->phone }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $partner->partner_type_badge }}" title="{{ ucfirst($partner->partner_type) }}">
                                {{ ucfirst($partner->partner_type) }}
                            </span>
                        </td>
                        <td class="px-2 py-2">
                            <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $partner->status_badge }}" title="{{ ucfirst($partner->status) }}">
                                {{ ucfirst($partner->status) }}
                            </span>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs font-medium text-gray-900 truncate" title="{{ $partner->formatted_commission_rate }}">{{ $partner->formatted_commission_rate }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs text-gray-900 truncate" title="{{ $partner->assignedUser->name ?? 'Unassigned' }}">{{ $partner->assignedUser->name ?? 'Unassigned' }}</div>
                        </td>
                        <td class="px-2 py-2">
                            <div class="text-xs text-gray-900 truncate" title="{{ $partner->created_at->format('M d, Y h:i A') }}">{{ $partner->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ $partner->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-2 py-2 text-xs font-medium">
                            <div class="flex flex-wrap gap-0.5 truncate">
                                <a href="{{ route('channel-partners.show', $partner) }}" class="text-teal-600 hover:text-teal-900 text-xs whitespace-nowrap px-1">V</a>
                                <a href="{{ route('channel-partners.edit', $partner) }}" class="text-blue-600 hover:text-blue-900 text-xs whitespace-nowrap px-1">E</a>
                                @if($partner->status === 'active')
                                <form method="POST" action="{{ route('channel-partners.deactivate', $partner) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 text-xs whitespace-nowrap px-1">D</button>
                                </form>
                                @elseif($partner->status === 'inactive')
                                <form method="POST" action="{{ route('channel-partners.activate', $partner) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 text-xs whitespace-nowrap px-1">A</button>
                                </form>
                                @endif
                                @if($partner->status !== 'suspended')
                                <form method="POST" action="{{ route('channel-partners.suspend', $partner) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs whitespace-nowrap px-1">S</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('channel-partners.destroy', $partner) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this partner?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs whitespace-nowrap px-1">D</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-2 py-6 text-center">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">No partners found</h3>
                            <p class="text-xs text-gray-600">Get started by adding your first channel partner.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($partners->hasPages())
        <div class="px-2 py-2 border-t border-gray-200">
            {{ $partners->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function exportChannelPartners() {
    // Get current filter values
    const search = document.querySelector('input[name="search"]').value;
    const status = document.querySelector('select[name="status"]').value;
    const partnerType = document.querySelector('select[name="partner_type"]').value;
    const assignedTo = document.querySelector('select[name="assigned_to"]').value;
    const startDate = document.querySelector('input[name="start_date"]').value;
    const endDate = document.querySelector('input[name="end_date"]').value;
    
    // Build query string
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (status && status !== 'all') params.append('status', status);
    if (partnerType && partnerType !== 'all') params.append('partner_type', partnerType);
    if (assignedTo && assignedTo !== 'all') params.append('assigned_to', assignedTo);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    
    // Create export URL
    const exportUrl = '{{ route("channel-partners.export") }}' + (params.toString() ? '?' + params.toString() : '');
    
    // Create hidden link and trigger download
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = 'channel_partners_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Show success message
    setTimeout(() => {
        // You can add a toast notification here if you have one
        console.log('Export completed successfully!');
    }, 1000);
}
</script>
@endsection
