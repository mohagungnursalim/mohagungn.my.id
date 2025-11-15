<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\PostsCacheHelper; 

class PostsCreate extends Component
{
    use WithFileUploads;

    public $title, $slug, $thumbnail,$thumbnail_description,$content;

    public $selectedCategories = [], $selectedTags = [];

    /**
     * rules
     */
     protected $rules = [
         'title' => 'required|min:3',
         'content' => 'required',
         'thumbnail' => 'nullable|image|max:2048',
         'thumbnail_description' => 'nullable|string|max:150',
     ];


    /**
     * store
     */
    public function store() :void
    {
        $this->validate();

        // Buat slug unik berdasarkan title
        $this->slug = Str::slug($this->title) . '-' . Str::random(4);

        // Upload thumbnail (opsional)
        $thumbnailPath = $this->thumbnail 
            ? $this->thumbnail->store('thumbnails', 'public') 
            : null;

        // Buat post baru
        $post = Post::create([
            'title'     => $this->title,
            'slug'      => $this->slug,
            'thumbnail' => $thumbnailPath,
            'thumbnail_description' => $this->thumbnail_description,
            'user_id'   => Auth::id(),
            'content'   => $this->content,
        ]);

        // Sinkronisasi kategori dan tag
        $post->categories()->sync($this->selectedCategories);
        $post->tags()->sync($this->selectedTags);

        // Refresh cache global per user
        $this->refreshCache();

        // Notifikasi
        session()->flash('notif', [
            'message' => 'Post has been created successfully!',
            'type' => 'success',
        ]);

        // Redirect ke index
        $this->redirectIntended(default: route('dashboard.posts.index'), navigate: true);
    }
    
    public function removeThumbnail()
    {
        $this->thumbnail = null;
        $this->thumbnail_description = null;
    }

    /**
     * refreshCache
     */
    public function refreshCache()
    {
        PostsCacheHelper::refreshAll(); // ✅ gunakan helper, bukan manual Cache::forget
    }

    public function render()
    {
        return view('livewire.dashboard.posts-create')
        ->layout('layouts.dashboard.main');
    }
}
