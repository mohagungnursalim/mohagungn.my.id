<div>
    <div class="p-6 text-gray-900 dark:text-gray-100">

        <h1 class="text-xl font-semibold mb-6">
            Role Management
        </h1>

        {{-- Create Role --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
            <form wire:submit.prevent="create" class="flex gap-3">
                <input
                    type="text"
                    wire:model="name"
                    placeholder="Role name"
                    class="
                        w-full rounded-lg px-3 py-2
                        border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-400 dark:text-gray-100
                        focus:ring focus:ring-blue-200 dark:focus:ring-blue-500
                        focus:outline-none
                    "
                />
                <button
                    class="
                        bg-blue-600 text-white px-4 py-2 rounded-lg
                        hover:bg-blue-700
                        dark:bg-blue-500 dark:hover:bg-blue-600
                    "
                >
                    Add
                </button>
            </form>

            @error('name')
                <p class="text-sm text-red-600 dark:text-red-400 mt-2">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Role List --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Users</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($roles as $role)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="px-4 py-3 font-medium">
                                {{ $role->name }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $role->users_count }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button
                                    wire:click="delete({{ $role->id }})"
                                    onclick="confirm('Delete role?') || event.stopImmediatePropagation()"
                                    class="text-red-600 dark:text-red-400 hover:underline"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
