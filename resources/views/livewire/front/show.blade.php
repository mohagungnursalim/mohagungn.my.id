@section('title', $post->title . ' | MohAgungN')

@section('meta')
    @php
        $excerpt = Str::limit(strip_tags($post->content), 150);
        $imageUrl = $post->thumbnail ? asset(Storage::url($post->thumbnail)) : url('default-cover.jpg');
    @endphp
    
    <meta name="description" content="{{ $excerpt }}">
    <meta name="author" content="Mohagung">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $excerpt }}">
    <meta property="og:image" content="{{ $imageUrl }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $post->title }}">
    <meta property="twitter:description" content="{{ $excerpt }}">
    <meta property="twitter:image" content="{{ $imageUrl }}">
    
    <!-- Article specific -->
    <meta property="article:published_time" content="{{ \Carbon\Carbon::parse($post->published_at)->toIso8601String() }}">
    @if($post->updated_at)
        <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    @endif
    @foreach($post->tags as $tag)
        <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach
@endsection

<div>
    <article class="max-w-3xl mx-auto">
        <!-- Header -->
        <header class="mb-10 sm:mb-14 text-center">
            <div class="flex items-center justify-center gap-x-2 text-sm mb-6">
                <time datetime="{{ \Carbon\Carbon::parse($post->published_at)->toIso8601String() }}" class="font-medium text-zinc-500 dark:text-zinc-400">{{ \Carbon\Carbon::parse($post->published_at)->translatedFormat('d F Y') }}</time>
                @foreach($post->categories as $category)
                    <span class="text-zinc-300 dark:text-zinc-700">&bull;</span>
                    <span class="font-medium px-2.5 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">{{ $category->name }}</span>
                @endforeach
            </div>
            
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-100 mb-8 leading-tight sm:leading-tight">{{ $post->title }}</h1>
            
            @if($post->thumbnail)
                <figure class="mt-10 rounded-xl overflow-hidden bg-zinc-50 dark:bg-zinc-900/50">
                    <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->thumbnail_description ?? $post->title }}" class="w-full h-auto object-cover max-h-[500px]" loading="lazy">
                    @if($post->thumbnail_description)
                        <figcaption class="mt-3 text-sm text-center text-zinc-500 pb-3 font-medium">{{ $post->thumbnail_description }}</figcaption>
                    @endif
                </figure>
            @endif
        </header>

        <!-- Body -->
        <div class="prose prose-zinc dark:prose-invert prose-lg md:prose-xl w-full max-w-none text-[17px] leading-8 font-serif sm:font-sans">
           <article class="prose max-w-none">
                <style>
                    /* Default: Center align untuk image tanpa alignment khusus */
                    figure.image {
                        display: flex;
                        justify-content: center;
                        margin: 0 auto;
                    }
                    
                    /* Left align - override default */
                    figure.image.image-style-block-align-left {
                        display: block;
                        float: left;
                        margin: 0 1rem 1rem 0;
                        max-width: 50%;
                    }
                    
                    /* Right align - override default */
                    figure.image.image-style-block-align-right {
                        display: block;
                        float: right;
                        margin: 0 0 1rem 1rem;
                        max-width: 50%;
                    }
                    
                    /* Table center */
                    figure.table {
                        display: flex;
                        justify-content: center;
                        margin: 0 auto;
                        clear: both;
                    }
                    
                    figure.image img {
                        display: block;
                        width: 100%;
                        height: auto;
                    }
                    
                    figure.table table {
                        margin: 0 auto;
                    }
                    
                    /* Clear float sebelum footer */
                    article.prose::after {
                        content: "";
                        display: table;
                        clear: both;
                    }
                    
                    /* Link colors */
                    article.prose a {
                        color: #2563eb;
                    }
                    
                    article.prose a:hover {
                        color: #1d4ed8;
                        text-decoration: underline;
                    }
                </style>
                {!! $post->content !!}
            </article>
        </div>
        
        <!-- Footer / Tags & Share -->
        <footer class="mt-16 pt-8 border-t border-zinc-100 dark:border-zinc-800/80" x-data="{ copied: false, url: '{{ url()->current() }}' }">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                @if($post->tags->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <span class="text-[13px] font-medium text-zinc-500 bg-zinc-100 dark:bg-zinc-800/60 px-3 py-1 rounded-full">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @else
                    <div></div>
                @endif
                
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300 mr-2">Share:</span>
                    
                    <!-- Share Twitter -->
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="p-2 sm:p-2.5 text-zinc-500 hover:text-white hover:bg-black dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-700 rounded-full transition-all focus:outline-none" aria-label="Share on X">
                      <i class="fab fa-x-twitter"></i>
                    </a>
                    
                    <!-- Share Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="p-2 sm:p-2.5 text-zinc-500 hover:text-white hover:bg-[#1877F2] dark:text-zinc-400 dark:hover:text-white dark:hover:bg-[#1877F2] rounded-full transition-all focus:outline-none" aria-label="Share on Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    
                    <!-- Share WhatsApp -->
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="p-2 sm:p-2.5 text-zinc-500 hover:text-white hover:bg-[#25D366] dark:text-zinc-400 dark:hover:text-white dark:hover:bg-[#25D366] rounded-full transition-all focus:outline-none" aria-label="Share on WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    
                    <!-- Copy Link using Alpine -->
                    <button @click="
                            navigator.clipboard.writeText(url);
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                        " 
                        class="p-2 sm:p-2.5 text-zinc-500 hover:text-white hover:bg-zinc-800 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-700 rounded-full transition-all focus:outline-none relative group" 
                        aria-label="Copy Link" 
                        title="Copy Link">
                        
                        
                        <i x-show="!copied" class="fa-solid fa-copy"></i>
                        
                        <i x-show="copied" class="fa-solid fa-check text-green-500"></i>
    
                        <!-- Tooltip -->
                        <span x-cloak x-show="copied" x-transition.opacity style="display: none;" class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-zinc-900 border border-zinc-800 text-white text-xs py-1 px-2.5 rounded whitespace-nowrap hidden sm:block">
                            Disalin!
                        </span>
                    </button>
                    <!-- Mobile explicit text -->
                    <span x-cloak x-show="copied" x-transition.opacity style="display: none;" class="text-[12px] text-green-600 dark:text-green-500 font-medium sm:hidden ml-1">Disalin!</span>
                </div>
            </div>
        </footer>
    </article>
    
</div>
