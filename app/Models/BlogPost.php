<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    public $fillable = ['title', 'slug', 'content', 'excerpt', 'author_id', 'category_id', 'published_at', 'status'];
}
