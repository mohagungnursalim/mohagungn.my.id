@section('title', $category->name . ' | MohAgungN')

@section('meta')
    <meta name="description" content="Artikel tentang {{ $category->name }}">
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
                    <div class="flex items-center gap-3 mb-4">
                        <a
                            wire:navigate
                            href="/"
                            class="text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-200 transition-colors"
                            title="Kembali ke halaman utama"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <span class="text-zinc-400 dark:text-zinc-600">/</span>
                        <span class="text-zinc-600 dark:text-zinc-400">Kategori</span>
                    </div>

                    <h1 style="font-family:monospace" class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 text-zinc-900 dark:text-zinc-100">
                        {{ $category->name }}
                    </h1>

                    <p style="font-family:monospace" class="text-lg text-zinc-600 dark:text-zinc-400">
                        Kumpulan artikel tentang {{ strtolower($category->name) }}.
                    </p>
                </header>

                

                {{-- Posts --}}
                <div class="space-y-8 lg:space-y-10">

                    @forelse ($posts as $post)

                        <article class="group relative flex flex-col items-start sm:flex-row sm:gap-x-6">

                            {{-- Content --}}
                            <div class="w-full sm:w-3/4 flex flex-col justify-center h-full">

                                {{-- Meta --}}
                                <div class="flex items-center gap-x-2 text-[12px] sm:text-xs font-medium mb-2 flex-wrap">

                                    <time
                                        datetime="{{ \Carbon\Carbon::parse($post->published_at)->toIso8601String() }}">
                                        {{ \Carbon\Carbon::parse($post->published_at)->translatedFormat('d F Y') }}
                                    </time>

                                    <div class="h-1.5 w-1.5 rounded-full bg-zinc-300 dark:bg-zinc-700"></div>

                                    @foreach($post->categories as $postCategory)
                                        <a
                                            wire:navigate
                                            href="{{ route('front.category', $postCategory->slug) }}"
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] sm:text-xs font-semibold bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                                            title="Lihat lebih banyak artikel dari kategori ini">
                                            {{ $postCategory->name }}
                                        </a>
                                    @endforeach

                                    <div class="h-1.5 w-1.5 rounded-full bg-zinc-300 dark:bg-zinc-700"></div>
                                    <span class="text-zinc-500 dark:text-zinc-400 flex items-center gap-1">
                                        <i class="far fa-eye text-xs"></i>
                                        {{ \App\Helpers\ViewsTrackingHelper::getPostViewCount($post) }}
                                    </span>

                                </div>

                                {{-- Title --}}
                                <h2 class="text-lg sm:text-xl font-bold leading-tight text-zinc-900 dark:text-zinc-50 hover:text-zinc-600 dark:hover:text-zinc-400 transition-colors">

                                    <a
                                        wire:navigate
                                        href="{{ route('front.show', $post->slug) }}"
                                        class="hover:underline">
                                        {{ $post->title }}
                                    </a>

                                </h2>

                            </div>

                        </article>

                        <div class="w-full border-t border-zinc-100 dark:border-zinc-800/80 last:hidden"></div>

                    @empty

                        <div class="py-12 px-4 rounded-xl border border-zinc-100 dark:border-zinc-800/80 bg-zinc-50/50 dark:bg-zinc-900/50 text-center text-zinc-500 dark:text-zinc-400">
                            <i class="fa-regular fa-file fa-4x"></i>
                            <p class="text-[17px]">
                                Belum ada artikel di kategori ini.
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
