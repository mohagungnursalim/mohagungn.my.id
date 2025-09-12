<?php

namespace App\Livewire\Dashboard;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;

class Posts extends Component
{
    /**
     * Koleksi posts & total posts
     */
    public $posts = [], $totalPosts;

    /**
     * Post yang akan dihapus
     */
    public $deleteId, $deleteName;
    public $showDeleteModal;

    /**
     * Pencarian post
     */
    #[Url()]
    public $search = '';

    /**
     * Batas jumlah data ditampilkan
     */
    public $limit = 5;

    /**
     * Menandakan apakah data sudah dimuat
     */
    public $loaded = false;

    /**
     * Cache
     */
    public $cacheKey = 'dashboard_posts';
    public $ttl = 60 * 60 * 24 * 7; // 1 minggu


    public function mount()
    {
        $this->totalPosts = Cache::remember('totalPosts', $this->ttl, function () {
            return Post::count();
        });
    }

    public function updatingSearch()
    {
        $this->limit = 5;
    }

    public function updatedSearch()
    {
        $this->loadInitialPosts();
    }

    public function loadInitialPosts()
    {
        $key = "{$this->cacheKey}_" . md5($this->search) . "_{$this->limit}";
        $this->trackCacheKey($key);

        $this->posts = Cache::remember($key, $this->ttl, function () {
            return Post::with(['categories', 'tags'])
                ->where('title', 'like', '%' . $this->search . '%')
                ->latest()
                ->take($this->limit)
                ->get();
        });

        $this->loaded = true;
    }

    public function loadMore()
    {
        $this->limit += 5;
        $this->loadInitialPosts();
    }

    public function confirmDelete($id)
    {
        $post = Post::findOrFail($id);
        $this->deleteId = $post->id;
        $this->deleteName = $post->title;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $post = Post::findOrFail($this->deleteId);

        if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();

        $this->refreshCache();
        $this->loadInitialPosts();

        $this->reset(['deleteId', 'deleteName', 'showDeleteModal']);

        $this->dispatch('successDeleted', [
            'message' => 'Post successfully deleted!'
        ]);
    }

    public function refreshCache()
    {
        Cache::forget('totalPosts');
        Cache::put('totalPosts', Post::count(), $this->ttl);

        $trackerKey = "{$this->cacheKey}_tracked_keys";
        $trackedKeys = Cache::get($trackerKey, []);

        foreach ($trackedKeys as $key) {
            Cache::forget($key);
        }

        Cache::forget($trackerKey);
    }

    protected function trackCacheKey($key)
    {
        $trackerKey = "{$this->cacheKey}_tracked_keys";
        $tracked = Cache::get($trackerKey, []);

        if (!in_array($key, $tracked)) {
            $tracked[] = $key;
            Cache::put($trackerKey, $tracked, $this->ttl);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.posts')
            ->layout('layouts.dashboard.main');
    }
}
