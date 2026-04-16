<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Protected
    protected $fillable = [
        'category_id',
        'product_name',
        'sku',
        'unit',
        'image_path',
        'low_stock_threshold',
        'color',
        'size',
        'weight',
        'price',
        'status',
        'stock_qty',
    ];

    // Data Type
    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    // Product to Relation Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Invoice Item relation to Category
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Stock Movement relation to Category
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
