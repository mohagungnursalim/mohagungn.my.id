<div class="p-6 text-gray-900 dark:text-gray-100">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold">Users Management</h1>


        <!--Add Button-->
        <button wire:target="create" wire:click="create" wire:loading.attr="disabled"
            class="px-4 py-2 w-32 text-white bg-blue-600 rounded-full disabled hover:bg-blue-600 disabled:opacity-90">
            <span wire:loading.remove wire:target="create">Add+</span>
            <span wire:loading wire:target="create" class="flex items-center justify-center">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        </button>



    </div>


    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr class="border-t dark:border-gray-700">
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        {{ $user->roles->pluck('name')->join(', ') ?: '-' }}
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        {{-- Edit --}}
                        <button type="button" wire:click="edit({{ $user->id }})" wire:target="edit({{ $user->id }})"
                            wire:loading.attr="disabled" class="inline-flex items-center justify-center w-20
                            bg-indigo-100 text-indigo-800 text-xs font-medium
                            px-3 py-1 rounded-full
                            dark:bg-indigo-900 dark:text-indigo-300
                            hover:bg-indigo-200 dark:hover:bg-indigo-500 dark:hover:text-indigo-50
                            transition
                            disabled:opacity-50">

                            <span wire:loading.remove wire:target="edit({{ $user->id }})">
                                Edit
                            </span>

                            <span wire:loading wire:target="edit({{ $user->id }})"
                                class="flex items-center justify-center">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>

                        {{-- Delete --}}
                        <button type="button" @click="confirmDeleteModal = true"
                            wire:click="confirmDelete({{ $user->id }})" wire:target="confirmDelete({{ $user->id }})"
                            wire:loading.attr="disabled" class="inline-flex items-center justify-center w-20
                            bg-red-100 text-red-800 text-xs font-medium
                            px-3 py-1 rounded-full
                            dark:bg-red-900 dark:text-red-300
                            hover:bg-red-200 transition
                            disabled:opacity-50">

                            <span wire:loading.remove wire:target="confirmDelete({{ $user->id }})">
                                Delete
                            </span>

                            <span wire:loading wire:target="confirmDelete({{ $user->id }})"
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

    {{-- Modal --}}
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4 dark:text-white">
                {{ $userId ? 'Edit User' : 'Create User' }}
            </h2>

            <div class="space-y-3">

                {{-- Name --}}
                <div>
                    <input wire:model.defer="name" placeholder="Name" class="w-full rounded border px-3 py-2
                           bg-white dark:bg-gray-700
                           border-gray-300 dark:border-gray-600" />
                    @error('name')
                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <input wire:model.defer="email" placeholder="Email" class="w-full rounded border px-3 py-2
                           bg-white dark:bg-gray-700
                           border-gray-300 dark:border-gray-600" />
                    @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <input wire:model.defer="password" type="password"
                        placeholder="{{ $userId ? 'New password (optional)' : 'Password' }}" class="w-full rounded border px-3 py-2
                           bg-white dark:bg-gray-700
                           border-gray-300 dark:border-gray-600" />
                    @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div>
                    <select wire:model.defer="role" class="w-full rounded border px-3 py-2
                           bg-white dark:bg-gray-700
                           border-gray-300 dark:border-gray-600">
                        <option value="">-- Select Role --</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
                    @error('role')
                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex justify-center mt-4 gap-2">
                <button type="button" wire:click="$set('showModal', false)"
                    class="px-4 py-2 text-white bg-gray-500 rounded-full hover:bg-gray-600">
                    Cancel
                </button>

                <!--Save Button-->
                <button wire:target="save" wire:click="save" wire:loading.attr="disabled"
                    class="px-4 py-2 w-32 text-white bg-green-600 rounded-full disabled hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="save">Save</span>
                    <span wire:loading wire:target="save" class="flex items-center justify-center">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </button>

            </div>
        </div>
    </div>
    @endif



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
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
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
