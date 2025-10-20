<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\PostsCacheHelper; // ✅ Tambahkan helper

class PostsEdit extends Component
{
    use WithFileUploads;

    public $postId;
    public $title, $slug, $thumbnail, $content;
    public $oldThumbnail;
    public $selectedCategories = [], $selectedTags = [];

    /**
     * rules
     */
    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required',
        'thumbnail' => 'nullable|image|max:2048',
    ];

    /**
     * mount
     */
    public function mount($slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['categories', 'tags'])
            ->firstOrFail();

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
     */
    public function update()
    {
        $this->validate();

        $post = Post::findOrFail($this->postId);

        // Update slug jika title berubah
        if ($this->title !== $post->title) {
            $this->slug = Str::slug($this->title) . '-' . Str::random(4);
        }

        // Handle thumbnail baru
        if ($this->thumbnail) {
            if ($this->oldThumbnail && Storage::disk('public')->exists($this->oldThumbnail)) {
                Storage::disk('public')->delete($this->oldThumbnail);
            }

            $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');
        } else {
            $thumbnailPath = $this->oldThumbnail;
        }

        // Update data
        $post->update([
            'title'     => $this->title,
            'slug'      => $this->slug,
            'thumbnail' => $thumbnailPath,
            'content'   => $this->content,
            'user_id'   => Auth::id(),
        ]);

        // Sinkronisasi kategori & tag
        $post->categories()->sync($this->selectedCategories);
        $post->tags()->sync($this->selectedTags);

        // ✅ Refresh cache pakai helper
        $this->refreshCache();

        // Dispatch event sukses
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Post updated successfully!',
            'redirect' => route('dashboard.posts.index'),
        ]);
    }

    /**
     * refreshCache
     */
    public function refreshCache()
    {
        PostsCacheHelper::refreshAll(); // ✅ panggil helper universal
    }

    public function render()
    {
        return view('livewire.dashboard.posts-edit', [
            'allCategories' => Category::all(),
            'allTags'       => Tag::all(),
        ])->layout('layouts.dashboard.main');
    }
}
