<?php

namespace App\Livewire\Front;

use App\Helpers\CategoriesCacheHelper;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('front.layouts.app')]
class CategoriesIndex extends Component
{
    public $categories = [];
    public $readyToLoad = false;

    public function mount()
    {
        $this->categories = CategoriesCacheHelper::getFrontendCategories();
    }

    /**
     * loadInitialContent
     * Trigger loading of content when the component is ready.
     * @return void
     */
    public function loadInitialContent()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.front.categories-index');
    }
}
