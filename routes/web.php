<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;

// Home page - Dashboard
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Client Routes
Route::resource('clients', ClientController::class);

// Invoice Routes
Route::resource('invoices', InvoiceController::class);
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])
    ->name('invoices.pdf');
