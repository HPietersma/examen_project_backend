<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier_Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'product_id'
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function suppliers() {
        return $this->belongsTo(Supplier::class);
    }


}


