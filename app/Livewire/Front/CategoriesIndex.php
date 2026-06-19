<?php

namespace App\Livewire\Front;

use App\Helpers\CategoriesCacheHelper;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('front.layouts.app')]
class CategoriesIndex extends Component
{
    public $categories = [];

    public function mount()
    {
        $this->categories = CategoriesCacheHelper::getFrontendCategories();
    }

    public function render()
    {
        return view('livewire.front.categories-index');
    }
}
