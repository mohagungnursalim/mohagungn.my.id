<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MohAgungN | Blog')</title>
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { text-rendering: optimizeLegibility; }
        .prose pre { padding: 1.25rem; border-radius: 0.5rem; }
    </style>
    @livewireStyles
</head>
<body class="bg-white text-zinc-900 dark:bg-zinc-950 dark:text-zinc-300 antialiased transition-colors duration-200 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-md border-b border-zinc-100 dark:border-zinc-800/80">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a wire:navigate href="{{ route('front.index') }}" class="text-xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100 cursor-pointer hover:opacity-80 transition-opacity">
                    MohAgungN<span class="text-zinc-400">.</span>
                </a>
                <div class="flex items-center space-x-4">
                    @if(request()->routeIs('front.show'))
                        <a wire:navigate href="{{ route('front.index') }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 pl-1 pr-2 rounded-md text-sm cursor-pointer font-medium hover:text-black dark:hover:text-white transition-all duration-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        {{ $slot }}
    </main>

    

    <!-- Footer -->
    <footer class="border-t border-zinc-100 dark:border-zinc-800 mt-auto">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex items-center justify-between text-sm text-zinc-500 dark:text-zinc-400">
            <p>&copy; {{ date('Y') }} MohAgungN.</p>
            <div class="flex space-x-4">
               <a href="https://www.instagram.com/mohagungn/" class="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Instagram</a>
               <a href="https://github.com/mohagungnursalim" class="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">GitHub</a>
            </div>
        </div>
    </footer>
    <!-- Scroll To Top Button -->
    <div
        x-data="{ show: false }"
        x-on:scroll.window="show = window.scrollY > 300"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        x-cloak
        class="fixed bottom-6 right-6 z-50"
    >
        <button
            x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            aria-label="Scroll to top"
            class="group flex items-center justify-center w-10 h-10 rounded-full bg-zinc-800 dark:bg-zinc-700 text-white shadow-lg hover:bg-zinc-900 dark:hover:bg-zinc-600 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200"
        >
            <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>

    @livewireScripts
</body>
</html>
