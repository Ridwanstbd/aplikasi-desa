<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveConsult extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_name',
        'address',
        'user_whatsapp',
        'name_kandang',
        'jenis_hewan',
        'data_pembelian'
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
}
