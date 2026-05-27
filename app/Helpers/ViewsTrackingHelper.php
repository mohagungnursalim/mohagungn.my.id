<?php

namespace App\Helpers;

use App\Models\Post;
use App\Models\PostView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ViewsTrackingHelper
{
    const CACHE_TTL_HOURS = 1;
    const CACHE_KEY_PREFIX = 'post_views_count_';
    const IP_CACHE_KEY_PREFIX = 'post_view_ip_';

    /**
     * Track view untuk post
     * Mencegah multiple views dari IP yang sama dalam periode tertentu
     */
    public static function trackView(Post $post)
    {
        $ipAddress = self::getClientIp();
        $userId = Auth::id();

        try {
            // Cache key for per-post+ip existence check
            $ipCacheKey = self::getIpCacheKey($post->id, $ipAddress);

            // If cache says this IP already viewed, skip DB hit
            if (Cache::get($ipCacheKey, false)) {
                return;
            }

            // Otherwise, check DB but cache the existence result to avoid repeated queries
            $existing = Cache::remember($ipCacheKey, now()->addHours(self::CACHE_TTL_HOURS), function () use ($post, $ipAddress) {
                return PostView::where('post_id', $post->id)
                    ->where('ip_address', $ipAddress)
                    ->exists();
            });

            if ($existing) {
                return;
            }

            // Create view record and mark cache so future hits won't query DB
            PostView::create([
                'post_id' => $post->id,
                'ip_address' => $ipAddress,
                'user_id' => $userId,
            ]);

            // Ensure the ip-existence cache is set (true) and invalidate overall views count
            Cache::put($ipCacheKey, true, now()->addHours(self::CACHE_TTL_HOURS));
            self::invalidateCache($post->id);
        } catch (\Exception $e) {
            // Silently fail, jangan interrupt user experience
            \Log::warning('Failed to track post view', [
                'post_id' => $post->id,
                'ip_address' => $ipAddress,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get cache key for per-post+ip existence
     */
    private static function getIpCacheKey($postId, $ip)
    {
        return self::IP_CACHE_KEY_PREFIX . $postId . '_' . $ip;
    }

    /**
     * Get client IP address
     */
    public static function getClientIp()
    {
        $ipKeys = [
            'HTTP_CF_CONNECTING_IP', // CloudFlare
            'HTTP_X_FORWARDED_FOR',  // Proxy
            'HTTP_CLIENT_IP',        // Client IP
            'REMOTE_ADDR',           // Direct connection
        ];

        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                return trim($ips[0]);
            }
        }

        return '127.0.0.1';
    }

    /**
     * Get total views untuk sebuah post dengan caching 1 jam
     */
    public static function getPostViewCount(Post $post)
    {
        $cacheKey = self::getCacheKey($post->id);
        
        return Cache::remember($cacheKey, now()->addHours(self::CACHE_TTL_HOURS), function () use ($post) {
            return PostView::where('post_id', $post->id)->count();
        });
    }

    /**
     * Get bulk views count untuk mencegah N+1 (digunakan di dashboard array of Posts)
     */
    public static function getPostViewCountsForCollection($posts)
    {
        $missingIds = [];
        $counts = [];

        foreach ($posts as $post) {
            $postId = is_array($post) ? $post['id'] : $post->id;
            $cacheKey = self::getCacheKey($postId);

            if (Cache::has($cacheKey)) {
                $counts[$postId] = Cache::get($cacheKey);
            } else {
                $missingIds[] = $postId;
            }
        }

        if (!empty($missingIds)) {
            $dbCounts = PostView::whereIn('post_id', $missingIds)
                ->selectRaw('post_id, count(*) as count')
                ->groupBy('post_id')
                ->pluck('count', 'post_id');

            foreach ($missingIds as $id) {
                // Default ke 0 jika tidak ada views di db
                $count = $dbCounts->get($id, 0); 
                Cache::put(self::getCacheKey($id), $count, now()->addHours(self::CACHE_TTL_HOURS));
                $counts[$id] = $count;
            }
        }

        return $counts;
    }

    /**
     * Get cache key untuk views count post
     */
    private static function getCacheKey($postId)
    {
        return self::CACHE_KEY_PREFIX . $postId;
    }

    /**
     * Invalidate cache untuk post views count
     */
    public static function invalidateCache($postId)
    {
        Cache::forget(self::getCacheKey($postId));
    }

    /**
     * Invalidate all views cache
     */
    public static function invalidateAllCache()
    {
        // Opsi: jika ingin invalidate semua cache views
        // Bisa digunakan di seeder atau admin command
        Cache::flush();
    }

    /**
     * Get views untuk post dengan grouping by date
     */
    public static function getPostViewsByDate(Post $post)
    {
        return PostView::where('post_id', $post->id)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }
}
