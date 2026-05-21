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
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
                    </a>
                    
                    <!-- Share Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="p-2 sm:p-2.5 text-zinc-500 hover:text-white hover:bg-[#1877F2] dark:text-zinc-400 dark:hover:text-white dark:hover:bg-[#1877F2] rounded-full transition-all focus:outline-none" aria-label="Share on Facebook">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path></svg>
                    </a>
                    
                    <!-- Share WhatsApp -->
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="p-2 sm:p-2.5 text-zinc-500 hover:text-white hover:bg-[#25D366] dark:text-zinc-400 dark:hover:text-white dark:hover:bg-[#25D366] rounded-full transition-all focus:outline-none" aria-label="Share on WhatsApp">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.012 2c5.506 0 9.998 4.476 9.998 9.984 0 1.954-.56 3.782-1.528 5.32l1.503 5.485-5.617-1.472a9.92 9.92 0 01-4.356 1.002C6.505 22.319 2 17.843 2 12.335 2 6.828 6.505 2 12.012 2zm.022 1.688c-4.57 0-8.297 3.712-8.297 8.264 0 1.77.56 3.42 1.554 4.773l-1.012 3.692 3.788-.992a8.214 8.214 0 003.967 1.02h.005c4.57 0 8.296-3.713 8.296-8.266 0-4.551-3.726-8.263-8.297-8.263zm4.52 11.233c-.247-.123-1.464-.716-1.69-.798-.227-.082-.393-.122-.559.122-.165.245-.64 .798-.785.96-.144.162-.289.184-.536.061-.247-.122-1.045-.383-1.99-1.226-.735-.655-1.23-1.465-1.375-1.71-.144-.244-.015-.376.108-.499.112-.112.247-.285.372-.428.123-.142.164-.244.247-.407.082-.162.042-.305-.02-.428-.062-.122-.56-1.336-.767-1.83-.201-.482-.405-.417-.559-.425-.144-.008-.31-.009-.475-.009-.165 0-.434.061-.661.305-.226.244-.866.838-.866 2.045 0 1.207.887 2.373 1.01 2.535.124.163 1.745 2.637 4.227 3.693.59.25 1.05.4 1.408.513.592.188 1.132.161 1.557.098.473-.07 1.464-.593 1.67-1.167.206-.573.206-1.064.145-1.166-.062-.102-.227-.163-.475-.285z" clip-rule="evenodd"></path></svg>
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
                        
                        <svg x-show="!copied" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        
                        <svg x-cloak x-show="copied" style="display: none;" class="w-4 h-4 sm:w-5 sm:h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
    
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
