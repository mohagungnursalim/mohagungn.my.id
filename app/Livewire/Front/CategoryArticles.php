<?php

namespace App\Livewire\Front;

use App\Helpers\CategoriesCacheHelper;
use App\Helpers\PostsCacheHelper;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('front.layouts.app')]
class CategoryArticles extends Component
{
    use WithPagination;

    public Category $category;

    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)->firstOrFail();
    }

    /**
     * render
     * Render the component view with posts filtered by category.
     * @return void
     */
    public function render()
    {
        // Get all categories for sidebar
        $allCategories = CategoriesCacheHelper::getFrontendCategories();

        // Get posts for specific category with pagination
        $page = $this->page ?? request()->get('page', 1);
        $cacheKey = PostsCacheHelper::versionedKey("front.category.{$this->category->slug}.page.{$page}");

        $posts = Cache::remember($cacheKey, now()->addSeconds(PostsCacheHelper::ttl()), function () {
            return Post::with(['categories', 'tags'])
                ->whereHas('categories', function ($query) {
                    $query->where('categories.id', $this->category->id);
                })
                ->where('is_published', true)
                ->latest('published_at')
                ->paginate(5);
        });

        return view('livewire.front.category-articles', compact('posts', 'allCategories'));
    }
}
