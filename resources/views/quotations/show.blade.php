@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quotation Details</h1>
            <p class="text-gray-600">Quotation #{{ $quotation->quotation_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('quotations.create-revision', $quotation) }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Create Revision
            </a>
            <a href="{{ route('quotations.edit', $quotation) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('quotations.pdf', $quotation) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>
            <a href="{{ route('quotations.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    <!-- Status Alert -->
    @if($quotation->is_expired)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong>Expired:</strong> This quotation has expired on {{ $quotation->valid_until->format('M d, Y') }}.
        </div>
    @elseif($quotation->days_remaining <= 7)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
            <strong>Expiring Soon:</strong> This quotation will expire in {{ $quotation->days_remaining }} days.
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Client Name</label>
                        <p class="text-sm text-gray-900">{{ $quotation->client->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Company</label>
                        <p class="text-sm text-gray-900">{{ $quotation->client->company ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="text-sm text-gray-900">{{ $quotation->client->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="text-sm text-gray-900">{{ $quotation->client->phone }}</p>
                    </div>
                    @if($quotation->client->address)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <p class="text-sm text-gray-900">{{ $quotation->client->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Project Information -->
            @if($quotation->project)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Project Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Project Name</label>
                            <p class="text-sm text-gray-900">{{ $quotation->project->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Project Code</label>
                            <p class="text-sm text-gray-900">{{ $quotation->project->project_code }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $quotation->project->status_badge }}">
                                {{ ucfirst($quotation->project->status) }}
                            </span>
                        </div>
                        @if($quotation->project->location)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Location</label>
                                <p class="text-sm text-gray-900">{{ $quotation->project->location }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Sample items - in real app, these would come from quotation_items table -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Solar Panel Installation</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">&#8377;{{ number_format($quotation->subtotal, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">&#8377;{{ number_format($quotation->subtotal, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes & Terms -->
            @if($quotation->notes || $quotation->terms_conditions)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    @if($quotation->notes)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <p class="text-sm text-gray-900">{{ $quotation->notes }}</p>
                        </div>
                    @endif
                    @if($quotation->terms_conditions)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                            <p class="text-sm text-gray-900">{{ $quotation->terms_conditions }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                <div class="space-y-4">
                    <div>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $quotation->status_badge }}">
                            {{ ucfirst($quotation->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created Date</label>
                        <p class="text-sm text-gray-900">{{ $quotation->quotation_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Valid Until</label>
                        <p class="text-sm text-gray-900">{{ $quotation->valid_until->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created By</label>
                        <p class="text-sm text-gray-900">{{ $quotation->creator->name }}</p>
                    </div>
                    @if($quotation->last_modified_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Modified</label>
                        <p class="text-sm text-gray-900">{{ $quotation->last_modified_at->setTimezone('Asia/Kolkata')->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Amount Summary -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Amount Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Subtotal:</span>
                        <span class="text-sm font-medium text-gray-900">&#8377;{{ number_format($quotation->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tax (18%):</span>
                        <span class="text-sm font-medium text-gray-900">&#8377;{{ number_format($quotation->tax_amount, 2) }}</span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="text-base font-medium text-gray-900">Total:</span>
                            <span class="text-base font-bold text-gray-900">&#8377;{{ number_format($quotation->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revision History -->
            @if($allRevisions && $allRevisions->count() > 1)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Revision History</h3>
                    @if($latestQuotation && $latestQuotation->id == $quotation->id && $latestQuotation->is_latest)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Latest Revision
                        </span>
                    @endif
                </div>
                <div class="space-y-2">
                    @foreach($allRevisions as $rev)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg {{ $rev->id == $quotation->id ? 'bg-blue-50 border-blue-300' : '' }}">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('quotations.show', $rev) }}" class="text-sm font-medium {{ $rev->id == $quotation->id ? 'text-blue-600' : 'text-gray-900 hover:text-blue-600' }}">
                                    {{ $rev->quotation_number }}
                                    @if($rev->is_revision)
                                        <span class="text-xs text-gray-500">(Rev. {{ $rev->revision_number }})</span>
                                    @else
                                        <span class="text-xs text-gray-500">(Original)</span>
                                    @endif
                                </a>
                                @if($rev->is_latest)
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Latest
                                    </span>
                                @endif
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                {{ $rev->quotation_date->format('M d, Y') }} • 
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $rev->status_badge }}">
                                    {{ ucfirst($rev->status) }}
                                </span>
                            </div>
                        </div>
                        @if($rev->id != $quotation->id)
                        <a href="{{ route('quotations.show', $rev) }}" class="text-xs text-blue-600 hover:text-blue-800">
                            View
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('quotations.create-revision', $quotation) }}" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Create Revision
                    </a>
                    
                    <a href="{{ route('quotations.preview', $quotation) }}" target="_blank" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Preview PDF
                    </a>
                    
                    @if($quotation->status == 'sent')
                        <form method="POST" action="{{ route('quotations.convert-to-invoice', $quotation) }}">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Convert to Invoice
                            </button>
                        </form>
                    @endif
                    
                    <form method="POST" action="{{ route('quotations.send-email', $quotation) }}">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Send Email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
