<div>
    <div class="p-6">
    <h1 class="text-xl font-semibold mb-6">Permission Management</h1>

    {{-- Create Permission --}}
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form wire:submit.prevent="create" class="flex gap-3">
            <input
                type="text"
                wire:model.live="name"
                placeholder="Permission name (e.g. edit post)"
                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
            />
            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Add
            </button>
        </form>
        @error('name')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Permission List --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3">Permission</th>
                    <th class="px-4 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-medium">
                            {{ $permission->name }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button
                                wire:click="delete({{ $permission->id }})"
                                onclick="confirm('Delete permission?') || event.stopImmediatePropagation()"
                                class="text-red-600 hover:underline"
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
