<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    public $fillable = ['name','logo_url','description','location_url','added_url','meta_pixel_id','tiktok_pixel_id','google_tag_id'];
    public function banners() {
        return $this->hasMany(Banner::class);
    }
    public function testimonials() {
        return $this->hasMany(Testimonial::class);
    }

}
