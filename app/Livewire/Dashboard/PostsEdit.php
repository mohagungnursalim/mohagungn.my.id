<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostsEdit extends Component
{
    use WithFileUploads;

    public $postId;
    public $title, $slug, $thumbnail, $content;
    public $oldThumbnail;
    public $selectedCategories = [], $selectedTags = [];

    public $cacheKey = 'dashboard_posts';
    public $ttl = 60 * 60 * 24 * 7; // 1 minggu

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required',
        'thumbnail' => 'nullable|image|max:2048',
    ];

    /**
     * mount
     *
     * @param  mixed $post
     * @return void
     */
    public function mount($slug)
    {

        $post = Post::where('slug', $slug)->with(['categories', 'tags'])->firstOrFail();

        $this->postId = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->content = $post->content;
        $this->oldThumbnail = $post->thumbnail;

        $this->selectedCategories = $post->categories->pluck('id')->toArray();
        $this->selectedTags = $post->tags->pluck('id')->toArray();
    }

    /**
     * update
     *
     * @return void
     */
    public function update()
    {
        $this->validate();

        $post = Post::findOrFail($this->postId);

        // update slug jika title berubah
        if ($this->title !== $post->title) {
            $this->slug = Str::slug($this->title) . '-' . Str::random(4);
        }

        // handle thumbnail baru
        if ($this->thumbnail) {
            // hapus file lama jika ada
            if ($this->oldThumbnail && Storage::disk('public')->exists($this->oldThumbnail)) {
                Storage::disk('public')->delete($this->oldThumbnail);
            }
            $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');
        } else {
            $thumbnailPath = $this->oldThumbnail;
        }

        // update data
        $post->update([
            'title'     => $this->title,
            'slug'      => $this->slug,
            'thumbnail' => $thumbnailPath,
            'content'   => $this->content,
            'user_id'   => Auth::id(),
        ]);

        // sync kategori dan tag
        $post->categories()->sync($this->selectedCategories);
        $post->tags()->sync($this->selectedTags);

        // refresh cache
        $this->refreshCache();

       // Dispatch event dengan payload lengkap
       $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Post updated successfully!',
            'redirect' => route('dashboard.posts.index'),
        ]);
        
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
        return view('livewire.dashboard.posts-edit', [
            'allCategories' => Category::all(),
            'allTags'       => Tag::all(),
        ])->layout('layouts.dashboard.main');
    }
}
