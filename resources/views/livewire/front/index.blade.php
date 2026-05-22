@section('title', 'Tulisan Terbaru | MohAgungN')

@section('meta')
    <meta name="description" content="Kumpulan artikel, opini, catatan belajar, dan tulisan terbaru.">
@endsection

<div wire:init="loadInitialPosts">

    @if(!$readyToLoad)
        {{-- ===================== INITIAL PAGE SKELETON ===================== --}}
        <div
            wire:loading.flex
            wire:target="loadInitialPosts"
            class="flex-col space-y-12 animate-pulse">

            {{-- Hero Header --}}
            <div class="mb-10 lg:mb-14 space-y-4">
                <div class="h-10 w-72 rounded-xl bg-zinc-200 dark:bg-zinc-800"></div>

                <div class="space-y-2">
                    <div class="h-4 w-full max-w-3xl rounded-full bg-zinc-100 dark:bg-zinc-800/60"></div>
                    <div class="h-4 w-5/6 rounded-full bg-zinc-100 dark:bg-zinc-800/60"></div>
                </div>
            </div>

            {{-- Intro Skeleton --}}
            <div class="rounded-2xl border border-zinc-100 dark:border-zinc-800/50 bg-zinc-50/70 dark:bg-zinc-900/30 p-8 sm:p-10 lg:p-12">
                <div class="flex flex-col lg:flex-row lg:items-center lg:gap-8">

                    <div class="flex-1 space-y-5">

                        <div class="h-7 w-32 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>

                        <div class="space-y-3">
                            <div class="h-8 w-full max-w-2xl rounded-xl bg-zinc-200 dark:bg-zinc-800"></div>
                            <div class="h-8 w-3/4 rounded-xl bg-zinc-200 dark:bg-zinc-800"></div>
                        </div>

                        <div class="space-y-3">
                            <div class="h-4 w-full rounded-full bg-zinc-100 dark:bg-zinc-800/60"></div>
                            <div class="h-4 w-11/12 rounded-full bg-zinc-100 dark:bg-zinc-800/60"></div>
                            <div class="h-4 w-4/5 rounded-full bg-zinc-100 dark:bg-zinc-800/60"></div>
                        </div>

                        <div class="h-11 w-44 rounded-xl bg-zinc-200 dark:bg-zinc-800"></div>

                    </div>

                    <div class="hidden lg:block">
                        <div class="w-48 h-48 rounded-2xl bg-zinc-200 dark:bg-zinc-800"></div>
                    </div>

                </div>
            </div>


            {{-- Posts Skeleton --}}
            <div class="space-y-8 lg:space-y-10">

                @for ($i = 0; $i < 5; $i++)

                    <div>

                        <article class="group relative flex flex-col items-start sm:flex-row sm:gap-x-6">

                            {{-- Content --}}
                            <div class="w-full sm:w-3/4 flex flex-col justify-center">

                                {{-- Meta --}}
                                <div class="flex items-center gap-x-2 mb-3">

                                    {{-- Date --}}
                                    <div class="h-3 w-24 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>

                                    {{-- Dot --}}
                                    <div class="h-1.5 w-1.5 rounded-full bg-zinc-300 dark:bg-zinc-700"></div>

                                    {{-- Category --}}
                                    <div class="h-5 w-16 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>

                                    {{-- Category --}}
                                    <div class="h-5 w-20 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>

                                </div>

                                {{-- Title --}}
                                <div class="space-y-2">
                                    <div class="h-5 w-11/12 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>
                                    <div class="h-5 w-3/4 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>
                                </div>

                            </div>

                        </article>

                        <div class="mt-8 border-t border-zinc-100 dark:border-zinc-800/80"></div>

                    </div>

                @endfor

            </div>

        </div>
    @else
        {{-- ===================== REAL CONTENT ===================== --}}
        <div
            wire:loading.remove
            wire:target="loadInitialPosts">

            <div class="space-y-12">

                {{-- Hero Header --}}
                <header class="mb-10 lg:mb-14">
                    <h1 style="font-family:monospace" class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-zinc-900 dark:text-zinc-100">
                        Hello <i class="fa-regular fa-hand-spock"></i> 

                        <a href="https://user-images.githubusercontent.com/72663882/171687151-bb31c996-c9d2-49c8-b593-734946893b23.gif"></a>
                        <mark class="px-2 pb-0.5 text-white bg-gray-900 rounded-md">World!</mark>
                    </h1>

                    <p style="font-family:monospace" class="text-lg text-zinc-600 dark:text-zinc-400">
                        Berbagi pengalaman, opini, dan catatan harian saya seputar pengembangan perangkat lunak,
                        teknologi, kebijakan publik dan hal menarik lainnya.
                    </p>
                </header>

                {{-- Intro Post --}}
                @if($introPost)
                    <div class="group relative backdrop-blur-sm bg-gradient-to-br from-zinc-50/80 to-zinc-100/80 dark:from-zinc-900/40 dark:to-zinc-800/40 rounded-2xl border border-zinc-100/50 dark:border-zinc-700/30 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">

                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent dark:via-white/2 pointer-events-none"></div>

                        <div class="relative p-8 sm:p-10 lg:p-12">

                            <div class="flex flex-col lg:flex-row lg:items-center lg:gap-8">

                                {{-- Content --}}
                                <div class="flex-1">

                                    <div class="flex items-center gap-2 mb-4">
                                        <span style="font-family:monospace" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-zinc-200 to-zinc-100 dark:from-zinc-700 dark:to-zinc-800 text-zinc-700 dark:text-zinc-300">
                                            Closer to Me
                                        </span>
                                    </div>

                                    <h2 style="font-family:monospace" class="text-2xl sm:text-3xl font-bold leading-tight text-zinc-900 dark:text-zinc-50 mb-4">
                                        {{ $introPost->title }}
                                    </h2>

                                    <p class="text-base sm:text-lg text-zinc-600 dark:text-zinc-300 leading-relaxed mb-6 line-clamp-3">
                                        {!! Str::limit(strip_tags($introPost->content), 200) !!}
                                    </p>

                                    <a
                                        wire:navigate
                                        href="{{ route('front.show', $introPost->slug) }}"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-zinc-900 dark:text-zinc-50 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 hover:border-zinc-400 dark:hover:border-zinc-500 hover:shadow-md transition-all duration-200 group/btn"
                                    >
                                        Baca Selengkapnya

                                        <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>

                                </div>

                                {{-- Image --}}
                                @if($introPost->thumbnail)
                                    <div class="hidden lg:block mt-8 lg:mt-0">

                                        <div class="relative w-48 h-48 rounded-xl overflow-hidden shadow-lg group/img">

                                            <img
                                                src="{{ Storage::url($introPost->thumbnail) }}"
                                                alt="{{ $introPost->title }}"
                                                class="w-full h-full object-cover transition-transform duration-300 group-hover/img:scale-110"
                                            >

                                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover/img:opacity-100 transition-opacity duration-300"></div>

                                        </div>

                                    </div>
                                @endif

                            </div>

                        </div>

                    </div>
                @endif

                {{-- Section Header --}}
                <header class="mt-8 mb-6">
                    <hr>
                </header>

                {{-- Posts --}}
                <div class="space-y-8 lg:space-y-10">

                    @forelse ($posts as $post)

                        <article class="group relative flex flex-col items-start sm:flex-row sm:gap-x-6">

                            {{-- Thumbnail --}}
                            {{-- @if($post->thumbnail)
                                <div class="w-full sm:w-1/4 shrink-0 mb-3 sm:mb-0 rounded-lg overflow-hidden bg-zinc-50 dark:bg-zinc-800/20">

                                    <img
                                        src="{{ Storage::url($post->thumbnail) }}"
                                        alt="{{ $post->title }}"
                                        class="w-full h-auto sm:h-32 object-cover transition-transform duration-500 ease-in-out group-hover:scale-105"
                                        loading="lazy"
                                    >

                                </div>
                            @endif --}}

                            {{-- Content --}}
                            <div class="w-full {{ $post->thumbnail ? 'sm:w-3/4' : '' }} flex flex-col justify-center h-full">

                                {{-- Meta --}}
                                <div class="flex items-center gap-x-2 text-[12px] sm:text-xs font-medium mb-2">

                                    <time
                                        datetime="{{ \Carbon\Carbon::parse($post->published_at)->toIso8601String() }}">
                                        {{ \Carbon\Carbon::parse($post->published_at)->translatedFormat('d F Y') }}
                                    </time>
                                    

                                    @foreach($post->categories as $category)

                                        <span class="text-zinc-300 dark:text-zinc-700">&bull;</span>

                                        <span style="font-family:monospace"class="px-2 py-0.5 rounded-full text-xs bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">
                                            {{ $category->name }}
                                        </span>

                                    @endforeach

                                </div>

                                {{-- Title --}}
                                <h2 style="font-family:monospace" class="underline text-lg sm:text-xl font-bold leading-snug text-zinc-600 dark:text-zinc-100 group-hover:text-zinc-600 dark:group-hover:text-zinc-400 transition-colors duration-200">

                                    <a wire:navigate href="{{ route('front.show', $post->slug) }}">
                                        <span class="absolute inset-0"></span>
                                        {{ $post->title }}
                                    </a>

                                </h2>

                                {{-- Excerpt --}}
                                {{-- <div class="mt-2 text-[14px] leading-relaxed text-zinc-600 dark:text-zinc-400 line-clamp-2 font-serif sm:font-sans">
                                    {!! Str::limit(strip_tags($post->content), 150) !!}
                                </div> --}}

                            </div>

                        </article>

                        <div class="w-full border-t border-zinc-100 dark:border-zinc-800/80 last:hidden"></div>

                    @empty

                        <div class="py-12 px-4 rounded-xl border border-zinc-100 dark:border-zinc-800/80 bg-zinc-50/50 dark:bg-zinc-900/50 text-center text-zinc-500 dark:text-zinc-400">
                            <p class="text-[17px]">
                                Belum ada artikel yang dipublikasikan.
                            </p>
                        </div>

                    @endforelse

                </div>

                {{-- ===================== LOAD MORE SKELETON ===================== --}}
                <div
                    wire:loading.flex
                    wire:target="loadMore"
                    class="flex-col space-y-8 lg:space-y-10 mt-8 lg:mt-10 animate-pulse"
                    aria-hidden="true"
                >

                    @for ($i = 0; $i < 3; $i++)

                        <div>

                            <article class="group relative flex flex-col items-start sm:flex-row sm:gap-x-6">

                                {{-- Thumbnail --}}
                                <div class="w-full sm:w-1/4 shrink-0 mb-3 sm:mb-0 rounded-lg overflow-hidden">
                                    <div class="w-full h-52 sm:h-32 rounded-lg bg-zinc-200 dark:bg-zinc-800"></div>
                                </div>

                                {{-- Content --}}
                                <div class="w-full sm:w-3/4 flex flex-col justify-center">

                                    {{-- Meta --}}
                                    <div class="flex items-center gap-x-2 mb-3">

                                        <div class="h-3 w-24 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>

                                        <div class="h-1.5 w-1.5 rounded-full bg-zinc-300 dark:bg-zinc-700"></div>

                                        <div class="h-5 w-16 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>

                                    </div>

                                    {{-- Title --}}
                                    <div class="space-y-2">
                                        <div class="h-5 w-11/12 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>
                                        <div class="h-5 w-3/4 rounded-full bg-zinc-200 dark:bg-zinc-800"></div>
                                    </div>

                                    {{-- Excerpt --}}
                                    <div class="mt-4 space-y-2">
                                        <div class="h-3.5 w-full rounded-full bg-zinc-100 dark:bg-zinc-800/60"></div>
                                        <div class="h-3.5 w-5/6 rounded-full bg-zinc-100 dark:bg-zinc-800/60"></div>
                                    </div>

                                </div>

                            </article>

                            <div class="w-full mt-8 border-t border-zinc-100 dark:border-zinc-800/80"></div>

                        </div>

                    @endfor

                </div>

                {{-- Sentinel --}}
                @if($hasMore)

                    <div
                        x-data
                        x-intersect.once="false"
                        id="infinite-scroll-sentinel"
                        class="h-1 w-full"
                        aria-hidden="true"
                    ></div>

                @else

                    @if(count($posts) > 0)
                        <div class="mt-10 text-center text-sm text-zinc-400 dark:text-zinc-600">
                            — Semua artikel sudah ditampilkan —
                        </div>
                    @endif

                @endif

            </div>

        </div>
    @endif
</div>