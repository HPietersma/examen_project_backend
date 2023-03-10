<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasFactory, Softdeletes;

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

    public function parcels() {
        return $this->hasMany(Parcel::class, 'family_id', 'id');
    }

}
