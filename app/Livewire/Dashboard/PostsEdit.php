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
use App\Helpers\PostsCacheHelper;

class PostsEdit extends Component
{
    use WithFileUploads;

    public $postId;
    public $title, $slug, $thumbnail, $thumbnail_description, $content;
    public $oldThumbnail;

    public $is_published = false, $is_archived = false,$published_at = null, $archived_at = null;


    // Tambahan untuk TomSelect
    public $selectedCategories = [];
    public $selectedTags = [];
    public $existingCategories = [];
    public $existingTags = [];

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required',
        'thumbnail' => 'nullable|image|max:2048',
        'thumbnail_description' => 'nullable|string|min:10|max:100',
        'selectedCategories' => 'required|array|min:1',
        'selectedTags' => 'nullable|array|max:10',
        'is_published' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function mount($slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['categories:id,name', 'tags:id,name'])
            ->firstOrFail();

        $this->postId = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->content = $post->content;
        $this->oldThumbnail = $post->thumbnail;
        $this->thumbnail_description = $post->thumbnail_description;

        // Selected IDs
        $this->selectedCategories = $post->categories->pluck('id')->toArray();
        $this->selectedTags = $post->tags->pluck('id')->toArray();

        // Full data untuk TomSelect
        $this->existingCategories = $post->categories->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
        ])->toArray();

        $this->existingTags = $post->tags->map(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
        ])->toArray();
    }

    public function updateDraft(): void
    {
        $this->setPublish(false);
    }
     
    public function updateNow(): void
    {
        $this->setPublish(true);
    }

    public function setPublish($value)
    {
        $this->is_published = $value;
        $this->update();
    }
    
    public function update(): void
    {
        $this->validate();

        $post = Post::findOrFail($this->postId);

        // Slug: berubah hanya jika title berubah
        if ($this->title !== $post->title) {
            $this->slug = Str::slug($this->title) . '-' . Str::random(4);
        } else {
            $this->slug = $post->slug;
        }

        // Thumbnail handling
        if ($this->thumbnail) {
            if ($this->oldThumbnail && Storage::disk('public')->exists($this->oldThumbnail)) {
                Storage::disk('public')->delete($this->oldThumbnail);
            }
            $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');
        } else {
            $thumbnailPath = $this->oldThumbnail;
        }

        // Membuat published_at sesuai perubahan status
        $publishedAt = $post->published_at;

        // Draft => Publish (pertama kali)
        if (! $post->is_published && $this->is_published) {
            $publishedAt = now();
        }

        // Publish => Draft (jika kamu izinkan)
        if ($post->is_published && ! $this->is_published) {
            $publishedAt = null;
        }

        $post->update([
            'title'        => $this->title,
            'slug'         => $this->slug,
            'thumbnail'    => $thumbnailPath,
            'thumbnail_description' => $this->thumbnail_description,
            'content'      => $this->content,
            'user_id'      => Auth::id(),
            'is_published' => $this->is_published,
            'published_at' => $publishedAt,
        ]);

        $post->categories()->sync($this->selectedCategories ?? []);
        $post->tags()->sync($this->selectedTags ?? []);

        PostsCacheHelper::refreshAll();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $this->is_published
                ? 'Post updated & published!'
                : 'Draft updated successfully.',
            'redirect' => route('dashboard.posts.index'),
        ]);
    }

    
    public function removeThumbnail()
    {
        $this->thumbnail = null;
        $this->oldThumbnail = null;
        $this->thumbnail_description = null;
    }

    public function render()
    {
        return view('livewire.dashboard.posts-edit')->layout('layouts.dashboard.main');
    }
}
