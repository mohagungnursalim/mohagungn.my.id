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

    public function render()
    {
        // Determine current page (Livewire provides $this->page when paginating)
        $page = $this->page ?? request()->get('page', 1);
        $cacheKey = PostsCacheHelper::versionedKey("front.posts.page.{$page}");

        // Cache posts per page using helper TTL (no tags - versioning used)
        $posts = Cache::remember($cacheKey, now()->addSeconds(PostsCacheHelper::ttl()), function () {
            return Post::with(['categories', 'tags'])
                ->where('is_published', true)
                ->latest('published_at')
                ->paginate(10);
        });

        return view('livewire.front.index', compact('posts'));
    }
}
