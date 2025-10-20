@extends('layouts.app')

@section('title', 'Create New Invoice')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Invoice</h1>
        <p class="text-gray-600 mt-2">Fill in the details below to create a new invoice</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Invoice Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Invoice Number
                    </label>
                    <input type="text" value="{{ $invoiceNumber }}" disabled
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>

                <!-- Invoice Date -->
                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Invoice Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="invoice_date" id="invoice_date" required
                        value="{{ old('invoice_date', date('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('invoice_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Client -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Client <span class="text-red-500">*</span>
                    </label>
                    <select name="client_id" id="client_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">-- Select Client --</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} @if ($client->company)
                                    - {{ $client->company }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Due Date
                    </label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Invoice Items</h3>
                    <button type="button" onclick="addItem()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-plus mr-1"></i> Add Item
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 rounded-lg" id="itemsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-24">Quantity
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-32">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-20">Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <tr class="item-row">
                                <td class="px-4 py-3">
                                    <input type="text" name="items[0][description]" required
                                        placeholder="Service description"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="items[0][quantity]" required min="1" value="1"
                                        class="item-quantity w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                        onchange="calculateRow(this)">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="items[0][price]" required min="0" step="0.01"
                                        placeholder="0.00"
                                        class="item-price w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                        onchange="calculateRow(this)">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text"
                                        class="item-total w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                        value="0.00" readonly>
                                </td>
                                <td class="px-4 py-3">
                                    <button type="button" onclick="removeItem(this)"
                                        class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totals -->
            <div class="flex justify-end mb-6">
                <div class="w-full md:w-1/3 space-y-3">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span class="font-semibold">Rs. <span id="subtotalDisplay">0.00</span></span>
                    </div>
                    <div class="flex justify-between items-center text-gray-700">
                        <span>Tax:</span>
                        <div class="flex items-center">
                            <span class="mr-2">Rs.</span>
                            <input type="number" name="tax" id="tax" min="0" step="0.01"
                                value="0"
                                class="w-24 px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                onchange="calculateTotal()">
                        </div>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-900 border-t pt-3">
                        <span>Total:</span>
                        <span>Rs. <span id="totalDisplay">0.00</span></span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes / Terms
                </label>
                <textarea name="notes" id="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="Any additional notes or payment terms...">{{ old('notes') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('invoices.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> Create Invoice
                </button>
            </div>
        </form>
    </div>

    <script>
        let itemIndex = 1;

        // Add new item row
        function addItem() {
            const tbody = document.getElementById('itemsBody');
            const newRow = `
        <tr class="item-row">
            <td class="px-4 py-3">
                <input type="text" name="items[${itemIndex}][description]" required placeholder="Service description"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${itemIndex}][quantity]" required min="1" value="1"
                    class="item-quantity w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="calculateRow(this)">
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${itemIndex}][price]" required min="0" step="0.01" placeholder="0.00"
                    class="item-price w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                    onchange="calculateRow(this)">
            </td>
            <td class="px-4 py-3">
                <input type="text" class="item-total w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" 
                    value="0.00" readonly>
            </td>
            <td class="px-4 py-3">
                <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
            tbody.insertAdjacentHTML('beforeend', newRow);
            itemIndex++;
        }

        // Remove item row
        function removeItem(button) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length > 1) {
                button.closest('tr').remove();
                calculateTotal();
            } else {
                alert('At least one item is required!');
            }
        }

        // Calculate single row total
        function calculateRow(input) {
            const row = input.closest('tr');
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const total = quantity * price;
            row.querySelector('.item-total').value = total.toFixed(2);
            calculateTotal();
        }

        // Calculate invoice total
        function calculateTotal() {
            let subtotal = 0;
            document.querySelectorAll('.item-total').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });

            const tax = parseFloat(document.getElementById('tax').value) || 0;
            const total = subtotal + tax;

            document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2);
            document.getElementById('totalDisplay').textContent = total.toFixed(2);
        }
    </script>
@endsection
