<?php

namespace App\Models;

use App\Observers\LeadsObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    public $fillable = ['name', 'phone', 'address', 'product_id', 'detail_order', 'time_order', 'province', 'regency', 'district', 'village'];

    protected static function booted()
    {
        parent::booted();
        static::observe(LeadsObserver::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
