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

    //Waktu: lama data di cache
    public $ttl = 60 * 60 * 24 * 7; // 1 minggu
    

    /**
       * Ambil data categories dengan pagination + cache sinkron
       */
      public function categories(Request $request)
      {
          $search  = $request->get('search', '');
          $perPage = 5;
          $page    = max(1, (int) $request->get('page', 1));
  
          $key = "cache_categories_" . md5($search) . "_{$page}_{$perPage}";
          CategoriesCacheHelper::trackCacheKey($key);
  
          // Ambil data dari cache atau query
          $data = Cache::remember($key, $this->ttl, function () use ($search, $perPage) {
              $query = Category::query();
  
              if (!empty($search)) {
                  $query->where('name', 'like', "%{$search}%");
              }
  
              return $query->orderBy('name')->paginate($perPage, ['id', 'name']);
          });
  
          return response()->json([
              'data'          => $data->items(),
              'next_page_url' => $data->nextPageUrl(),
          ]);
      }
  
      /**
       * Ambil data tags dengan pagination + cache sinkron
       */
      public function tags(Request $request)
      {
          $search  = $request->get('search', '');
          $perPage = 5;
          $page    = max(1, (int) $request->get('page', 1));
  
          $key = "cache_tags_" . md5($search) . "_{$page}_{$perPage}";
          TagsCacheHelper::trackCacheKey($key);
  
          $data = Cache::remember($key, $this->ttl, function () use ($search, $perPage) {
              $query = Tag::query();
  
              if (!empty($search)) {
                  $query->where('name', 'like', "%{$search}%");
              }
  
              return $query->orderBy('name')->paginate($perPage, ['id', 'name']);
          });
  
          return response()->json([
              'data'          => $data->items(),
              'next_page_url' => $data->nextPageUrl(),
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
