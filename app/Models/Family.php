<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = [
        'familyname',
        'address',
        'homenr',
        'zipcode',
        'city',
        'phone',
        'email',
        'amountAdults',
        'amountChildren',
        'amountBabies',
        'deleted_at',
    ];
}
