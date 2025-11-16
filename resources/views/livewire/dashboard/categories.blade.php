<div class="dark:bg-gray-800">
    <div class="py-12 bg-white dark:bg-gray-800 sm:rounded-lg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div x-data="{ showModalAdd: @entangle('showModalAdd') }">

                {{-- Add Button --}}
                <button @click="showModalAdd = true"
                    class="mb-4 px-5 py-2.5 text-sm font-medium border border-blue-700 text-blue-700 hover:text-white hover:bg-blue-800 focus:outline-none rounded-lg">
                    Add Category
                </button>

                {{-- Search --}}
                <div class="w-full mb-3">
                    <div class="relative">
                        {{-- Icon Search --}}
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-300 dark:text-white text-lg"></i>
                        </div>
                
                        {{-- Input Search --}}
                        <input
                            type="text"
                            wire:model.live.debounce.500ms="search"
                            placeholder="Search..."
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                   focus:ring-blue-500 focus:border-blue-500
                                   block w-full pl-10 pr-10 p-2.5
                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white
                                   dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        />
                    </div>
                
                    {{-- Spinner Loading --}}
                    <div wire:loading wire:target="search" class="flex items-center gap-2 mt-2 text-gray-500 dark:text-gray-300">
                        <i class="fas fa-spinner fa-spin text-gray-400 dark:text-white text-xs"></i>
                        <span class="text-xs">Searching...</span>
                    </div>
                </div>



                <div wire:init="loadInitialCategories">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                        {{-- Skeleton Halaman Pertama --}}
                        @if (!$loaded)
                        <table class="w-full text-sm text-left text-gray-500 dark:text-white animate-pulse">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Category Name</th>
                                    <th class="px-6 py-3">Slug</th>
                                    <th class="px-6 py-3">Color</th>
                                    <th class="px-6 py-3">Image</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < 5; $i++) <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">
                                        <div class="h-4 bg-gray-300 rounded w-3/4 dark:bg-gray-600"></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="h-4 bg-gray-300 rounded w-1/2 dark:bg-gray-600"></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="h-6 w-6 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="h-10 w-10 rounded bg-gray-300 dark:bg-gray-600"></div>
                                    </td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        <div class="h-3 w-10 bg-gray-300 rounded-md dark:bg-gray-600"></div>
                                        <div class="h-3 w-10 bg-gray-300 rounded-md dark:bg-gray-600"></div>
                                    </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>

                        {{-- Tabel Data --}}
                        @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-white">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Category Name</th>
                                    <th class="px-6 py-3">Slug</th>
                                    <th class="px-6 py-3">Color</th>
                                    <th class="px-6 py-3">Image</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $category->name }}</td>
                                    <td class="px-6 py-4">{{ $category->slug }}</td>
                                    <td class="px-6 py-4">
                                        @if($category->color)
                                        <span class="inline-block w-6 h-6 rounded-full border"
                                            style="background-color: {{ $category->color }}"></span>
                                        @else
                                        <span class="text-gray-400 text-xs">No color</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                            alt="{{ $category->name }}" class="w-10 h-10 rounded object-cover">
                                        @else
                                        <span class="text-gray-400 text-xs">No image</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button 
                                                @click="showEditModal = true" 
                                                wire:click="edit({{ $category->id }})"
                                                class="inline-flex items-center justify-center w-20 bg-green-100 text-green-800 text-xs font-medium px-3 py-1 
                                                       rounded-full dark:bg-green-900 dark:text-green-300 hover:bg-green-200 transition">
                                                Edit
                                            </button>
                                            <button 
                                                @click="confirmDeleteModal = true"
                                                wire:click="confirmDelete({{ $category->id }})"
                                                class="inline-flex items-center justify-center w-20 bg-red-100 text-red-800 text-xs font-medium px-3 py-1 
                                                       rounded-full dark:bg-red-900 dark:text-red-300 hover:bg-red-200 transition">
                                                Delete
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                                @endforeach

                                {{-- Jika Data Kosong --}}
                                @if($categories->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No Categories found
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                        {{-- Tombol Load More --}}
                        @if ($categories->count() >= $limit && $totalCategories > $limit)
                        <div class="flex justify-center mt-4 mb-3">
                            <button wire:click="loadMore" wire:loading.remove wire:target="loadMore"
                                class="px-4 py-2 text-sm text-white bg-blue-600 rounded-full hover:bg-blue-700">
                                Load More
                            </button>

                            <button wire:loading wire:target="loadMore" wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm text-white bg-blue-800 rounded-full">
                                Loading..
                                <i class="fas fa-spinner fa-spin"></i>
                            </button>
                        </div>
                        @endif
                        @endif

                    </div>
                </div>




                {{-- Modal Add --}}
                <div x-show="showModalAdd" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

                    {{-- Modal Box --}}
                    <div @click.outside="showModalAdd = true" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="w-full max-w-md p-6 bg-white rounded shadow-lg dark:bg-gray-800">

                        <h2 class="mb-4 text-lg font-bold dark:text-white">Add New Category</h2>

                        <form wire:submit.prevent="store" x-data="{ progress: 0 }"
                            x-on:livewire-upload-start="progress = 0"
                            x-on:livewire-upload-progress="progress = $event.detail.progress"
                            x-on:livewire-upload-finish="progress = 0">

                            {{-- Name --}}
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input wire:model.defer="name" type="text" placeholder="Category name..."
                                class="w-full p-2 mb-4 border rounded dark:bg-gray-700 dark:text-white" />
                            @error('name')
                                <div wire:key="{{ now() }}" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                                    x-transition class="text-red-500 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror

                            {{-- Color --}}
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
                            <input wire:model.defer="color" type="color"
                                class="w-full p-2 mb-4 border rounded dark:bg-gray-700 dark:text-white" />
                            @error('color')
                                <div wire:key="{{ now() }}" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                                    x-transition class="text-red-500 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror

                            {{-- Image Upload --}}
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image
                                Upload</label>
                            <input wire:model="image" type="file" accept="image/*"
                                class="w-full p-2 mb-4 border rounded dark:bg-gray-700 dark:text-white" />
                            @error('image')
                                <div wire:key="{{ now() }}" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                                    x-transition class="text-red-500 text-sm">
                                    {{ $message }}
                                </div>
                            @enderror

                            {{-- Progress Bar --}}
                            <div x-show="progress > 0" class="w-full mb-4 bg-gray-300 rounded">
                                <div class="h-2 bg-blue-600 rounded" 
                                :class="progress < 100 ? 'bg-blue-600' : 'bg-green-600'"
                                :style="'width: ' + progress + '%'"></div>
                            </div>

                            {{-- Preview Image --}}
                            @if ($image)
                            <div class="mb-4 flex justify-center">
                                <div class="relative inline-block">
                                    {{-- Gambar Preview --}}
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                        class="w-32 h-32 object-cover rounded border" />

                                    {{-- Tombol Hapus --}}
                                    <button type="button" wire:click="removeImage" wire:target="removeImage"
                                        wire:loading.attr="disabled"
                                        class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/3 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow"
                                        title="Remove Image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @endif

                            {{-- Loading hanya saat upload --}}
                            <div wire:loading wire:target="image" class="flex items-center gap-2 mb-4 text-blue-500">
                                <span>Uploading image...</span>
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>

                            {{-- Loading hanya sata hapus image selected --}}
                            <div wire:loading wire:target="removeImage"
                                class="flex items-center gap-2 mb-4 text-blue-500">
                                <span>Removing image...</span>
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>

                              
                            {{-- Button --}}
                            <div class="flex justify-center gap-2">
                                {{-- Cancel --}}
                                <button type="button"  @click="showModalAdd = false"
                                    class="px-4 py-2 text-white bg-gray-500 rounded-full hover:bg-gray-600">
                                    Cancel
                                </button>
                                
                                <!-- Tombol Save -->
                                  <button 
                                      type="submit"
                                      wire:target="store,image,removeImage"
                                      wire:loading.attr="disabled"
                                      class="px-4 py-2 w-32 text-white bg-blue-500 rounded-full disabled hover:bg-blue-600">
                                      <span wire:loading.remove wire:target="store">Save</span>
                                      <span wire:loading wire:target="store" class="flex items-center justify-center">
                                          <i class="fas fa-spinner fa-spin"></i>
                                      </span>
                                  </button>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- Modal Edit --}}
                <div x-data="{ showEditModal: @entangle('showEditModal') }">
                    <div x-show="showEditModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

                        {{-- Modal Box --}}
                        <div @click.outside="showEditModal = true" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="w-full max-w-md p-6 bg-white rounded shadow-lg dark:bg-gray-800">

                            <h2 class="mb-4 text-lg font-bold dark:text-white">Edit Category</h2>

                            <form wire:submit.prevent="update" x-data="{ progress: 0 }"
                                x-on:livewire-upload-start="progress = 0"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                                x-on:livewire-upload-finish="progress = 0">

                                {{-- editName --}}
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                <input wire:model.defer="editName" type="text" placeholder="Category name..."
                                    class="w-full p-2 mb-4 border rounded dark:bg-gray-700 dark:text-white" />
                                @error('editName')
                                    <div wire:key="{{ now() }}" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                                        x-transition class="text-red-500 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror

                                {{-- editColor --}}
                                <label
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color</label>
                                <input wire:model.defer="editColor" type="color"
                                    class="w-full p-2 mb-4 border rounded dark:bg-gray-700 dark:text-white" />
                                @error('editColor')
                                    <div wire:key="{{ now() }}" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                                        x-transition class="text-red-500 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror

                                {{-- editImage Upload --}}
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image
                                    Upload</label>
                                <input wire:model="editImage" type="file" accept="image/*"
                                    class="w-full p-2 mb-4 border rounded dark:bg-gray-700 dark:text-white" />
                                @error('editImage')
                                    <div wire:key="{{ now() }}" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                                        x-transition class="text-red-500 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror

                                {{-- Progress Bar --}}
                                <div x-show="progress > 0" class="w-full mb-4 bg-gray-300 rounded">
                                    <div class="h-2 rounded"
                                        :class="progress < 100 ? 'bg-blue-600' : 'bg-green-600'"
                                        :style="'width: ' + progress + '%'">
                                    </div>
                                </div>


                                {{-- Preview editImage --}}
                                @if ($editImage)
                                <div class="mb-4 flex justify-center">
                                    <div class="relative inline-block">
                                        <img src="{{ $editImage->temporaryUrl() }}" alt="Preview"
                                            class="w-32 h-32 object-cover rounded border" />

                                        {{-- Tombol hapus image --}}
                                        <button type="button" wire:click="removeImageEdit" wire:target="removeImageEdit"
                                            wire:loading.attr="disabled"
                                            class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/3 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                @elseif($editImagePath)
                                <div class="mb-4 flex justify-center">
                                    <img src="{{ Storage::url($editImagePath) }}"
                                        class="w-32 h-32 object-cover rounded border" alt="{{ $editName }}" />
                                </div>
                                @endif

                                {{-- Loading hanya saat upload image --}}
                                <div wire:loading wire:target="editImage"
                                    class="flex items-center gap-2 mb-4 text-blue-500">
                                    <span>Uploading image...</span>
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>


                                {{-- Loading hanya saat hapus image selected --}}
                                <div wire:loading wire:target="removeImageEdit"
                                    class="flex items-center gap-2 mb-4 text-blue-500">
                                    <span>Removing image...</span>
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>

                                
                                {{-- Button --}}
                                <div class="flex justify-center gap-2">
                                    {{-- Cancel --}}
                                    <button type="button"  @click="showEditModal = false"
                                        class="px-4 py-2 text-white bg-gray-500 rounded-full hover:bg-gray-600">
                                        Cancel
                                    </button>
                                    
                                    <!-- Tombol Update -->
                                      <button 
                                          type="submit"
                                          wire:target="update,editImage,removeImageEdit"
                                          wire:loading.attr="disabled"
                                          class="px-4 py-2 w-32 text-white bg-blue-500 rounded-full disabled hover:bg-blue-600">
                                          <span wire:loading.remove wire:target="update">Update</span>
                                          <span wire:loading wire:target="update" class="flex items-center justify-center">
                                              <i class="fas fa-spinner fa-spin"></i>
                                          </span>
                                      </button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Konfirmasi Delete --}}
                <div x-data="{ showDeleteModal: @entangle('showDeleteModal') }">
                    <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

                        {{-- Modal Box --}}
                        <div @click.outside="showDeleteModal = false"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="w-full max-w-md p-6 bg-white rounded shadow-lg dark:bg-gray-800">

                            <h2 class="mb-4 text-lg font-bold dark:text-white">Delete Confirmation</h2>

                            <div class="flex justify-center mb-4">
                                <p class="text-center dark:text-gray-300">
                                    Are you sure you want to delete <span class="font-bold">{{ $deleteName}}</span>
                                    Category ?
                                    <span class="font-semibold text-red-600">This action cannot be undone.</span>
                                </p>
                            </div>

                            {{-- Button --}}
                            <div class="flex justify-center gap-2">
                                <button type="button" @click="showDeleteModal = false"
                                    class="px-4 py-2 text-white bg-gray-500 rounded-full hover:bg-gray-600">
                                    Cancel
                                </button>

                                <!--Delete Button-->
                                <button
                                    wire:target="delete" wire:click="delete"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 w-32 text-white bg-red-600 rounded-full disabled hover:bg-red-600">
                                    <span wire:loading.remove wire:target="delete">Delete</span>
                                    <span wire:loading wire:target="delete" class="flex items-center justify-center">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </button>

                            </div>

                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>
