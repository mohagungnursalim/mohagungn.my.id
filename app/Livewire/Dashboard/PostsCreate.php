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
    public $is_published = false, $is_archived = false,$published_at = null, $archived_at = null;


    public $selectedCategories = [], $selectedTags = [];

    /**
     * rules
     */
     protected $rules = [
         'title' => 'required|min:3',
         'content' => 'required',
         'thumbnail' => 'required|image|max:2048',
         'thumbnail_description' => 'nullable|string|min:10|max:100',
         'selectedCategories' => 'required|array|min:1',
         'selectedTags' => 'nullable|array|max:10',
         'is_published' => 'boolean',
         'is_archived' => 'boolean',
     ];

     public function saveDraft(): void
     {
         $this->setPublish(false);
     }
     
     public function publishNow(): void
     {
         $this->setPublish(true);
     }

     public function setPublish($value)
     {
         $this->is_published = $value;
         $this->store();
     }

    /**
     * store
     */
     public function store() : void
     {
         $this->validate();
     
         // Slug unik
         $this->slug = Str::slug($this->title) . '-' . Str::random(4);
     
         // Upload thumbnail
         $thumbnailPath = $this->thumbnail
             ? $this->thumbnail->store('thumbnails', 'public')
             : null;
     
         // Set waktu publish jika is_published = true
         $publishedAt = $this->is_published ? now() : null;
     
         $post = Post::create([
             'title'       => $this->title,
             'slug'        => $this->slug,
             'thumbnail'   => $thumbnailPath,
             'thumbnail_description' => $this->thumbnail_description,
             'user_id'     => Auth::id(),
             'content'     => $this->content,
             'is_published'=> $this->is_published,
             'published_at'=> $publishedAt,
             'is_archived' => false,
             'archived_at' => null,
         ]);
     
         $post->categories()->sync($this->selectedCategories);
         $post->tags()->sync($this->selectedTags);
     
         $this->refreshCache();
     
         session()->flash('notif', [
             'message' => $this->is_published 
                 ? 'Post published successfully!'
                 : 'Post saved as draft.',
             'type' => 'success'
         ]);
     
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
