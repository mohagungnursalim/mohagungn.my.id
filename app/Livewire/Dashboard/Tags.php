<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Tag;
use App\Helpers\TagsCacheHelper;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class Tags extends Component
{
    public $name, $showModalAdd;
    public $editId, $editName, $showEditModal;
    public $deleteId, $deleteName, $showDeleteModal;
    public $tags = [], $totalTags;
    public $cacheKey = 'cache_tags';
    
    #[Url()]
    public $search;
    
    public $limit = 5;
    public $loaded = false;
    public $ttl = 60 * 60 * 24 * 7; // 1 minggu


    public function mount()
    {
        // Ambil total tags dari helper
        $this->totalTags = TagsCacheHelper::getTotalTags();

        // Gunakan cache jika tersedia
        $key = "{$this->cacheKey}_" . md5($this->search) . "_{$this->limit}";
        if (cache()->has($key)) {
            $this->tags = cache()->get($key);
            $this->loaded = true;
        }
    }


    public function updatingSearch()
    {
        $this->limit = 5;
    }


    public function updatedSearch()
    {
        $this->loadInitialTags();
    }


    public function loadInitialTags()
    {
        $key = "{$this->cacheKey}_" . md5($this->search) . "_{$this->limit}";
        
        TagsCacheHelper::trackCacheKey($key);

        $this->tags = cache()->remember($key, $this->ttl, function () {
            return Tag::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->take($this->limit)
                ->get();
        });

        $this->loaded = true;
    }


    public function loadMore()
    {
        $this->limit += 5;
        $this->loadInitialTags();
    }


    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        Tag::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
        ]);

        TagsCacheHelper::refreshAll();
        $this->loadInitialTags();

        $this->reset(['name', 'showModalAdd']);
        $this->dispatch('addSuccess', [
            'message' => 'Tag successfully created.',
        ]);
    }


    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $this->editId = $tag->id;
        $this->editName = $tag->name;
        $this->showEditModal = true;
    }


    public function update()
    {
        $this->validate([
            'editName' => 'required|string|max:255|unique:tags,name,' . $this->editId,
        ]);

        $tag = Tag::findOrFail($this->editId);
        $tag->update([
            'name' => $this->editName,
            'slug' => Str::slug($this->editName),
        ]);

        TagsCacheHelper::refreshAll();
        $this->loadInitialTags();
        
        $this->reset(['editId', 'editName', 'showEditModal']);
        $this->dispatch('updateSuccess', [
            'message' => 'Tag successfully updated.',
        ]);
    }


    public function confirmDelete($id)
    {
        $tag = Tag::findOrFail($id);
        $this->deleteId = $tag->id;
        $this->deleteName = $tag->name;
        $this->showDeleteModal = true;
    }


    public function delete()
    {
        $tag = Tag::findOrFail($this->deleteId);
        $tag->delete();

        TagsCacheHelper::refreshAll();
        $this->loadInitialTags();

        $this->reset(['deleteId', 'deleteName', 'showDeleteModal']);
        $this->dispatch('deleteSuccess', [
            'message' => 'Tag successfully deleted.',
        ]);
    }


    public function render()
    {
        return view('livewire.dashboard.tags')
            ->layout('layouts.dashboard.main');
    }
}
