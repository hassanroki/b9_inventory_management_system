<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Protected
    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_amount',
        'grand_total',
        'status',
    ];

    // Data Types
    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    // Item to relation Invoice
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Stock Movement relation to Invoice
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
