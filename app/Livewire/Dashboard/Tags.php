<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class Tags extends Component
{
    /**
     * Nama tag yang akan dibuat
     * @var string|null
     */
    public $name;
    public $showModalAdd;
    
    /**
     * ID dan nama tag yang akan diedit
     * @var int|null
     */
    public $editId;
    public $editName;
    public $showEditModal = false;


    /**
     * ID dan nama tag yang akan dihapus
     * @var int|null
     */
    public $deleteId;
    public $deleteName;
    public $showDeleteModal = false;

    /**
     * Koleksi tag & total koleksi tag yang akan ditampilkan
     * @var \Illuminate\Support\Collection
     */
    public $tags, $totalTags;

    /**
     * Kunci cache untuk menyimpan tag
     * @var string
     */
    public $cacheKey = 'dashboard_tags';

    /**
     * Pencarian tag
     * @var string|null
     */
    #[Url()]
    public $search;

    /**
     * Batas jumlah tag yang akan ditampilkan
     * @var int
     */
    public $limit = 5;

    /**
     * Menandakan apakah tag sudah dimuat
     * @var bool
     */
    public $loaded = false;

    /**
     * Waktu cache untuk tag dalam detik
     * @var int
     */

    public $ttl = 300; // 5 menit


    
    public function mount()
    {
        // Ambil jumlah total tag dari cache
        $this->totalTags = Cache::remember('totalTags', $this->ttl, function () {
            return Tag::count();
        });

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
        $this->loaded = true;

        $key = "{$this->cacheKey}_{$this->search}_{$this->limit}";

        // Simpan key-nya ke tracker manual
        $this->trackCacheKey($key);

        $this->tags = Cache::remember($key, $this->ttl, function () {
            return Tag::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->take($this->limit)
                ->get();
        });
    }

    public function loadMore()
    {
        $this->limit += 5;
        $this->loadInitialTags();
    }

    public function store()
    {
        DB::listen(function ($query) {
            logger($query->sql, $query->bindings);
        });

        $this->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        Tag::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
        ]);

        $this->refreshCache();
        $this->loadInitialTags();

        $this->reset(['name', 'showModalAdd']);
       

        $this->dispatch('addSuccess',
        [
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

        $this->refreshCache();
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

        $this->refreshCache();
        $this->loadInitialTags();

        $this->reset(['deleteId', 'deleteName', 'showDeleteModal']);

        $this->dispatch('deleteSuccess', [
            'message' => 'Tag successfully deleted.',
        ]);
    }
    
    public function refreshCache()
    {
        // Hapus dan segarkan total tag
        Cache::forget('totalTags');
        Cache::put('totalTags', Tag::count(), $this->ttl);

        // Ambil semua key yang disimpan
        $trackerKey = "{$this->cacheKey}_tracked_keys";

        $trackedKeys = Cache::get($trackerKey, []);

        // Hapus semua cache yang terkait
        foreach ($trackedKeys as $key) {
            Cache::forget($key);
        }

        // Hapus tracker-nya sendiri
        Cache::forget($trackerKey);
    }

    protected function trackCacheKey($key)
    {
        $trackerKey = "{$this->cacheKey}_tracked_keys";

        $tracked = Cache::get($trackerKey, []);

        if (!in_array($key, $tracked)) {
            $tracked[] = $key;
            Cache::put($trackerKey, $tracked, $this->ttl);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.tags')
            ->layout('layouts.dashboard.main');
    }
}
