<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    // Protected
    protected $fillable = [
        'invoice_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount_type',
        'discount_value',
        'discount_amount',
        'line_total',
    ];

    // Data Type
    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    // Product relation to Invoice Item
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
