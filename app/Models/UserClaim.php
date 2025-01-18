<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserClaim extends Model
{
    protected $fillable = [
        'voucher_id',
        'user_name',
        'user_whatsapp',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
