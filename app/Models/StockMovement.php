<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    // Protected
    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'note',
        'invoice_id',
    ];

    // Product relation to Stock Movement
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Invoice relation to Stock Movement
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
