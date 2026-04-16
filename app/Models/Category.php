<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Protected
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    // Relation Category to Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
