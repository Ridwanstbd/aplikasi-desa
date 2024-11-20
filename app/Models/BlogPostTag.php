<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostTag extends Model
{
    use HasFactory;

    public $fillable = ['post_id', 'tag_id'];

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }

    public function blogTag()
    {
        return $this->belongsTo(BlogTag::class, 'tag_id');
    }
}
