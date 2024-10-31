<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'discount_amount'
    ];

    public function userClaims()
    {
        return $this->hasMany(UserClaim::class);
    }
}
