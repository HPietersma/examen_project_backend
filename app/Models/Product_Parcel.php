<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Parcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'parcel_id',
        'product_id',
    ];
}

