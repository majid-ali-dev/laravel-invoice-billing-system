<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'client_id',
        'invoice_date',
        'due_date',
        'status',
        'subtotal',
        'tax',
        'total',
        'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    // Invoice ka client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Invoice ki items
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}