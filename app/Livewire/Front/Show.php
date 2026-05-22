<?php

namespace App\Livewire\Front;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

use App\Helpers\PostsCacheHelper;

#[Layout('front.layouts.app')]
class Show extends Component
{
    public $post;

    public function mount($slug)
    {
        $cacheKey = PostsCacheHelper::versionedKey("show_post_{$slug}");
        $ttl = PostsCacheHelper::ttl();
        $this->post = \Illuminate\Support\Facades\Cache::remember($cacheKey, $ttl, function () use ($slug) {
            return Post::with(['categories', 'tags'])
                ->where('slug', $slug)
                ->where('is_published', true)
                ->firstOrFail();
        });
    }

    public function render()
    {
        return view('livewire.front.show');
    }
}
