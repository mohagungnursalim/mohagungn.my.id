<?php

namespace App\Helpers;

use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class TagsCacheHelper
{
    protected static $ttl = 60 * 60 * 24 * 7; // 1 minggu
    protected static $baseKey = 'cache_tags';

    /**
     * Ambil total semua kategori (cached)
     */
    public static function getTotalTags()
    {
        $key = self::$baseKey . '_total';
        return Cache::remember($key, self::$ttl, fn() => Tag::count());
    }

    /**
     * Refresh semua cache kategori
     */
    public static function refreshAll()
    {
        $key = self::$baseKey . '_total';
        Cache::forget($key);
        Cache::put($key, Tag::count(), self::$ttl);

        $trackerKey = self::$baseKey . '_tracked_keys';
        $trackedKeys = Cache::get($trackerKey, []);

        foreach ($trackedKeys as $k) {
            Cache::forget($k);
        }

        Cache::forget($trackerKey);
    }

    /**
     * Track key cache yang digunakan
     */
    public static function trackCacheKey($key)
    {
        $trackerKey = self::$baseKey . '_tracked_keys';
        $tracked = Cache::get($trackerKey, []);

        if (!in_array($key, $tracked)) {
            $tracked[] = $key;
            Cache::put($trackerKey, $tracked, self::$ttl);
        }
    }

    /**
     * Ambil daftar tracked keys
     */
    public static function getTrackedKeys()
    {
        $trackerKey = self::$baseKey . '_tracked_keys';
        return Cache::get($trackerKey, []);
    }
}
