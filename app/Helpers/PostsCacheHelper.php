<?php

namespace App\Helpers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostsCacheHelper
{
    protected static $ttl = 60 * 60 * 24 * 7; // 1 minggu
    protected static $baseKey = 'cache_posts';
    protected static $versionKey = 'cache_posts_version';

    protected static function userKey()
    {
        $userId = Auth::id() ?? 'guest';
        return self::$baseKey . "_user_{$userId}";
    }

    public static function getTotalPosts()
    {
        $key = self::versionedKey(self::userKey() . '_total');
        return Cache::remember($key, self::$ttl, fn() => Post::where('user_id', Auth::id())->count());
    }

    public static function refreshAll()
    {
        // Bump global version so all previous versioned keys become stale immediately
        try {
            $current = Cache::get(self::$versionKey, 0);
            $new = $current + 1;
            Cache::put(self::$versionKey, $new, self::$ttl);
        } catch (\Exception $e) {
            // ignore if store has issues
        }

        // Optionally keep legacy behavior: remove explicitly tracked keys if any
        $trackerKey = self::userKey() . '_tracked_keys';
        $trackedKeys = Cache::get($trackerKey, []);

        foreach ($trackedKeys as $k) {
            try {
                Cache::forget($k);
            } catch (\Exception $e) {
                // ignore
            }
        }

        Cache::forget($trackerKey);
    }

    public static function getTrackedKeys()
    {
        $trackerKey = self::userKey() . '_tracked_keys';
        return Cache::get($trackerKey, []);
    }

    public static function getVersion()
    {
        return Cache::get(self::$versionKey, 0);
    }

    public static function versionedKey(string $key)
    {
        $version = self::getVersion();
        return self::$baseKey . "_v{$version}_" . $key;
    }

    public static function ttl()
    {
        return self::$ttl;
    }

}
