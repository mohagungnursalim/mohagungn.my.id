<div class="space-y-4">
    <h3 style="font-family:monospace" class="text-lg md:text-xl font-bold text-zinc-900 dark:text-zinc-100">
        Kategori
    </h3>

    @if($categories->count() > 0)
        <div class="flex flex-wrap gap-2 md:gap-3">
            @foreach($categories as $category)
                <a
                    wire:navigate
                    href="{{ route('front.category', $category->slug) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full transition-all duration-200 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-700 hover:shadow-md"
                    title="Lihat artikel dari kategori {{ $category->name }}"
                >
                    @if($category->image)
                        <img
                            src="{{ Storage::url($category->image) }}"
                            alt="{{ $category->name }}"
                            class="w-5 h-5 rounded-full object-cover"
                        >
                    @else
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H3a1 1 0 01-1-1V3zM12 3a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1V3zM2 13a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1v-2zM12 13a1 1 0 011-1h6a1 1 0 011 1v2a1 1 0 01-1 1h-6a1 1 0 01-1-1v-2z"></path>
                        </svg>
                    @endif

                    <span>{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    @else
        <div class="p-4 rounded-lg bg-zinc-50 dark:bg-zinc-900/50 text-zinc-600 dark:text-zinc-400 text-sm">
            Belum ada kategori yang tersedia.
        </div>
    @endif
</div>
