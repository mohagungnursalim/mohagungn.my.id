<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Helpers\CategoriesCacheHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Livewire\Component;

class Categories extends Component
{
    use WithFileUploads;

    public $name, $color, $image;
    public $showModalAdd;

    public $editId, $editName, $editColor = '#000000', $editImage, $editImagePath;
    public $showEditModal;

    public $deleteId, $deleteName, $deleteColor;
    public $showDeleteModal;

    public $categories = [], $totalCategories;
    public $cacheKey = 'cache_categories';

    #[Url()]
    public $search;

    public $limit = 5;
    public $loaded = false;
    public $ttl = 60 * 60 * 24 * 7; // 1 minggu


    public function mount()
    {
        // Ambil total kategori dari helper (cached)
        $this->totalCategories = CategoriesCacheHelper::getTotalCategories();

        // Cek apakah daftar kategori sudah ada di cache
        $key = "{$this->cacheKey}_" . md5($this->search) . "_{$this->limit}";
        if (Cache::has($key)) {
            $this->categories = Cache::get($key);
            $this->loaded = true;
        }
    }


    public function updatingSearch()
    {
        $this->limit = 5;
    }


    public function updatedSearch()
    {
        $this->loadInitialCategories();
    }


    public function loadInitialCategories()
    {
        $key = "{$this->cacheKey}_" . md5($this->search) . "_{$this->limit}";
        CategoriesCacheHelper::trackCacheKey($key);

        $this->categories = Cache::remember($key, $this->ttl, function () {
            return Category::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->take($this->limit)
                ->get();
        });

        $this->loaded = true;
    }


    public function loadMore()
    {
        $this->limit += 5;
        $this->loadInitialCategories();
    }


    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'color' => 'nullable|string|max:7',
            'image' => 'nullable|image|max:2048',
        ]);

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'color' => $this->color,
            'image' => $this->image
                ? Storage::disk('public')->putFile('categories', $this->image)
                : null,
        ]);

        // Refresh cache & reload data
        CategoriesCacheHelper::refreshAll();
        $this->loadInitialCategories();

        $this->reset(['name', 'color', 'image', 'showModalAdd']);
        $this->dispatch('successAdded', [
            'message' => 'Category successfully added!',
        ]);
    }


    public function removeImage()
    {
        $this->image = null;
    }


    public function edit($id)
    {
        $category = Category::findOrFail($id);

        $this->editId = $category->id;
        $this->editName = $category->name;
        $this->editColor = $category->color;
        $this->editImage = null;
        $this->editImagePath = $category->image;

        $this->showEditModal = true;
    }


    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255|unique:categories,name,' . $this->editId,
            'editColor' => 'nullable|string|max:7',
            'editImage' => 'nullable|image|max:2048',
        ]);

        $category = Category::findOrFail($this->editId);
        $imagePath = $category->image;

        if ($this->editImage) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = Storage::disk('public')->putFile('categories', $this->editImage);
        }

        $category->update([
            'name' => $this->editName,
            'slug' => Str::slug($this->editName),
            'color' => $this->editColor,
            'image' => $imagePath,
        ]);

        // Refresh cache & reload data
        CategoriesCacheHelper::refreshAll();
        $this->loadInitialCategories();

        $this->reset(['editId', 'editName', 'editColor', 'editImage', 'showEditModal']);
        $this->dispatch('successUpdated', [
            'message' => 'Category successfully updated!',
        ]);
    }


    public function removeImageEdit()
    {
        $this->editImage = null;
    }


    public function confirmDelete($id)
    {
        $category = Category::findOrFail($id);
        $this->deleteId = $category->id;
        $this->deleteName = $category->name;
        $this->showDeleteModal = true;
    }


    public function delete()
    {
        $category = Category::findOrFail($this->deleteId);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        // Refresh cache & reload data
        CategoriesCacheHelper::refreshAll();
        $this->loadInitialCategories();

        $this->reset(['deleteId', 'deleteName', 'showDeleteModal']);
        $this->dispatch('successDeleted', [
            'message' => 'Category successfully deleted!',
        ]);
    }


    public function render()
    {
        return view('livewire.dashboard.categories')
            ->layout('layouts.dashboard.main');
    }
}
