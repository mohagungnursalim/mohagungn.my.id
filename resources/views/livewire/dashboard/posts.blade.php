<div class="dark:bg-gray-800">
    <div class="py-12 bg-white dark:bg-gray-800 sm:rounded-lg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Add Button --}}
            <a wire:navigate href="{{ route('dashboard.posts.create') }}"
                class="mb-4 inline-block px-5 py-2.5 text-sm font-medium border border-blue-700 text-blue-700 hover:text-white hover:bg-blue-800 focus:outline-none rounded-lg">
                Add Post
            </a>

            {{-- Search --}}
            <div class="relative w-full mb-3">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-300 dark:text-white text-lg"></i>
                </div>

                <input wire:model.live.debounce.500ms="search" type="text"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search post..." />
            </div>
            <div class="text-white" wire:loading wire:target='search'>Loading..</div>

            <div wire:init="loadInitialPosts">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    {{-- Skeleton --}}
                    @if (!$loaded)
                    {{-- mirip skeleton categories --}}
                    @else
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
                            @foreach($posts as $post)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

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

                                <td class="px-6 py-4">{{ $post->categories->pluck('name')->join(', ') }}</td>
                                <td class="px-6 py-4">{{ $post->tags->pluck('name')->join(', ') }}</td>

                                <td class="px-6 py-4">
                                    <a href="{{ route('dashboard.posts.edit', $post) }}"
                                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                        Edit
                                    </a>
                                    <button @click="showDeleteModal = true" wire:click="confirmDelete({{ $post->id }})"
                                        class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                    @endif
                </div>
            </div>

            {{-- Delete Confirmation Modal --}}
            <div x-data="{ showDeleteModal: @entangle('showDeleteModal') }">
                <div x-show="showDeleteModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="w-full max-w-md p-6 bg-white rounded shadow-lg dark:bg-gray-800">
                        <h2 class="mb-4 text-lg font-bold dark:text-white">Delete Confirmation</h2>
                        <p class="text-center dark:text-gray-300 mb-4">
                            Are you sure you want to delete <span class="font-bold">{{ $deleteName }}</span> Post?
                            <span class="font-semibold text-red-600">This action cannot be undone.</span>
                        </p>
                        <div class="flex justify-center gap-2">
                            <button @click="showDeleteModal = false"
                                class="px-4 py-2 text-white bg-gray-500 rounded-full hover:bg-gray-600">
                                Cancel
                            </button>
                            <button wire:click="delete" wire:loading.attr="disabled"
                                class="px-4 py-2 text-white bg-red-600 rounded-full hover:bg-red-700">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
