<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;
    public $fillable = ['name','phone','address','product_id','detail_order','time_order','province','regency', 'district', 'village'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
