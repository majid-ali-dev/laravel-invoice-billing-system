<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    private $currencies = [
        'USD' => '$',
        'PKR' => 'Rs.',
        'EUR' => '€',
        'GBP' => '£',
        'AED' => 'د.إ',
    ];

    public function index()
    {
        // Sab invoices dikhaye (no user filter)
        $invoices = Invoice::with('client')
            ->latest()
            ->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = Client::all();
        
        // Latest invoice number
        $lastInvoice = Invoice::latest()->first();
        $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 4, '0', STR_PAD_LEFT);
        
        $currencies = array_keys($this->currencies);

        return view('invoices.create', compact('clients', 'invoiceNumber', 'currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:paid,unpaid,pending',
            'currency' => 'required|in:USD,PKR,EUR,GBP,AED',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        // Latest invoice number
        $lastInvoice = Invoice::latest()->first();
        $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $tax = $request->tax ?? 0;
        $total = $subtotal + $tax;

        // Get first user or create one
        $user = \App\Models\User::first();
        if (!$user) {
            $user = \App\Models\User::create([
                'name' => 'Default User',
                'email' => 'default@example.com',
                'password' => bcrypt('password')
            ]);
        }

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'user_id' => $user->id, // Hardcoded user ID
            'client_id' => $request->client_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'currency' => $request->currency,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'notes' => $request->notes
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $invoice->logo_path = $path;
            $invoice->save();
        }

        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price']
            ]);
        }

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        // No authorization check
        $invoice->load('client', 'items', 'user');
        $currencySymbol = $this->currencies[$invoice->currency] ?? '$';
        return view('invoices.show', compact('invoice', 'currencySymbol'));
    }

    public function edit(Invoice $invoice)
    {
        // No authorization check
        $clients = Client::all();
        $currencies = array_keys($this->currencies);
        $invoice->load('items');
        
        return view('invoices.edit', compact('invoice', 'clients', 'currencies'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        // No authorization check
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:paid,unpaid,pending',
            'currency' => 'required|in:USD,PKR,EUR,GBP,AED',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $tax = $request->tax ?? 0;
        $total = $subtotal + $tax;

        $invoice->update([
            'client_id' => $request->client_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'currency' => $request->currency,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'notes' => $request->notes
        ]);

        $invoice->items()->delete();
        
        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price']
            ]);
        }

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        // No authorization check
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }

    public function downloadPdf(Invoice $invoice)
    {
        // No authorization check
        $invoice->load('client', 'items', 'user');
        $currencySymbol = $this->currencies[$invoice->currency] ?? '$';

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'currencySymbol'));
        return $pdf->download($invoice->invoice_number . '.pdf');
    }
}