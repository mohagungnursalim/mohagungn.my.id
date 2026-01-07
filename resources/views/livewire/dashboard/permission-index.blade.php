<div>
    <div class="p-6">
        <h1 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">
            Permission Management
        </h1>

        {{-- Create Permission --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
            <form wire:submit.prevent="create" class="flex gap-3">
                <input type="text" wire:model.live="name" placeholder="Permission name (e.g. edit post)" class="w-full rounded-lg px-3 py-2
                        border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-500 dark:text-gray-400
                        focus:ring focus:ring-blue-200 dark:focus:ring-blue-500
                        focus:outline-none" />
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg
                           hover:bg-blue-700 dark:hover:bg-blue-500">
                    Add
                </button>
            </form>

            @error('name')
            <p class="text-sm text-red-600 dark:text-red-400 mt-2">
                {{ $message }}
            </p>
            @enderror
        </div>

        {{-- Permission List --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm text-left text-gray-900 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Permission</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="px-4 py-3 font-medium">
                            {{ $permission->name }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" @click="confirmDeleteModal = true"
                                wire:click="confirmDelete({{ $permission->id }})"
                                wire:target="confirmDelete({{ $permission->id }})" wire:loading.attr="disabled" class="inline-flex items-center justify-center w-20
                                bg-red-100 text-red-800 text-xs font-medium
                                px-3 py-1 rounded-full
                                dark:bg-red-900 dark:text-red-300
                                hover:bg-red-200 dark:hover:bg-red-500 dark:hover:text-red-50
                                transition
                                disabled:opacity-50">

                                <span wire:loading.remove wire:target="confirmDelete({{ $permission->id }})">
                                    Delete
                                </span>

                                <span wire:loading wire:target="confirmDelete({{ $permission->id }})"
                                    class="flex items-center justify-center">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </button>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        {{-- Delete Confirmation Modal  --}}
        <div x-data="{ showDeleteModal: @entangle('showDeleteModal') }">
            <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

                {{-- Modal Box  --}}
                <div @click.outside="showDeleteModal = true" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="w-full max-w-md p-6 bg-white rounded shadow-lg dark:bg-gray-800">

                    <h2 class="mb-4 text-lg font-bold dark:text-white">Delete Confirmation</h2>

                    <div class="flex justify-center mb-4">
                        <p class="text-center dark:text-gray-300">
                            Are you sure you want to delete this <span class="font-bold">{{ $deleteName}}</span> ?
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
                        <button wire:target="delete" wire:click="delete" wire:loading.attr="disabled"
                            class="px-4 py-2 w-32 text-white bg-red-600 rounded-full disabled hover:bg-red-600 disabled:opacity-50 disabled:cursor-not-allowed">
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
