<?php

namespace App\Livewire\Front;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('front.layouts.app')]
class Show extends Component
{
    public $post;

    public function mount($slug)
    {
        $this->post = Post::with(['categories', 'tags'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.front.show');
    }
}
