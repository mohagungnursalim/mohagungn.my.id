@section('title', 'Tulisan Terbaru | MohAgungN')

@section('meta')
    <meta name="description" content="Kumpulan artikel, opini, catatan belajar, dan tulisan terbaru.">
@endsection

<div>
    <div class="space-y-12">
        <header class="mb-10 lg:mb-14">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-zinc-900 dark:text-zinc-100">Hello <mark class="px-2 pb-0.5 text-white bg-gray-900 rounded-md">World!</mark></h1>
            <p class="text-lg text-zinc-600 dark:text-zinc-400">Berbagi pengalaman, opini, dan catatan harian saya seputar pengembangan perangkat lunak, teknologi, kebijakan publik dan hal menarik lainnya.</p>
        </header>

        <div class="space-y-8 lg:space-y-10">
            @forelse ($posts as $post)
                <article class="group relative flex flex-col items-start sm:flex-row sm:gap-x-6">
                    @if($post->thumbnail)
                        <div class="w-full sm:w-1/4 shrink-0 mb-3 sm:mb-0 rounded-lg overflow-hidden bg-zinc-50 dark:bg-zinc-800/20">
                            <!-- Image Hover zoom effect -->
                            <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-auto sm:h-32 object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" loading="lazy">
                        </div>
                    @endif
                    <div class="w-full {{ $post->thumbnail ? 'sm:w-3/4' : '' }} flex flex-col justify-center h-full">
                        <div class="flex items-center gap-x-2 text-[12px] sm:text-xs font-medium mb-2">
                            <time datetime="{{ \Carbon\Carbon::parse($post->published_at)->toIso8601String() }}" class="text-zinc-500">{{ \Carbon\Carbon::parse($post->published_at)->translatedFormat('d F Y') }}</time>
                            @foreach($post->categories as $category)
                                <span class="text-zinc-300 dark:text-zinc-700">&bull;</span>
                                <span class="px-2 py-0.5 rounded-full text-xs bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold leading-snug text-zinc-900 dark:text-zinc-100 group-hover:text-zinc-600 dark:group-hover:text-zinc-400 transition-colors duration-200">
                            <a wire:navigate href="{{ route('front.show', $post->slug) }}">
                                <span class="absolute inset-0"></span>
                                {{ $post->title }}
                            </a>
                        </h2>
                        <div class="mt-2 text-[14px] leading-relaxed text-zinc-600 dark:text-zinc-400 line-clamp-2 font-serif sm:font-sans">
                            {!! Str::limit(strip_tags($post->content), 150) !!}
                        </div>
                    </div>
                </article>
                <div class="w-full border-t border-zinc-100 dark:border-zinc-800/80 last:hidden"></div>
            @empty
                <div class="py-12 px-4 rounded-xl border border-zinc-100 dark:border-zinc-800/80 bg-zinc-50/50 dark:bg-zinc-900/50 text-center text-zinc-500 dark:text-zinc-400">
                    <p class="text-[17px]">Belum ada artikel yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-12 flex justify-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
