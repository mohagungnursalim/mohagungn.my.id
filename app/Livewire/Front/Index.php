<?php

namespace App\Livewire\Front;

use App\Helpers\PostsCacheHelper;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('front.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public $readyToLoad = false;

        
    /**
     * loadInitialPosts
     * Trigger loading of posts when the component is ready.
     * @return void
     */
    public function loadInitialPosts()
    {
        $this->readyToLoad = true;
    }
    
    /**
     * render
     * Render the component view with posts and introduction post.
     * @return void
     */
    public function render()
    {
        $posts = null;
        $introPost = null;
        $hasMore = false;

        if ($this->readyToLoad) {
            $introPost = Cache::remember('front.intro_post', now()->addMinutes(60), function () {
                return Post::whereHas('categories', function ($query) {
                    $query->where('slug', 'introduction');
                })
                ->where('is_published', true)
                ->first();
            });

            $page = $this->page ?? request()->get('page', 1);
            $cacheKey = PostsCacheHelper::versionedKey("front.posts.page.{$page}");

            $posts = Cache::remember($cacheKey, now()->addSeconds(PostsCacheHelper::ttl()), function () {
                return Post::with(['categories', 'tags'])
                    ->whereDoesntHave('categories', function ($query) {
                        $query->where('slug', 'introduction');
                    })
                    ->where('is_published', true)
                    ->latest('published_at')
                    ->paginate(10);
            });

            $hasMore = $posts ? $posts->hasMorePages() : false;
        }

        return view('livewire.front.index', compact('posts', 'introPost', 'hasMore'));
    }
}
