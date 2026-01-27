@extends('layouts.app')

@section('title', 'Upload Documents for Lead')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Upload Documents for Lead</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @php
        // Use clientDocs from controller, fallback to []
        $uploadedDocs = isset($clientDocs) ? $clientDocs : collect();
    @endphp



    <form method="POST" action="{{ route('leads.documents.upload', $lead) }}" enctype="multipart/form-data">
        <!-- Consumer No -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Consumer Number</label>
            <input type="text" name="consumer_number" required value="{{ old('consumer_number', $lead->consumer_number ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Enter consumer number">
        </div>
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-medium mb-1">Electricity Bill (Attachment)</label>
                @if($uploadedDocs->has('electricity_bill'))
                    <a href="{{ asset('storage/' . $uploadedDocs['electricity_bill']->path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                @else
                    <input type="file" name="electricity_bill" class="w-full border rounded px-3 py-2">
                @endif
            </div>
            <div>
                <label class="block font-medium mb-1">Cancelled Cheque (Attachment)</label>
                @if($uploadedDocs->has('cancelled_cheque'))
                    <a href="{{ asset('storage/' . $uploadedDocs['cancelled_cheque']->path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                @else
                    <input type="file" name="cancelled_cheque" class="w-full border rounded px-3 py-2">
                @endif
            </div>
            <div>
                <label class="block font-medium mb-1">Aadhar</label>
                @if($uploadedDocs->has('aadhar'))
                    <a href="{{ asset('storage/' . $uploadedDocs['aadhar']->path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                @else
                    <input type="file" name="aadhar" class="w-full border rounded px-3 py-2">
                @endif
            </div>
            <div>
                <label class="block font-medium mb-1">PAN</label>
                @if($uploadedDocs->has('pan'))
                    <a href="{{ asset('storage/' . $uploadedDocs['pan']->path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                @else
                    <input type="file" name="pan" class="w-full border rounded px-3 py-2">
                @endif
            </div>
            <div>
                <label class="block font-medium mb-1">Other Document Name</label>
                <input type="text" name="other_document_name" class="w-full border rounded px-3 py-2" placeholder="e.g. Property Tax Receipt">
            </div>
            <div>
                <label class="block font-medium mb-1">Other Document</label>
                @if($uploadedDocs->has('other_document'))
                    <a href="{{ asset('storage/' . $uploadedDocs['other_document']->path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                @else
                    <input type="file" name="other_document" class="w-full border rounded px-3 py-2">
                @endif
            </div>
            <div>
                <label class="block font-medium mb-1">Passport Photo</label>
                @if($uploadedDocs->has('passport_photo'))
                    <a href="{{ asset('storage/' . $uploadedDocs['passport_photo']->path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                @else
                    <input type="file" name="passport_photo" class="w-full border rounded px-3 py-2">
                @endif
            </div>
        </div>
        <hr class="my-8">
        <h3 class="text-xl font-semibold mb-4">Site Photos</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-medium mb-1">Pre Installation Site Photo</label>
                @if($uploadedDocs->has('pre_installation_photo'))
                    <a href="{{ asset('storage/' . $uploadedDocs['pre_installation_photo']->path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                @else
                    <input type="file" name="pre_installation_photo" class="w-full border rounded px-3 py-2">
                @endif
            </div>
            <div>
                <label class="block font-medium mb-1">Post Installation Site Photo</label>
                @if($uploadedDocs->has('post_installation_photo'))
                    <a href="{{ asset('storage/' . $uploadedDocs['post_installation_photo']->path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                @else
                    <input type="file" name="post_installation_photo" class="w-full border rounded px-3 py-2">
                @endif
            </div>
        </div>
        <div class="mt-8">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload Documents</button>
        </div>
    </form>
</div>
@endsection
