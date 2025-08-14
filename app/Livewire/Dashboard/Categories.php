<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Livewire\Component;

class Categories extends Component
{
    use WithFileUploads;

    /**
     * Kategori yang akan dibuat
     * 
     * @var mixed
     */
    public $name,$color,$image;
    public $showModalAdd;

    
    /**
     * Kategori yang akan diedit
     *
     * @var mixed
     */
    public $editId,$editName,$editColor = '#000000',$editImage,$editImagePath;
    public $showEditModal;


    /**
     * Kategori yang akan dihapus
     *
     * @var mixed
     */
    public $deleteId,$deleteName,$deleteColor;
    public $showDeleteModal;    
    

    /**
     * Koleksi kategori & total koleksi kategori yang akan ditampilkan
     *
     * @var 
     */
    public $categories, $totalCategories;
    
        
    /**
     * Kunci cache untuk menyimpan kategori
     *
     * @var string
     */
    public $cacheKey = 'dashboard_categories';


    /**
     * Pencarian kategori
     *
     * @var mixed
     */
    #[Url()]    
    public $search;

    
    /**
     * Batas jumlah kategori yang akan ditampilkan
     *
     * @var int
     */
    public $limit = 5;
    

    /**
     * Menandakan apakah kategori sudah dimuat
     *
     * @var bool
     */
    public $loaded = false;
    

    /**
     * Waktu cache untuk kategori dalam detik
     *
     * @var int
     */
    public $ttl = 60 * 60 * 24 * 7; // 1 minggu

        
    /**
     * Method mount untuk inisialisasi komponen
     *
     * @return void
     */
    public function mount()
    {
        $this->totalCategories = Cache::remember('totalCategories', $this->ttl, function () {
            return Category::count();
        });
    }
    
        
    /**
     * Method updatingSearch lifecycle hook untuk memperbarui data & batas kategori yang ditampilkan
     *
     * @return void
     */
    public function updatingSearch()
    {
        $this->limit = 5;
    }
        

    /**
     * Method updatedSearch untuk memperbarui kategori berdasarkan pencarian
     *
     * @return void
     */
    public function updatedSearch()
    {
        $this->loadInitialCategories();
    }
    

    /**
     * Method loadInitialCategories untuk memuat kategori
     *
     * @return void
     */
    public function loadInitialCategories()
    {
        $key = "{$this->cacheKey}_{$this->search}_{$this->limit}";
        
        // Simpan key cache yang digunakan
        $this->trackCacheKey($key);
    
        $this->categories = Cache::remember($key, $this->ttl, function () {
            return Category::where('name', 'like', '%' . $this->search . '%')
                ->latest()
                ->take($this->limit)
                ->get();
        });
    
        // Set loaded ke true hanya jika kategori telah dimuat
        $this->loaded = true;
    }

       
    /**
     * Method loadMore untuk memuat lebih banyak kategori ditampilkan
     *
     * @return void
     */
    public function loadMore()
    {
        $this->limit += 5;
        $this->loadInitialCategories();
    }
    

    /**
     * Method store untuk menambahkan kategori baru
     *
     * @return void
     */
    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'color' => 'nullable|string|max:7',
            'image' => 'nullable|image|max:2048', // Maksimal 2MB
        ]);

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'color' => $this->color,
           'image' => $this->image ? Storage::disk('public')->putFile('categories', $this->image) : null,

        ]);

        $this->refreshCache();
        $this->loadInitialCategories();

        $this->reset(['name', 'color', 'image', 'showModalAdd']);
        $this->dispatch('successAdded', [
            'message' => 'Category successfully added!'
        ]);
        
    }
    

    /**
     * Method removeImage untuk menghapus gambar kategori terpilih
     *
     * @return void
     */
    public function removeImage()
    {
        $this->image = null;
    }


    /**
     * Method edit untuk menampilkan modal edit kategori
     *
     * @param  mixed $id
     * @return void
     */
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
    

    /**
     * Method update untuk memperbarui kategori 
     *
     * @return void
     */
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
            // Hapus file lama kalau ada
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

        // Refresh cache & data
        $this->refreshCache();
        $this->loadInitialCategories();

        $this->reset(['editId', 'editName', 'editColor', 'editImage', 'showEditModal']);
        $this->dispatch('successUpdated', [
            'message' => 'Category successfully updated!'
        ]);
    }

    
    /**
     * Method removeImageEdit untuk menghapus gambar yang terselect di modal edit
     *
     * @return void
     */
    public function removeImageEdit()
    {
        $this->editImage = null;
    }

        
    /**
     * Method confirmDelete untuk menampilkan modal konfirmasi penghapusan kategori
     *
     * @param  mixed $id
     * @return void
     */
    public function confirmDelete($id)
    {
        $category = Category::findOrFail($id);
        $this->deleteId = $category->id;
        $this->deleteName = $category->name;
        $this->showDeleteModal = true;
    }

      
    /**
     * Method delete untuk menghapus kategori
     *
     * @return void
     */
    public function delete()
    {
        $category = Category::findOrFail($this->deleteId);

        // Hapus gambar terkait jika ada
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        // Hapus kategori dari database
        $category->delete();

        // Refresh cache & data
        $this->refreshCache();
        $this->loadInitialCategories();

        $this->reset(['deleteId', 'deleteName', 'showDeleteModal']);


        $this->dispatch('successDeleted', [
            'message' => 'Category successfully deleted!'
        ]);
    }


    /**
     * Method refreshCache untuk memperbarui cache kategori
     *
     * @return void
     */
    public function refreshCache()
    {
        // Hapus dan perbarui total kategori
        Cache::forget('totalCategories');
        Cache::put('totalCategories', Category::count(), $this->ttl);

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
        

    /**
     * Method trackCacheKey untuk menyimpan key cache yang digunakan
     *
     * @param  mixed $key
     * @return void
     */
    protected function trackCacheKey($key)
    {
        $trackerKey = "{$this->cacheKey}_tracked_keys";

        $tracked = Cache::get($trackerKey, []);

        if (!in_array($key, $tracked)) {
            $tracked[] = $key;
            Cache::put($trackerKey, $tracked, $this->ttl);
        }
    }


    /**
     * Method render untuk menampilkan halaman
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.dashboard.categories')
            ->layout('layouts.dashboard.main');
    }
}
