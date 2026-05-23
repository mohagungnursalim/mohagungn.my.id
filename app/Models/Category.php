<?php

namespace App\Models;

use App\Helpers\CategoriesCacheHelper;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'color', 'image'];
    protected $table = 'categories';

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'pivot_category_post');
    }

    protected static function booted()
    {
        // Invalidate categories cache when category is modified
        static::saved(function ($category) {
            try {
                CategoriesCacheHelper::refreshAll();
            } catch (\Exception $e) {
                // ignore
            }
        });

        static::deleted(function ($category) {
            try {
                CategoriesCacheHelper::refreshAll();
            } catch (\Exception $e) {
                // ignore
            }
        });
    }
}

