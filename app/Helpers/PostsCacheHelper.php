<?php

namespace App\Helpers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostsCacheHelper
{
    protected static $ttl = 60 * 60 * 24 * 7; // 1 minggu
    protected static $baseKey = 'cache_posts';

    protected static function userKey()
    {
        $userId = Auth::id() ?? 'guest';
        return self::$baseKey . "_user_{$userId}";
    }

    public static function getTotalPosts()
    {
        $key = self::userKey() . '_total';
        return Cache::remember($key, self::$ttl, fn() => Post::where('user_id', Auth::id())->count());
    }

    public static function refreshAll()
    {
        $key = self::userKey() . '_total';
        Cache::forget($key);
        Cache::put($key, Post::where('user_id', Auth::id())->count(), self::$ttl);

        $trackerKey = self::userKey() . '_tracked_keys';
        $trackedKeys = Cache::get($trackerKey, []);

        foreach ($trackedKeys as $k) {
            Cache::forget($k);
        }

        Cache::forget($trackerKey);
    }

    public static function getTrackedKeys()
    {
        $trackerKey = self::userKey() . '_tracked_keys';
        return Cache::get($trackerKey, []);
    }

}
