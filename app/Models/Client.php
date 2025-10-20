<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'address'
    ];

    // Client ke saare invoices
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}