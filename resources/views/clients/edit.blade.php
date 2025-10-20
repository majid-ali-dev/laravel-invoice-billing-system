@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Client</h1>
        <p class="text-gray-600 mt-2">Update client information</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl">
        <form action="{{ route('clients.update', $client) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Client Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter client name" value="{{ old('name', $client->name) }}">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address
                </label>
                <input type="email" name="email" id="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="client@example.com" value="{{ old('email', $client->email) }}">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Field -->
            <div class="mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number
                </label>
                <input type="text" name="phone" id="phone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0300-1234567" value="{{ old('phone', $client->phone) }}">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Company Field -->
            <div class="mb-6">
                <label for="company" class="block text-sm font-medium text-gray-700 mb-2">
                    Company Name
                </label>
                <input type="text" name="company" id="company"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Company name" value="{{ old('company', $client->company) }}">
                @error('company')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address Field -->
            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Address
                </label>
                <textarea name="address" id="address" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter full address">{{ old('address', $client->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('clients.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> Update Client
                </button>
            </div>
        </form>
    </div>
@endsection
