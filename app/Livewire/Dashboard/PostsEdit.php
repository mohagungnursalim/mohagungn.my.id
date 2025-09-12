<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Str;

class PostsEdit extends Component
{
    public $post;
    public $title, $content, $categories = [], $tags = [];

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->content = $post->content;
        $this->categories = $post->categories->pluck('id')->toArray();
        $this->tags = $post->tags->pluck('id')->toArray();
    }

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'nullable',
    ];

    public function update()
    {
        $this->validate();

        $this->post->update([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'content' => $this->content,
        ]);

        $this->post->categories()->sync($this->categories);
        $this->post->tags()->sync($this->tags);

        return redirect()->route('dashboard.posts.index')->with('success','Post updated.');
    }

    public function render()
    {
        return view('livewire.dashboard.posts-edit', [
            'allCategories' => Category::all(),
            'allTags' => Tag::all(),
        ]);
    }
}

