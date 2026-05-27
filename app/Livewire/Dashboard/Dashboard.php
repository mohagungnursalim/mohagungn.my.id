<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Helpers\PostsCacheHelper;

class Dashboard extends Component
{
    public $loaded = false;
    public $metrics = [
        'total_posts' => 0,
        'published_posts' => 0,
        'draft_posts' => 0,
        'total_views' => 0
    ];

    public function mount()
    {
        $userId = Auth::id() ?? 'guest';
        $version = PostsCacheHelper::getVersion();
        $cacheKey = "dashboard_metrics_user_{$userId}_v{$version}";
        
        if (Cache::has($cacheKey)) {
            $this->metrics = Cache::get($cacheKey);
            $this->loaded = true; // Tandai sudah load → skip skeleton
        }
    }

    public function loadMetrics()
    {
        $userId = Auth::id() ?? 'guest';
        
        $version = PostsCacheHelper::getVersion();
        $cacheKey = "dashboard_metrics_user_{$userId}_v{$version}";
        
        $this->metrics = Cache::remember($cacheKey, 600, function() use ($userId) {
            $totalPosts = Post::where('user_id', $userId)->count();
            $publishedPosts = Post::where('user_id', $userId)->where('is_published', true)->count();
            $draftPosts = Post::where('user_id', $userId)->where('is_published', false)->where('is_archived', false)->count();
            
            $totalViews = PostView::whereHas('post', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->count();
            
            return [
                'total_posts' => $totalPosts,
                'published_posts' => $publishedPosts,
                'draft_posts' => $draftPosts,
                'total_views' => $totalViews
            ];
        });

        $this->loaded = true;
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard')->layout('layouts.dashboard.main');
    }
}

