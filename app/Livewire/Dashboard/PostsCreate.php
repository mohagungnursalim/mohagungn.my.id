<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PostsCreate extends Component
{
    use WithFileUploads;

    public $title, $slug, $thumbnail, $content;
    public $selectedCategories = [], $selectedTags = [];

    public $cacheKey = 'dashboard_posts';
    public $ttl = 60 * 60 * 24 * 7; // 1 minggu

       
    /**
     * rules
     *
     * @var array
     */
    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required',
        'thumbnail' => 'nullable|image|max:2048',
    ];
    
   
    
    /**
     * store
     *
     * @return void
     */
    public function store()
    {
        $this->validate();

        // Buat slug berdasarkan title
        $this->slug = Str::slug($this->title) . '-' . Str::random(4);

        $thumbnailPath = $this->thumbnail ? $this->thumbnail->store('thumbnails', 'public') : null;

        $post = Post::create([
            'title'     => $this->title,
            'slug'      => $this->slug,
            'thumbnail' => $thumbnailPath,
            'user_id'   => Auth::id(),
            'content'   => $this->content,
        ]);

        $post->categories()->sync($this->selectedCategories);
        $post->tags()->sync($this->selectedTags);

        $this->refreshCache();

        session()->flash('success', 'Post created!');
        return redirect()->route('dashboard.posts.index');
    }
    
    /**
     * refreshCache
     *
     * @return void
     */
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

    public function render()
    {
        return view('livewire.dashboard.posts-create', [
            'allCategories' => Category::all(),
            'allTags'       => Tag::all(),
        ])->layout('layouts.dashboard.main');
    }
}
