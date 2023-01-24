<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory, Softdeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'quantity_stock',
        'EAN_number'
    ];

    public function categories() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
