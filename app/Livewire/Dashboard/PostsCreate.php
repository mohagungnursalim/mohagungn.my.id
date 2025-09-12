<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;

class PostsCreate extends Component
{
    public $title, $content, $categories = [], $tags = [];

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'nullable',
    ];

    public function store()
    {
        $this->validate();

        $post = Post::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'content' => $this->content,
            'user_id' => auth()->id(),
        ]);

        $post->categories()->sync($this->categories);
        $post->tags()->sync($this->tags);

        return redirect()->route('dashboard.posts.index')->with('success','Post created.');
    }

    public function render()
    {
        return view('livewire.dashboard.posts-create', [
            'allCategories' => Category::all(),
            'allTags' => Tag::all(),
        ]);
    }
}

