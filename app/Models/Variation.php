<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'name_variation', 'price', 'sku','image','is_ready'];

    protected $casts =[
        'is_ready' => 'boolean',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
