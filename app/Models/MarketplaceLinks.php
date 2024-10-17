<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceLinks extends Model
{
    use HasFactory;

    public $fillable = ['id', 'type', 'name', 'marketplace_url', 'shop_id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
