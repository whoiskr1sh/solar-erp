@extends('layouts.app')

@section('title', 'Material Request Details')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $materialRequest->title }}</h1>
            <p class="text-gray-600">Request #{{ $materialRequest->request_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('material-requests.edit', $materialRequest) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Edit Request
            </a>
            <a href="{{ route('material-requests.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                Back to Requests
            </a>
        </div>
    </div>

    <!-- Status and Priority Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Status</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $materialRequest->status_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $materialRequest->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Priority</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $materialRequest->priority_badge }}">
                        {{ ucfirst($materialRequest->priority) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Category</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $materialRequest->category_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $materialRequest->category)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Type</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $materialRequest->type_badge }}">
                        {{ ucfirst($materialRequest->request_type) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Request Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Request Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Request Number</label>
                        <p class="text-gray-900 font-medium">{{ $materialRequest->request_number }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                        <p class="text-gray-900">{{ $materialRequest->project ? $materialRequest->project->project_name : 'No Project Assigned' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Required Date</label>
                        <p class="text-gray-900">
                            {{ $materialRequest->required_date ? $materialRequest->required_date->format('M d, Y') : 'Not Set' }}
                            @if($materialRequest->is_overdue)
                                <span class="text-red-600 text-sm ml-2">(Overdue)</span>
                            @elseif($materialRequest->is_urgent)
                                <span class="text-orange-600 text-sm ml-2">(Urgent)</span>
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urgency Reason</label>
                        <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $materialRequest->urgency_reason)) }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <p class="text-gray-900">{{ $materialRequest->description ?: 'No description provided' }}</p>
                </div>

                @if($materialRequest->justification)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Justification</label>
                    <p class="text-gray-900">{{ $materialRequest->justification }}</p>
                </div>
                @endif

                @if($materialRequest->notes)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <p class="text-gray-900">{{ $materialRequest->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Materials List -->
            @if($materialRequest->materials->count() > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Requested Materials</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specification</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($materialRequest->materials as $material)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $material->description ?: 'No description' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $material->specification ?: 'No specification' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $material->quantity }} {{ $material->unit ?: 'units' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₹{{ number_format($material->unit_price, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">₹{{ number_format($material->total_price, 2) }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total Amount:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">₹{{ number_format($materialRequest->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Financial Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Financial Summary</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Amount:</span>
                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($materialRequest->total_amount, 2) }}</span>
                    </div>
                    
                    @if($materialRequest->approved_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Approved Amount:</span>
                        <span class="text-sm font-medium text-green-600">₹{{ number_format($materialRequest->approved_amount, 2) }}</span>
                    </div>
                    @endif
                    
                    @if($materialRequest->consumed_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Consumed Amount:</span>
                        <span class="text-sm font-medium text-blue-600">₹{{ number_format($materialRequest->consumed_amount, 2) }}</span>
                    </div>
                    @endif
                </div>

                @if($materialRequest->total_amount > 0)
                <div class="mt-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Approval Progress</span>
                        <span class="text-gray-900">{{ $materialRequest->approval_percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $materialRequest->approval_percentage }}%"></div>
                    </div>
                </div>
                @endif
            </div>

            <!-- People -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">People</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Requested By</label>
                        <p class="text-gray-900">{{ $materialRequest->requester ? $materialRequest->requester->name : 'Unknown' }}</p>
                        <p class="text-sm text-gray-500">{{ $materialRequest->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    @if($materialRequest->approver)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Approved By</label>
                        <p class="text-gray-900">{{ $materialRequest->approver->name }}</p>
                        @if($materialRequest->approved_date)
                        <p class="text-sm text-gray-500">{{ $materialRequest->approved_date->format('M d, Y H:i') }}</p>
                        @endif
                    </div>
                    @endif
                    
                    @if($materialRequest->assignee)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                        <p class="text-gray-900">{{ $materialRequest->assignee->name }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if(in_array($materialRequest->status, ['draft', 'pending']))
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
                
                <div class="space-y-3">
                    @if($materialRequest->status === 'pending')
                    <form method="POST" action="{{ route('material-requests.approve', $materialRequest) }}" class="inline-block w-full">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Approved Amount</label>
                            <input type="number" name="approved_amount" value="{{ $materialRequest->total_amount }}" step="0.01" min="0" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Approve Request
                        </button>
                    </form>
                    
                    <form method="POST" action="{{ route('material-requests.reject', $materialRequest) }}" class="inline-block w-full">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
                            <textarea name="rejection_reason" rows="2" required 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500 focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Reject Request
                        </button>
                    </form>
                    @endif
                    
                    @if($materialRequest->status === 'approved')
                    <form method="POST" action="{{ route('material-requests.mark-in-progress', $materialRequest) }}" class="inline-block w-full">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                            <select name="assigned_to" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                <option value="">Select Assignee</option>
                                @foreach(\App\Models\User::where('is_active', true)->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Mark In Progress
                        </button>
                    </form>
                    @endif
                    
                    @if($materialRequest->status === 'in_progress')
                    <form method="POST" action="{{ route('material-requests.mark-completed', $materialRequest) }}" class="inline-block w-full">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                            Mark Completed
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Request Created</p>
                            <p class="text-sm text-gray-500">{{ $materialRequest->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($materialRequest->approved_date)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Request Approved</p>
                            <p class="text-sm text-gray-500">{{ $materialRequest->approved_date->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($materialRequest->completion_date)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-purple-600 rounded-full mt-2"></div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Request Completed</p>
                            <p class="text-sm text-gray-500">{{ $materialRequest->completion_date->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



