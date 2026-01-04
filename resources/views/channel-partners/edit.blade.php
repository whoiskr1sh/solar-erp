@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Channel Partner</h1>
            <p class="text-gray-600">Update channel partner information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('channel-partners.show', $channelPartner) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                View Partner
            </a>
            <a href="{{ route('channel-partners.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                Back to Partners
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('channel-partners.update', $channelPartner) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $channelPartner->company_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('company_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">Contact Person *</label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $channelPartner->contact_person) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('contact_person')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $channelPartner->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $channelPartner->phone) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="alternate_phone" class="block text-sm font-medium text-gray-700 mb-2">Alternate Phone</label>
                    <input type="text" id="alternate_phone" name="alternate_phone" value="{{ old('alternate_phone', $channelPartner->alternate_phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                    <input type="url" id="website" name="website" value="{{ old('website', $channelPartner->website) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea id="address" name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('address', $channelPartner->address) }}</textarea>
                </div>
                
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city', $channelPartner->city) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <input type="text" id="state" name="state" value="{{ old('state', $channelPartner->state) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label for="pincode" class="block text-sm font-medium text-gray-700 mb-2">Pincode</label>
                    <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $channelPartner->pincode) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <input type="text" id="country" name="country" value="{{ old('country', $channelPartner->country) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>
        </div>

        <!-- Business Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="gst_number" class="block text-sm font-medium text-gray-700 mb-2">GST Number</label>
                    <input type="text" id="gst_number" name="gst_number" value="{{ old('gst_number', $channelPartner->gst_number) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label for="pan_number" class="block text-sm font-medium text-gray-700 mb-2">PAN Number</label>
                    <input type="text" id="pan_number" name="pan_number" value="{{ old('pan_number', $channelPartner->pan_number) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label for="partner_type" class="block text-sm font-medium text-gray-700 mb-2">Partner Type *</label>
                    <select id="partner_type" name="partner_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Partner Type</option>
                        <option value="distributor" {{ old('partner_type', $channelPartner->partner_type) == 'distributor' ? 'selected' : '' }}>Distributor</option>
                        <option value="dealer" {{ old('partner_type', $channelPartner->partner_type) == 'dealer' ? 'selected' : '' }}>Dealer</option>
                        <option value="installer" {{ old('partner_type', $channelPartner->partner_type) == 'installer' ? 'selected' : '' }}>Installer</option>
                        <option value="consultant" {{ old('partner_type', $channelPartner->partner_type) == 'consultant' ? 'selected' : '' }}>Consultant</option>
                        <option value="other" {{ old('partner_type', $channelPartner->partner_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('partner_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select Status</option>
                        <option value="active" {{ old('status', $channelPartner->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $channelPartner->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ old('status', $channelPartner->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="suspended" {{ old('status', $channelPartner->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Financial Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%) *</label>
                    <input type="number" id="commission_rate" name="commission_rate" value="{{ old('commission_rate', $channelPartner->commission_rate) }}" step="0.01" min="0" max="100" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('commission_rate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-2">Credit Limit (Rs.) *</label>
                    <input type="number" id="credit_limit" name="credit_limit" value="{{ old('credit_limit', $channelPartner->credit_limit) }}" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    @error('credit_limit')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="agreement_start_date" class="block text-sm font-medium text-gray-700 mb-2">Agreement Start Date</label>
                    <input type="date" id="agreement_start_date" name="agreement_start_date" value="{{ old('agreement_start_date', $channelPartner->agreement_start_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
                
                <div>
                    <label for="agreement_end_date" class="block text-sm font-medium text-gray-700 mb-2">Agreement End Date</label>
                    <input type="date" id="agreement_end_date" name="agreement_end_date" value="{{ old('agreement_end_date', $channelPartner->agreement_end_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                    <select id="assigned_to" name="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('assigned_to', $channelPartner->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="specializations" class="block text-sm font-medium text-gray-700 mb-2">Specializations</label>
                    <input type="text" id="specializations" name="specializations" value="{{ old('specializations', is_array($channelPartner->specializations) ? implode(', ', $channelPartner->specializations) : '') }}" placeholder="Enter specializations separated by commas" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    <p class="text-xs text-gray-500 mt-1">e.g., Residential Solar, Commercial Solar, Maintenance</p>
                </div>
                
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">{{ old('notes', $channelPartner->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('channel-partners.show', $channelPartner) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Cancel
            </a>
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg">
                Update Partner
            </button>
        </div>
    </form>
</div>
@endsection
