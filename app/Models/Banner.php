<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    public $fillable =['shop_id','banner_url'];
    public function shop() {
        return $this->belongsTo(Shop::class);
    }
}
