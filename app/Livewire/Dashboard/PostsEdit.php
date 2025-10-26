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
    public $title, $slug, $thumbnail, $content;
    public $oldThumbnail;

    // Tambahan untuk TomSelect
    public $selectedCategories = [];
    public $selectedTags = [];
    public $existingCategories = [];
    public $existingTags = [];

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required',
        'thumbnail' => 'nullable|image|max:2048',
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

    public function update()
    {
        $this->validate();

        $post = Post::findOrFail($this->postId);

        if ($this->title !== $post->title) {
            $this->slug = Str::slug($this->title) . '-' . Str::random(4);
        }

        if ($this->thumbnail) {
            if ($this->oldThumbnail && Storage::disk('public')->exists($this->oldThumbnail)) {
                Storage::disk('public')->delete($this->oldThumbnail);
            }
            $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');
        } else {
            $thumbnailPath = $this->oldThumbnail;
        }

        $post->update([
            'title'     => $this->title,
            'slug'      => $this->slug,
            'thumbnail' => $thumbnailPath,
            'content'   => $this->content,
            'user_id'   => Auth::id(),
        ]);

        $post->categories()->sync($this->selectedCategories ?? []);
        $post->tags()->sync($this->selectedTags ?? []);

        PostsCacheHelper::refreshAll();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Post updated successfully!',
            'redirect' => route('dashboard.posts.index'),
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.posts-edit')->layout('layouts.dashboard.main');
    }
}
