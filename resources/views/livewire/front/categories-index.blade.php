@section('title', 'Semua Kategori | MohAgungN')

@section('meta')
    <meta name="description" content="Jelajahi semua kategori artikel">
@endsection

<div>

        {{-- ===================== REAL CONTENT ===================== --}}
        <div>

            <div class="space-y-12">

                {{-- Hero Header --}}
                <header class="mb-10 lg:mb-14">
                    <h1 style="font-family:monospace" class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-zinc-900 dark:text-zinc-100">
                        Semua Kategori
                    </h1>

                    <p style="font-family:monospace" class="text-lg text-zinc-600 dark:text-zinc-400">
                        Temukan artikel berdasarkan kategori yang Anda minati.
                    </p>
                </header>

                {{-- Categories Grid --}}
                @if($categories->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($categories as $category)
                            <a
                                wire:navigate
                                href="{{ route('front.category', $category->slug) }}"
                                class="group relative rounded-xl border border-zinc-100 dark:border-zinc-800/50 bg-white dark:bg-zinc-900/30 hover:border-zinc-300 dark:hover:border-zinc-700 transition-all duration-300 overflow-hidden hover:shadow-lg hover:-translate-y-1"
                            >
                                {{-- Background Image --}}
                                @if($category->image)
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-10 transition-opacity duration-300">
                                        <img
                                            src="{{ Storage::url($category->image) }}"
                                            alt="{{ $category->name }}"
                                            class="w-full h-full object-cover"
                                        >
                                    </div>
                                @endif

                                {{-- Content --}}
                                <div class="relative p-6 flex flex-col h-full">

                                    {{-- Icon/Image --}}
                                    @if($category->image)
                                        <div class="mb-4 flex justify-start">
                                            <div class="w-14 h-14 rounded-lg overflow-hidden shadow-md ring-2 ring-zinc-100 dark:ring-zinc-800">
                                                <img
                                                    src="{{ Storage::url($category->image) }}"
                                                    alt="{{ $category->name }}"
                                                    class="w-full h-full object-cover"
                                                >
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-4 flex justify-start">
                                            <div class="w-14 h-14 rounded-lg bg-gradient-to-br from-zinc-200 to-zinc-300 dark:from-zinc-700 dark:to-zinc-800 flex items-center justify-center shadow-md">
                                                <svg class="w-7 h-7 text-zinc-600 dark:text-zinc-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 3a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H3a1 1 0 01-1-1V3zM12 3a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1V3zM2 13a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1v-2zM12 13a1 1 0 011-1h6a1 1 0 011 1v2a1 1 0 01-1 1h-6a1 1 0 01-1-1v-2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Title --}}
                                    <h3 style="font-family:monospace" class="text-xl font-bold text-zinc-900 dark:text-zinc-100 mb-2 group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors">
                                        {{ $category->name }}
                                    </h3>

                                    {{-- Slug as description --}}
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4 flex-grow">
                                        {{ ucfirst($category->slug) }}
                                    </p>

                                    {{-- Arrow Icon --}}
                                    <div class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-zinc-100 group-hover:translate-x-1 transition-all duration-300">
                                        <span class="text-sm font-medium">Lihat Artikel</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>

                                </div>

                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 px-4 rounded-xl border border-zinc-100 dark:border-zinc-800/80 bg-zinc-50/50 dark:bg-zinc-900/50 text-center text-zinc-500 dark:text-zinc-400">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-lg font-medium">
                            Belum ada kategori yang tersedia.
                        </p>
                    </div>
                @endif

            </div>

        </div>
</div>
