<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'user_id',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'pivot_category_post');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'pivot_tag_post');
    }

}
