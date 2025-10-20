<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    // Saare invoices dikhaye
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    // Naya invoice banane ka form
    public function create()
    {
        $clients = Client::all();
        // Latest invoice number generate kare
        $lastInvoice = Invoice::latest()->first();
        $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        return view('invoices.create', compact('clients', 'invoiceNumber'));
    }

    // Invoice save kare
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:paid,unpaid,pending',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0'
        ]);

        // Invoice number generate kare
        $lastInvoice = Invoice::latest()->first();
        $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 4, '0', STR_PAD_LEFT);

        // Subtotal calculate kare
        $subtotal = 0;
        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        // Tax aur total calculate kare
        $tax = $request->tax ?? 0;
        $total = $subtotal + $tax;

        // Invoice create kare
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'client_id' => $request->client_id,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'notes' => $request->notes
        ]);

        // Invoice items save kare
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

    // Invoice detail dikhaye
    public function show(Invoice $invoice)
    {
        $invoice->load('client', 'items');
        return view('invoices.show', compact('invoice'));
    }

    // Invoice PDF download kare
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load('client', 'items');

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    // Invoice delete kare
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }
}
