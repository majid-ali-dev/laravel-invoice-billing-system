@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Invoice Details</h1>
            <p class="text-gray-600 mt-2">Invoice #{{ $invoice->invoice_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('invoices.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <a href="{{ route('invoices.pdf', $invoice) }}"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-file-pdf mr-2"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8 max-w-4xl mx-auto">

        <!-- Header -->
        <div class="border-b pb-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-blue-600 mb-2">iCreativez Technologies</h2>
                    <p class="text-gray-600 text-sm">Karachi, Pakistan</p>
                    <p class="text-gray-600 text-sm">+92 300 5000248</p>
                    <p class="text-gray-600 text-sm">hello@icreativez.info</p>
                </div>
                <div class="text-right">
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">INVOICE</h3>
                    <p class="text-gray-700 font-semibold">{{ $invoice->invoice_number }}</p>
                </div>
            </div>
        </div>

        <!-- Invoice Info & Client Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            <!-- Invoice Information -->
            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Invoice Information</h4>
                <div class="space-y-2">
                    <div class="flex">
                        <span class="text-gray-600 w-32">Invoice Date:</span>
                        <span class="font-semibold text-gray-900">{{ $invoice->invoice_date->format('d M, Y') }}</span>
                    </div>
                    @if ($invoice->due_date)
                        <div class="flex">
                            <span class="text-gray-600 w-32">Due Date:</span>
                            <span class="font-semibold text-gray-900">{{ $invoice->due_date->format('d M, Y') }}</span>
                        </div>
                    @endif
                    <div class="flex">
                        <span class="text-gray-600 w-32">Status:</span>
                        @if ($invoice->status == 'paid')
                            <span
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Paid
                            </span>
                        @elseif($invoice->status == 'unpaid')
                            <span
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Unpaid
                            </span>
                        @else
                            <span
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Client Details -->
            <div>
                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Bill To</h4>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="font-bold text-gray-900 text-lg mb-1">{{ $invoice->client->name }}</p>
                    @if ($invoice->client->company)
                        <p class="text-gray-700 font-semibold">{{ $invoice->client->company }}</p>
                    @endif
                    @if ($invoice->client->email)
                        <p class="text-gray-600 text-sm mt-2">
                            <i class="fas fa-envelope mr-1"></i> {{ $invoice->client->email }}
                        </p>
                    @endif
                    @if ($invoice->client->phone)
                        <p class="text-gray-600 text-sm">
                            <i class="fas fa-phone mr-1"></i> {{ $invoice->client->phone }}
                        </p>
                    @endif
                    @if ($invoice->client->address)
                        <p class="text-gray-600 text-sm mt-2">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $invoice->client->address }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Invoice Items Table -->
        <div class="mb-8">
            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Invoice Items</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($invoice->items as $index => $item)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->description }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-right">Rs.
                                    {{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 text-right">Rs.
                                    {{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-full md:w-1/3">
                <div class="bg-gray-50 p-6 rounded-lg space-y-3">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span class="font-semibold">Rs. {{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Tax:</span>
                        <span class="font-semibold">Rs. {{ number_format($invoice->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-gray-900 border-t pt-3">
                        <span>Total:</span>
                        <span>Rs. {{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if ($invoice->notes)
            <div class="border-t pt-6">
                <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Notes / Terms</h4>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $invoice->notes }}</p>
            </div>
        @endif

        <!-- Footer -->
        <div class="border-t mt-8 pt-6 text-center">
            <p class="text-gray-600 text-sm">Thank you for your business!</p>
            <p class="text-gray-500 text-xs mt-2">This is a computer-generated invoice.</p>
        </div>

    </div>
@endsection
