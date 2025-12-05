<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;

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

// User Profile Routes (optional)
Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
Route::post('/profile', [UserController::class, 'update'])->name('profile.update');
Route::delete('/profile/logo', [UserController::class, 'deleteLogo'])->name('profile.deleteLogo');