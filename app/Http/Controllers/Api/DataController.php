<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Tag;
use App\Helpers\CategoriesCacheHelper;
use App\Helpers\TagsCacheHelper;
use Illuminate\Support\Str;

class DataController extends Controller
{
    /**
     * Ambil data categories dengan pagination + search
     */
    public function categories(Request $request)
    {
        $search  = $request->get('search', '');
        $page    = (int) $request->get('page', 1);
        $perPage = 5;

        $cacheKey = "categories_{$search}_page_{$page}";
        CategoriesCacheHelper::trackCacheKey($cacheKey);

        $data = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($search, $perPage) {
            $query = Category::query();

            if (!empty($search)) {
                $query->where('name', 'like', "%{$search}%");
            }

            return $query->orderBy('name')
                         ->simplePaginate($perPage, ['id', 'name']);
        });

        return response()->json([
            'data' => $data->items(),
            'next_page_url' => $data->nextPageUrl(),
            'cached_total' => CategoriesCacheHelper::getTotalCategories(),
        ]);
    }

    /**
     * Ambil data tags dengan pagination + search
     */
    public function tags(Request $request)
    {
        $search  = $request->get('search', '');
        $page    = (int) $request->get('page', 1);
        $perPage = 5;

        $cacheKey = "tags_{$search}_page_{$page}";
        TagsCacheHelper::trackCacheKey($cacheKey);

        $data = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($search, $perPage) {
            $query = Tag::query();

            if (!empty($search)) {
                $query->where('name', 'like', "%{$search}%");
            }

            return $query->orderBy('name')
                         ->simplePaginate($perPage, ['id', 'name']);
        });

        return response()->json([
            'data' => $data->items(),
            'next_page_url' => $data->nextPageUrl(),
            'cached_total' => TagsCacheHelper::getTotalTags(),
        ]);
    }

    /**
     * Simpan tag baru via TomSelect create
     */
    public function storeTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100'
        ]);

        // Trim & pastikan unik
        $name = trim($request->input('name'));

        $tag = Tag::firstOrCreate(
        ['name' => $name],
        [
            'slug' => Str::slug($name),
            'created_at' => now(),
            'updated_at' => now(),
        ] 
        );

        // refresh cache
        TagsCacheHelper::refreshAll();

        return response()->json([
            'success' => true,
            'item' => [
                'id' => $tag->id,
                'name' => $tag->name,
            ],
        ]);
    }
}
