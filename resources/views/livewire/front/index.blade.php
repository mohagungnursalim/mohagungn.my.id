@section('title', 'Tulisan Terbaru | MohAgungN')

@section('meta')
    <meta name="description" content="Kumpulan artikel, opini, catatan belajar, dan tulisan terbaru.">
@endsection

<div>

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
                                        wire:navigate.hover
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

                    <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-zinc-600 dark:text-zinc-400">
                        <span style="font-family: monospace">
                            Built with
                        </span>

                        {{-- Laravel --}}
                        <span class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="100" viewBox="0 0 256 264"><path fill="#FF2D20" d="M255.856 59.62c.095.351.144.713.144 1.077v56.568c0 1.478-.79 2.843-2.073 3.578L206.45 148.18v54.18a4.135 4.135 0 0 1-2.062 3.579l-99.108 57.053c-.227.128-.474.21-.722.299c-.093.03-.18.087-.278.113a4.15 4.15 0 0 1-2.114 0c-.114-.03-.217-.093-.325-.134c-.227-.083-.464-.155-.68-.278L2.073 205.938A4.128 4.128 0 0 1 0 202.36V32.656c0-.372.052-.733.144-1.083c.031-.119.103-.227.145-.346c.077-.216.15-.438.263-.639c.077-.134.19-.242.283-.366c.119-.165.227-.335.366-.48c.119-.118.274-.206.408-.309c.15-.124.283-.258.453-.356h.005L51.613.551a4.135 4.135 0 0 1 4.125 0l49.546 28.526h.01c.165.104.305.232.454.351c.134.103.284.196.402.31c.145.149.248.32.371.484c.088.124.207.232.279.366c.118.206.185.423.268.64c.041.118.113.226.144.35c.095.351.144.714.145 1.078V138.65l41.286-23.773V60.692c0-.36.052-.727.145-1.072c.036-.124.103-.232.144-.35c.083-.217.155-.44.268-.64c.077-.134.19-.242.279-.366c.123-.165.226-.335.37-.48c.12-.118.269-.206.403-.309c.155-.124.289-.258.454-.356h.005l49.551-28.526a4.13 4.13 0 0 1 4.125 0l49.546 28.526c.175.103.309.232.464.35c.128.104.278.197.397.31c.144.15.247.32.37.485c.094.124.207.232.28.366c.118.2.185.423.267.64c.047.118.114.226.145.35Zm-8.115 55.258v-47.04l-17.339 9.981l-23.953 13.792v47.04l41.297-23.773h-.005Zm-49.546 85.095V152.9l-23.562 13.457l-67.281 38.4v47.514l90.843-52.3ZM8.259 39.796v160.177l90.833 52.294v-47.505L51.64 177.906l-.015-.01l-.02-.01c-.16-.093-.295-.227-.444-.34c-.13-.104-.279-.186-.392-.3l-.01-.015c-.134-.129-.227-.289-.34-.433c-.104-.14-.227-.258-.31-.402l-.005-.016c-.093-.154-.15-.34-.217-.515c-.067-.155-.154-.3-.196-.464v-.005c-.051-.196-.061-.403-.082-.604c-.02-.154-.062-.309-.062-.464V63.57L25.598 49.772l-17.339-9.97v-.006ZM53.681 8.893L12.399 32.656l41.272 23.762L94.947 32.65L53.671 8.893h.01Zm21.468 148.298l23.948-13.786V39.796L81.76 49.778L57.805 63.569v103.608l17.344-9.986ZM202.324 36.935l-41.276 23.762l41.276 23.763l41.271-23.768l-41.27-23.757Zm-4.13 54.676l-23.953-13.792l-17.338-9.981v47.04l23.948 13.787l17.344 9.986v-47.04Zm-94.977 106.006l60.543-34.564l30.264-17.272l-41.246-23.747l-47.489 27.34l-43.282 24.918l41.21 23.325Z"/></svg>
                        </span>

                        {{-- Livewire --}}
                        <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="100" viewBox="0 0 128 128"><defs><path id="deviconLivewire0" fill="#fb70a9" fill-rule="evenodd" d="M108.566 83.547c-1.937 2.926-3.406 6.527-7.34 6.527c-6.624 0-6.98-10.203-13.609-10.203c-6.625 0-6.265 10.203-12.887 10.203c-6.625 0-6.98-10.203-13.609-10.203c-6.625 0-6.266 10.203-12.887 10.203c-6.625 0-6.98-10.203-13.605-10.203c-6.629 0-6.27 10.203-12.89 10.203c-2.083 0-3.544-1.008-4.778-2.39c-4.738-8.239-7.465-17.895-7.465-28.22c0-30.222 23.367-54.722 52.191-54.722c28.825 0 52.192 24.5 52.192 54.723c0 8.64-1.91 16.816-5.313 24.082Zm0 0"/></defs><use href="#deviconLivewire0" fill-rule="evenodd"/><path fill="#4e56a6" fill-rule="evenodd" d="M40.844 78.145v22.668c0 4.066-3.301 7.363-7.371 7.363a7.365 7.365 0 0 1-7.371-7.364V73.45c1.375-2.523 2.945-4.707 5.78-4.707c4.61 0 6.223 5.79 8.962 9.403Zm27.843 1.183v35.844a8.185 8.185 0 0 1-8.187 8.183c-4.523 0-8.191-3.664-8.191-8.183v-40.57c1.543-2.973 3.132-5.86 6.39-5.86c5.16 0 6.563 7.242 9.989 10.586Zm26.211-.66v26.023c0 4.067-3.3 7.364-7.37 7.364c-4.071 0-7.372-3.297-7.372-7.364V72.707c1.281-2.195 2.809-3.965 5.364-3.965c4.84 0 6.375 6.38 9.378 9.926Zm0 0"/><path fill-opacity=".298" fill-rule="evenodd" d="M40.844 85.094c-1.309-1.602-2.856-2.79-5.094-2.79c-5.316 0-6.293 6.696-9.648 9.712V63.145a7.365 7.365 0 0 1 7.37-7.364c4.071 0 7.372 3.297 7.372 7.364Zm27.843.515c-1.394-1.855-3.023-3.304-5.496-3.304c-5.914 0-6.457 8.285-10.882 10.578v-12.77c0-4.52 3.668-8.183 8.191-8.183a8.185 8.185 0 0 1 8.188 8.183Zm26.211-1.433c-1.136-1.117-2.48-1.871-4.265-1.871c-5.73 0-6.418 7.777-10.477 10.343V66.734a7.371 7.371 0 0 1 14.742 0Zm0 0"/><use href="#deviconLivewire0" fill-rule="evenodd"/><path fill="#e24ca6" fill-rule="evenodd" d="M97.273 88.984c13.676-20.332 14.028-42.879 1.059-67.652c9.613 9.844 15.547 23.348 15.547 38.25c0 8.61-1.98 16.75-5.508 23.992c-2.004 2.91-3.531 6.5-7.61 6.5a5.947 5.947 0 0 1-3.488-1.09Zm0 0"/><path fill="#fff" fill-rule="evenodd" d="M58.89 73.117c18.15 0 25.79-10.52 25.79-25.46c0-14.942-11.547-28.692-25.79-28.692c-14.245 0-25.792 13.75-25.792 28.691c0 14.942 7.64 25.461 25.793 25.461Zm0 0"/><path fill="#030776" fill-rule="evenodd" d="M61.625 37.836c0 5.89-4.332 10.668-9.672 10.668c-5.344 0-9.672-4.777-9.672-10.668c0-5.89 4.328-10.668 9.672-10.668c5.34 0 9.672 4.777 9.672 10.668Zm0 0"/><path fill="#fff" fill-rule="evenodd" d="M55.176 35.375c0 2.719-2.164 4.922-4.836 4.922s-4.836-2.203-4.836-4.922s2.164-4.922 4.836-4.922s4.836 2.203 4.836 4.922Zm0 0"/></svg>
                        </span>

                        {{-- Alpine.js --}}
                        <span class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="90" height="100" viewBox="0 0 128 128"><path fill="#2d3441" d="m43.342 68.821l.734-2.373h4.655l.734 2.373h3.388L48.56 55.329h-4.092L39.924 68.82h3.418zm4.655-4.756H44.89l1.578-4.936l1.529 4.936zm10.133 4.756V54.947h-3.217v13.874h3.217zm6.636 4.805v-5.222l.01.009c.101.093.209.177.324.251l.058.036c.489.295 1.099.442 1.83.442c.838 0 1.624-.208 2.357-.623c.734-.416 1.327-1.015 1.779-1.8c.452-.784.679-1.733.679-2.845c0-1.119-.223-2.071-.669-2.855c-.446-.784-1.024-1.384-1.734-1.8s-1.461-.623-2.252-.623c-.596 0-1.159.152-1.689.457c-.381.22-.709.536-.985.948l-.04.061l-.262-1.144h-2.624v14.708h3.218zm1.709-7.419a2.12 2.12 0 0 1-.855-.171a1.544 1.544 0 0 1-.623-.472a1.125 1.125 0 0 1-.231-.704v-1.629l.001-.069a1.77 1.77 0 0 1 .196-.789l.029-.052c.151-.258.36-.457.628-.598c.268-.141.573-.211.915-.211c.422 0 .786.097 1.091.292c.305.194.541.469.709.824c.168.355.251.771.251 1.247c0 .563-.112 1.015-.337 1.357c-.225.342-.499.59-.824.744a2.212 2.212 0 0 1-.95.231zm9.802-8.566c.59 0 1.056-.156 1.397-.467c.342-.312.513-.752.513-1.322c0-.556-.171-1-.513-1.332c-.342-.332-.808-.498-1.397-.498c-.61 0-1.081.166-1.412.498c-.332.332-.498.776-.498 1.332c0 .57.166 1.01.498 1.322c.331.311.802.467 1.412.467zm1.608 11.18v-9.963h-3.217v9.963h3.217zm6.917 0v-4.172l.001-.102c.008-.676.091-1.229.251-1.659l.025-.064c.184-.459.431-.793.739-1a1.777 1.777 0 0 1 1.015-.312c.362 0 .67.126.925.377s.382.638.382 1.161v5.771h3.237V63.05c0-.865-.127-1.632-.382-2.302c-.255-.67-.652-1.196-1.191-1.578c-.54-.382-1.235-.573-2.086-.573c-.744 0-1.409.208-1.996.623c-.364.258-.68.607-.949 1.049l-.028.046l-.326-1.397h-2.835v9.902h3.218zm13.682.321c.818 0 1.54-.147 2.166-.442c.627-.295 1.136-.694 1.528-1.196s.638-1.062.739-1.679H99.72a.975.975 0 0 1-.462.573a1.586 1.586 0 0 1-.774.181c-.362 0-.662-.085-.9-.256a1.56 1.56 0 0 1-.538-.719c-.121-.308-.181-.674-.181-1.096v.02h5.951c.159-.711.19-1.403.093-2.075l-.017-.112a4.758 4.758 0 0 0-.744-1.905a4.121 4.121 0 0 0-1.493-1.342c-.617-.332-1.34-.498-2.172-.498c-.838 0-1.622.208-2.352.623c-.731.416-1.32 1.014-1.769 1.795c-.449.781-.674 1.717-.674 2.81s.226 2.037.679 2.835c.452.798 1.044 1.411 1.774 1.84a4.553 4.553 0 0 0 2.343.643zm1.528-6.243H96.88v-.003c.024-.275.079-.519.165-.733l.027-.063a1.59 1.59 0 0 1 .583-.714c.251-.164.548-.246.89-.246c.308 0 .561.062.759.186c.198.124.35.288.457.493c.107.204.179.427.216.669c.018.121.03.24.034.359l.001.052zm6.324 6.102c.637 0 1.163-.193 1.578-.578c.416-.385.623-.876.623-1.473c0-.596-.208-1.092-.623-1.488c-.416-.395-.942-.593-1.578-.593c-.637 0-1.154.198-1.553.593c-.399.395-.598.891-.598 1.488c0 .59.199 1.079.598 1.468c.398.389.916.583 1.553.583zm6.142-11.36c.59 0 1.056-.156 1.397-.467c.342-.312.513-.752.513-1.322c0-.556-.171-1-.513-1.332c-.342-.332-.808-.498-1.397-.498s-1.059.166-1.407.498c-.349.332-.523.776-.523 1.332c0 .57.174 1.01.523 1.322c.348.311.818.467 1.407.467zm-1.92 16.337c1.173 0 2.061-.32 2.664-.96c.603-.64.905-1.721.905-3.242V58.878h-3.237v10.837c0 .576-.102.962-.307 1.156c-.204.194-.514.292-.93.292c-.322 0-.67-.047-1.046-.141a8.018 8.018 0 0 1-.995-.312v2.765c.462.127.922.243 1.377.347c.457.104.979.156 1.569.156zm11.089-4.836c.831 0 1.575-.154 2.232-.462c.657-.308 1.175-.709 1.553-1.201c.379-.493.568-1.014.568-1.563c0-.603-.194-1.106-.583-1.508c-.389-.402-.922-.737-1.598-1.005l-2.734-1.096c-.422-.147-.633-.349-.633-.603c0-.188.101-.338.302-.452c.201-.114.476-.171.824-.171c.422 0 .724.079.905.236a.718.718 0 0 1 .271.558h2.946c-.007-.952-.365-1.736-1.076-2.352c-.71-.617-1.726-.925-3.046-.925c-1.407 0-2.517.271-3.328.814c-.811.543-1.216 1.253-1.216 2.131c0 .623.193 1.161.578 1.614s.933.826 1.644 1.121l2.403.935c.255.101.442.201.563.302a.497.497 0 0 1 .181.402a.569.569 0 0 1-.131.357a.889.889 0 0 1-.392.266a1.866 1.866 0 0 1-.664.101c-.442 0-.793-.067-1.051-.201c-.258-.134-.394-.375-.407-.724h-2.905c-.007.771.193 1.409.598 1.915s.97.885 1.694 1.136c.723.25 1.557.375 2.502.375z"/><path fill="#77c1d2" fill-rule="evenodd" d="m27.508 54.436l7.288 7.256l-7.288 7.256l-7.288-7.256z" clip-rule="evenodd"/><path fill="#2d3441" fill-rule="evenodd" d="m9.288 54.436l15.109 15.043H9.821L2 61.692z" clip-rule="evenodd"/></svg>
                        </span>

                        {{-- Tailwind CSS --}}
                        <span class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="100" viewBox="0 0 128 128"><path fill="#38bdf8" d="M64.004 25.602c-17.067 0-27.73 8.53-32 25.597c6.398-8.531 13.867-11.73 22.398-9.597c4.871 1.214 8.352 4.746 12.207 8.66C72.883 56.629 80.145 64 96.004 64c17.066 0 27.73-8.531 32-25.602c-6.399 8.536-13.867 11.735-22.399 9.602c-4.87-1.215-8.347-4.746-12.207-8.66c-6.27-6.367-13.53-13.738-29.394-13.738zM32.004 64c-17.066 0-27.73 8.531-32 25.602C6.402 81.066 13.87 77.867 22.402 80c4.871 1.215 8.352 4.746 12.207 8.66c6.274 6.367 13.536 13.738 29.395 13.738c17.066 0 27.73-8.53 32-25.597c-6.399 8.531-13.867 11.73-22.399 9.597c-4.87-1.214-8.347-4.746-12.207-8.66C55.128 71.371 47.868 64 32.004 64zm0 0"/></svg>
                        </span>
                    </div>

                {{-- Section Header --}}
                <header class="mt-8 mb-6">
                    <hr>
                </header>

                {{-- Categories --}}
                <div class="mb-8">
                    <livewire:frontend.categories />
                </div>

                {{-- Section Divider --}}
                <header class="my-8">
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
                                <div class="flex items-center gap-x-2 text-[12px] sm:text-xs font-medium mb-2 flex-wrap">

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

                                    <span class="text-zinc-300 dark:text-zinc-700">&bull;</span>
                                    <span class="text-zinc-500 dark:text-zinc-400 flex items-center gap-1">
                                        <i class="far fa-eye text-xs"></i>
                                        {{ \App\Helpers\ViewsTrackingHelper::getPostViewCount($post) }}
                                    </span>

                                </div>

                                {{-- Title --}}
                                <h2 style="font-family:monospace" class="underline text-lg sm:text-xl font-bold leading-snug text-zinc-600 dark:text-zinc-100 group-hover:text-zinc-600 dark:group-hover:text-zinc-400 transition-colors duration-200">

                                    <a wire:navigate.hover href="{{ route('front.show', $post->slug) }}">
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

                {{-- Sentinel / Pagination --}}
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>

            </div>

        </div>
</div>