<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto dark:bg-gray-900 min-h-[60vh]">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('dashboard.workout') }}" wire:navigate class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline mb-2">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Calendar
            </a>
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                {{ $type }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Scheduled Date: <span class="font-medium text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</span>
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
        <div class="flex items-center gap-4 text-gray-600 dark:text-gray-300">
            <div class="w-12 h-12 rounded-xl {{ $type === 'Running' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400' : 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400' }} flex items-center justify-center text-xl">
                <i class="fa-solid {{ $type === 'Running' ? 'fa-person-running' : 'fa-dumbbell' }}"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $type }} Details Page</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Target route for {{ $date }}. Full activity details, interval workouts, and strength sets will be expanded in the subsequent part.</p>
            </div>
        </div>
    </div>
</div>

