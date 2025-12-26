<div class="dark:bg-gray-800">
    <div class="py-12 bg-white dark:bg-gray-800 sm:rounded-lg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            
            {{-- Add Button --}}
            <a href="{{ route('dashboard.posts.create') }}"
                class="mb-4 inline-block px-5 py-2.5 text-sm font-medium border border-blue-700 text-blue-700 hover:text-white hover:bg-blue-800 focus:outline-none rounded-lg">
                Add Post
            </a>

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


            <div wire:init="loadInitialPosts">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    {{-- Skeleton --}}
                    @if (!$loaded)
                        <table class="w-full text-sm text-left text-gray-500 dark:text-white">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Thumbnail</th>
                                    <th class="px-6 py-3">Title</th>
                                    <th class="px-6 py-3">Categories</th>
                                    <th class="px-6 py-3">Tags</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < 5; $i++)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 animate-pulse rounded"></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 animate-pulse"></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 animate-pulse"></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3 animate-pulse"></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <div class="w-12 h-5 bg-gray-200 dark:bg-gray-700 rounded-full animate-pulse"></div>
                                                <div class="w-12 h-5 bg-gray-200 dark:bg-gray-700 rounded-full animate-pulse"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-white">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Thumbnail</th>
                                    <th class="px-6 py-3">Title</th>
                                    <th class="px-6 py-3">Categories</th>
                                    <th class="px-6 py-3">Tags</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        {{-- Thumbnail --}}
                                        <td class="px-6 py-4">
                                            @if($post->thumbnail)
                                                <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Thumbnail"
                                                    class="w-16 h-16 object-cover rounded">
                                            @else
                                                <span class="text-gray-400 text-sm italic">No image</span>
                                            @endif
                                        </td>
            
                                        {{-- Title --}}
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $post->title }}
                                        </td>
            
                                        {{-- Categories --}}
                                        <td class="px-6 py-4">{{ $post->categories->pluck('name')->join(', ') }}</td>
            
                                        {{-- Tags --}}
                                        <td class="px-6 py-4">{{ $post->tags->pluck('name')->join(', ') }}</td>
            
                                        {{-- Status --}}
                                        <td class="px-6 py-4">
                                            @if($post->is_archived)
                                                <span class="inline-block px-3 py-0 text-xs font-medium rounded-full 
                                                             bg-gray-300 text-gray-800 dark:bg-gray-600 dark:text-gray-200">
                                                    Archived
                                                </span>
                                            @elseif($post->is_published)
                                                <span class="inline-block px-3 py-0 text-xs font-medium rounded-full 
                                                             bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    Published
                                                </span>
                                            @else
                                                <span class="inline-block px-3 py-0 text-xs font-medium rounded-full 
                                                             bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Action --}}
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('dashboard.posts.edit', $post->slug) }}"
                                                    class="inline-flex items-center justify-center w-20 bg-indigo-100 text-indigo-800 text-xs font-medium px-3 py-1 rounded-full 
                                                           dark:bg-indigo-900 dark:text-indigo-300 hover:bg-indigo-200 dark:hover:bg-indigo-500 dark:hover:text-indigo-50 transition">
                                                    Edit
                                                </a>
                                                <button 
                                                    @click="showDeleteModal = true" 
                                                    wire:click="confirmDelete({{ $post->id }})"
                                                    class="inline-flex items-center justify-center w-20 bg-red-100 text-red-800 text-xs font-medium px-3 py-1 rounded-full 
                                                           dark:bg-red-900 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-500 dark:hover:text-red-50 transition">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                                
                                {{-- Jika Data Kosong --}}
                                @if($posts->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No Posts found
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            {{-- Modal Konfirmasi Hapus  --}}
            <div x-data="{ showDeleteModal: @entangle('showDeleteModal') }">
                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

                    {{-- Modal Box  --}}
                    <div @click.outside="showDeleteModal = true"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="w-full max-w-md p-6 bg-white rounded shadow-lg dark:bg-gray-800">

                        <h2 class="mb-4 text-lg font-bold dark:text-white">Delete Confirmation</h2>

                        <div class="flex justify-center mb-4">
                            <p class="text-center dark:text-gray-300">
                                Are you sure you want to delete this <span
                                    class="font-bold">{{ $deleteName}}</span> ?
                                <span class="font-semibold text-red-600">This action cannot be undone.</span>
                            </p>
                        </div>

                        {{-- Button --}}
                        <div class="flex justify-center gap-2">
                            <button 
                                type="button" 
                                @click="showDeleteModal = false"
                                class="px-4 py-2 w-32 rounded-full 
                                       bg-gray-100 text-gray-800 
                                       hover:bg-gray-200 transition
                                       dark:bg-gray-700 dark:text-gray-200 
                                       dark:hover:bg-gray-600">
                                Cancel
                            </button>

                            
                            <!--Delete Button-->
                            <button
                                wire:target="delete"
                                wire:click="delete"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 w-32 rounded-full 
                                       bg-red-100 text-red-800 
                                       hover:bg-red-200 transition
                                       dark:bg-red-900 dark:text-red-300 
                                       dark:hover:bg-red-800 
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            
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
