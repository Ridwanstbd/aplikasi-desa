<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    public $fillable =['testimoni_url','shop_id'];
    public function shop() {
        return $this->belongsTo(Shop::class);
    }
}
