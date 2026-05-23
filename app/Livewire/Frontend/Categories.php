<?php

namespace App\Livewire\Frontend;

use App\Helpers\CategoriesCacheHelper;
use Livewire\Component;

class Categories extends Component
{
    public $categories = [];

    public function mount()
    {
        $this->categories = CategoriesCacheHelper::getFrontendCategories();
    }

    public function render()
    {
        return view('livewire.frontend.categories');
    }
}
