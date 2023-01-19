<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier',
        'last_delivery',
        'next_delivery'
    ];

    public function Supplier_Product() {
        return $this->belongsToMany(Product::class, 'supplier_product');
    }
}
