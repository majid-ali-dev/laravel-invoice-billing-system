@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome to Invoice & Billing System</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Total Clients Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Clients</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Client::count() }}</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('clients.index') }}" class="text-blue-600 text-sm mt-4 inline-block hover:underline">
                View all clients →
            </a>
        </div>

        <!-- Total Invoices Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Invoices</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Invoice::count() }}</h3>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-file-invoice text-green-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('invoices.index') }}" class="text-green-600 text-sm mt-4 inline-block hover:underline">
                View all invoices →
            </a>
        </div>

        <!-- Total Revenue Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">Rs.
                        {{ number_format(\App\Models\Invoice::sum('total'), 2) }}</h3>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-dollar-sign text-purple-600 text-2xl"></i>
                </div>
            </div>
            <p class="text-purple-600 text-sm mt-4">From all invoices</p>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <a href="{{ route('clients.create') }}"
                class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-2 border-transparent hover:border-blue-500">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-4 mr-4">
                        <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Add New Client</h3>
                        <p class="text-gray-600 text-sm">Create a new client profile</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('invoices.create') }}"
                class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-2 border-transparent hover:border-green-500">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-4 mr-4">
                        <i class="fas fa-plus-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Create New Invoice</h3>
                        <p class="text-gray-600 text-sm">Generate a new invoice</p>
                    </div>
                </div>
            </a>

        </div>
    </div>
@endsection
