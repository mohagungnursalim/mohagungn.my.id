<?php

namespace App\Models;

use App\Helpers\PostsCacheHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'thumbnail_description',
        'user_id',
        'is_published',
        'is_archived',
        'published_at',
        'archived_at',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'pivot_category_post');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'pivot_tag_post');
    }

    protected static function booted()
    {
        // Invalidate posts cache by bumping version via helper (safe for non-taggable stores)
        static::saved(function ($post) {
            try {
                PostsCacheHelper::refreshAll();
            } catch (\Exception $e) {
                // ignore
            }
        });

        static::deleted(function ($post) {
            try {
                PostsCacheHelper::refreshAll();
            } catch (\Exception $e) {
                // ignore
            }
        });
    }

}
