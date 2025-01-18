<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','main_image','slug','category_id','url_form'];
    public function getRouteKeyName(){
        return 'slug';
    }
    public function variations(){
        return $this->hasMany(Variation::class);
    }
    public function leads(){
        return $this->hasMany(Leads::class);
    }
    public function images(){
        return $this->hasMany(Images::class);
    }
    public function category() {
        return $this->belongsTo(Categories::class);
    }
    public function getHargaTermurahAttribute()
    {
        return $this->variations->min('price');
    }
}
