<div class="dark:bg-gray-800" wire:init="loadMetrics"> 
    <div class="py-12 bg-white dark:bg-gray-800 sm:rounded-lg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">Overview</h2>
            
            @if(!$loaded)
            {{-- Skeleton View --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                @for ($i = 0; $i < 4; $i++)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 dark:bg-gray-700 dark:border-gray-600">
                    <div class="flex items-center">
                        <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-lg animate-pulse">
                        </div>
                        <div class="ml-4 flex flex-col gap-2 w-full">
                            <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-24 animate-pulse"></div>
                            <div class="h-6 bg-gray-200 dark:bg-gray-600 rounded w-12 animate-pulse"></div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            @else
            {{-- Actual Data View --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Posts -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-blue-600 bg-blue-100 rounded-lg dark:text-blue-300 dark:bg-blue-900">
                            <i class="fa-regular fa-pen-to-square text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Total Posts</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($metrics['total_posts']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Published Posts -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-green-600 bg-green-100 rounded-lg dark:text-green-300 dark:bg-green-900">
                            <i class="fa-solid fa-check text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Published</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($metrics['published_posts']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Draft Posts -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-yellow-600 bg-yellow-100 rounded-lg dark:text-yellow-300 dark:bg-yellow-900">
                            <i class="fa-regular fa-file-lines text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Drafts</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($metrics['draft_posts']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Views -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 dark:bg-gray-700 dark:border-gray-600 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="inline-flex flex-shrink-0 justify-center items-center w-12 h-12 text-purple-600 bg-purple-100 rounded-lg dark:text-purple-300 dark:bg-purple-900">
                            <i class="fa-regular fa-eye text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 truncate dark:text-gray-400">Total Views</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($metrics['total_views']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-gray-50 dark:bg-gray-700 overflow-hidden border border-gray-100 dark:border-gray-600 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold">Welcome back, {{ auth()->user()->name ?? 'User' }}!</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Here is a quick summary of your website metrics.</p>
                    </div>
                    <div>
                        <a wire:navigate.hover href="{{ route('dashboard.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-medium text-white hover:bg-blue-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition">
                            <i class="fa-solid fa-plus mr-2"></i> Create New Post
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div> 
