<div class="px-3 sm:px-6 lg:px-8 py-6 w-full max-w-7xl mx-auto dark:bg-gray-900 min-h-screen"
     x-data="{ showModal: false }"
     x-on:open-date-modal.window="showModal = true"
     x-on:close-modal.window="showModal = false"
     @keydown.escape.window="showModal = false">

    {{-- Top Controls: Title & Navigation --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">Workout Planning</h1>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                Schedule, track, and review your running and strength sessions.
            </p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
            {{-- Month & Year Direct Selector --}}
            <div class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-1 shadow-sm">
                <select wire:model.live="month" class="text-xs sm:text-sm font-medium bg-transparent border-none text-gray-800 dark:text-gray-200 focus:ring-0 py-1 pl-2 pr-6 cursor-pointer">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" class="dark:bg-gray-800">{{ $name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="year" class="text-xs sm:text-sm font-medium bg-transparent border-none text-gray-800 dark:text-gray-200 focus:ring-0 py-1 pl-2 pr-6 cursor-pointer">
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" class="dark:bg-gray-800">{{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Previous, Today, Next Buttons --}}
            <div class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                <button type="button"
                        wire:click="previousMonth"
                        aria-label="Previous month"
                        class="p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-l-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button type="button"
                        wire:click="goToToday"
                        class="px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 border-x border-gray-200 dark:border-gray-700 transition-colors">
                    Today
                </button>

                <button type="button"
                        wire:click="nextMonth"
                        aria-label="Next month"
                        class="p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 rounded-r-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Monthly Metrics Summary Bar --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-3 sm:p-4 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-calendar-check text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Planned</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $monthSummary['total'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-3 sm:p-4 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-circle-check text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Completed</p>
                <p class="text-lg sm:text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ $monthSummary['completed'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-3 sm:p-4 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-cyan-50 dark:bg-cyan-950/40 text-cyan-600 dark:text-cyan-400 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-person-running text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Run Distance</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ rtrim(rtrim(number_format($monthSummary['running_km'], 1), '0'), '.') }} km</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-3 sm:p-4 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-dumbbell text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Strength Days</p>
                <p class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $monthSummary['strength_sessions'] }}</p>
            </div>
        </div>
    </div>

    {{-- Calendar Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        {{-- Weekday Headers (7 columns) --}}
        <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 text-center">
            @foreach($weekDays as $dayName)
                <div class="py-2.5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <span class="hidden sm:inline">{{ $dayName }}</span>
                    <span class="sm:hidden">{{ substr($dayName, 0, 1) }}</span>
                </div>
            @endforeach
        </div>

        {{-- Calendar Grid (7 columns) --}}
        <div class="grid grid-cols-7 border-l border-t border-gray-200 dark:border-gray-700">
            @foreach($calendarDays as $cell)
                @php
                    $isCellCurrentMonth = $cell['isCurrentMonth'];
                    $isCellToday = $cell['isToday'];
                    $isCellSelected = $cell['isSelected'];
                    $hasWorkouts = $cell['workouts']->isNotEmpty();
                @endphp

                <div wire:key="cal-day-{{ $cell['date'] }}"
                     wire:click="selectDate('{{ $cell['date'] }}')"
                     tabindex="0"
                     role="button"
                     aria-label="{{ $cell['date'] }} {{ $hasWorkouts ? '(' . $cell['workouts']->count() . ' activities)' : '(empty)' }}"
                     class="min-h-[90px] sm:min-h-[115px] md:min-h-[130px] p-1.5 sm:p-2 border-r border-b border-gray-200 dark:border-gray-700 flex flex-col justify-between transition-colors relative cursor-pointer select-none
                            {{ $isCellCurrentMonth ? 'bg-white dark:bg-gray-800 hover:bg-gray-50/70 dark:hover:bg-gray-700/40' : 'bg-gray-50/60 dark:bg-gray-900/40 text-gray-400 dark:text-gray-600 hover:bg-gray-100/60 dark:hover:bg-gray-800/60' }}
                            {{ $isCellSelected ? 'ring-2 ring-indigo-500 ring-inset bg-indigo-50/40 dark:bg-indigo-950/20' : '' }}">

                    {{-- Day Number Header --}}
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm font-semibold inline-flex items-center justify-center
                                     {{ $isCellToday ? 'w-6 h-6 rounded-full bg-indigo-600 text-white font-bold' : ($isCellCurrentMonth ? 'text-gray-800 dark:text-gray-200' : 'text-gray-400 dark:text-gray-500') }}">
                            {{ $cell['day'] }}
                        </span>

                        {{-- Multi-activity indicator on mobile or summary dots --}}
                        <div class="flex items-center gap-1 sm:hidden">
                            @if($cell['hasRunning'])
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500" title="Running"></span>
                            @endif
                            @if($cell['hasStrength'])
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500" title="Strength"></span>
                            @endif
                        </div>
                    </div>

                    {{-- Desktop Activities List (Visible on sm screens and up) --}}
                    <div class="mt-1 flex-1 space-y-1 overflow-hidden hidden sm:block">
                        @foreach($cell['workouts']->take(2) as $workout)
                            @php
                                $isRunning = $workout->type === 'running';
                                $isCompleted = $workout->status === 'completed';
                            @endphp
                            <div class="px-1.5 py-1 rounded text-xs leading-tight border truncate transition-all
                                        {{ $isRunning
                                            ? ($isCompleted
                                                ? 'bg-cyan-50 text-cyan-800 border-cyan-200 dark:bg-cyan-950/50 dark:text-cyan-300 dark:border-cyan-800'
                                                : 'bg-white text-cyan-700 border-dashed border-cyan-300 dark:bg-gray-800 dark:text-cyan-400 dark:border-cyan-700')
                                            : ($isCompleted
                                                ? 'bg-indigo-50 text-indigo-800 border-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800'
                                                : 'bg-white text-indigo-700 border-dashed border-indigo-300 dark:bg-gray-800 dark:text-indigo-400 dark:border-indigo-700') }}">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="truncate font-medium flex items-center gap-1">
                                        <i class="fa-solid {{ $isRunning ? 'fa-person-running text-[10px]' : 'fa-dumbbell text-[10px]' }}"></i>
                                        {{ $workout->title }}
                                    </span>
                                    @if($isCompleted)
                                        <i class="fa-solid fa-check text-[10px] text-emerald-600 dark:text-emerald-400 flex-shrink-0" title="Completed"></i>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 flex-shrink-0" title="Planned"></span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    {{ $workout->progress_summary }}
                                </div>
                            </div>
                        @endforeach

                        {{-- More indicator if > 2 activities --}}
                        @if($cell['workouts']->count() > 2)
                            <div class="text-[10px] font-medium text-gray-500 dark:text-gray-400 pl-1">
                                +{{ $cell['workouts']->count() - 2 }} more
                            </div>
                        @endif
                    </div>

                    {{-- Empty Date Affordance --}}
                    @if(!$hasWorkouts)
                        <div class="hidden sm:flex items-center justify-center opacity-0 hover:opacity-100 text-gray-400 dark:text-gray-500 text-xs py-1 transition-opacity">
                            <span class="text-[11px]">+ Add</span>
                        </div>
                    @else
                        {{-- Mobile quick indicator text --}}
                        <div class="sm:hidden mt-auto text-[10px] font-medium text-gray-600 dark:text-gray-400 truncate">
                            {{ $cell['workouts']->count() }} {{ \Illuminate\Support\Str::plural('act', $cell['workouts']->count()) }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Interactive Alpine.js Modal for Empty Dates & Activity Inspection --}}
    <div x-cloak
         x-show="showModal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-date-title"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">

        {{-- Backdrop --}}
        <div x-show="showModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showModal = false"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"></div>

        {{-- Dialog Panel --}}
        <div class="min-h-full flex items-center justify-center p-4">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.outside="showModal = false"
                 class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg shadow-xl overflow-hidden p-6">

                {{-- Header --}}
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h2 id="modal-date-title" class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $selectedDateFormatted ?: 'Selected Date' }}
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $selectedActivities->isEmpty() ? 'Schedule a new activity' : 'Manage activities for this date' }}
                        </p>
                    </div>

                    <button type="button"
                            @click="showModal = false"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="Close modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body: Check if date has activities or is empty --}}
                <div class="py-5">
                    @if($selectedActivities->isNotEmpty())
                        {{-- Date with Existing Activities --}}
                        <div class="mb-5">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                                Existing Activities ({{ $selectedActivities->count() }})
                            </h3>

                            <div class="space-y-3">
                                @foreach($selectedActivities as $activity)
                                    @php
                                        $isRun = $activity->type === 'running';
                                        $isDone = $activity->status === 'completed';
                                        $targetRoute = $isRun
                                            ? route('dashboard.workout.running', ['date' => $activity->date->format('Y-m-d')])
                                            : route('dashboard.workout.strength', ['date' => $activity->date->format('Y-m-d')]);
                                    @endphp
                                    <div class="p-3.5 rounded-xl border {{ $isRun ? 'border-cyan-200 bg-cyan-50/40 dark:border-cyan-800 dark:bg-cyan-950/20' : 'border-indigo-200 bg-indigo-50/40 dark:border-indigo-800 dark:bg-indigo-950/20' }} flex items-center justify-between gap-3">
                                        <div class="flex items-start gap-3 min-w-0">
                                            <div class="w-9 h-9 rounded-lg {{ $isRun ? 'bg-cyan-100 text-cyan-600 dark:bg-cyan-900/50 dark:text-cyan-400' : 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <i class="fa-solid {{ $isRun ? 'fa-person-running' : 'fa-dumbbell' }}"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                                        {{ $activity->title }}
                                                    </p>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                                                        {{ $isDone ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                                        {{ ucfirst($activity->status) }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-600 dark:text-gray-300 font-medium mt-0.5">
                                                    {{ $activity->progress_summary }}
                                                </p>
                                                @if($activity->notes)
                                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate mt-1">
                                                        "{{ $activity->notes }}"
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Open Existing Record Button (Prevents Duplication) --}}
                                        <a href="{{ $targetRoute }}"
                                           wire:navigate
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold {{ $isRun ? 'bg-cyan-600 hover:bg-cyan-700 text-white' : 'bg-indigo-600 hover:bg-indigo-700 text-white' }} shadow-xs transition-colors flex-shrink-0">
                                            <span>Open</span>
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Section to schedule an additional activity type --}}
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                                Schedule Another Activity
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @if(!$selectedActivities->contains(fn ($w) => $w->type === 'running'))
                                    <a href="{{ route('dashboard.workout.running', ['date' => $selectedDate]) }}"
                                       wire:navigate
                                       class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-cyan-500 dark:hover:border-cyan-400 hover:bg-cyan-50/30 dark:hover:bg-cyan-950/20 transition-all group">
                                        <div class="w-8 h-8 rounded-lg bg-cyan-100 dark:bg-cyan-900/50 text-cyan-600 dark:text-cyan-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                            <i class="fa-solid fa-person-running"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200 group-hover:text-cyan-600 dark:group-hover:text-cyan-400">Add Running</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400">Pace, distance, route</p>
                                        </div>
                                    </a>
                                @endif

                                @if(!$selectedActivities->contains(fn ($w) => $w->type === 'strength'))
                                    <a href="{{ route('dashboard.workout.strength', ['date' => $selectedDate]) }}"
                                       wire:navigate
                                       class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-950/20 transition-all group">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                            <i class="fa-solid fa-dumbbell"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">Add Strength</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400">Sets, reps, exercises</p>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>

                    @else
                        {{-- Empty Date Choice Modal --}}
                        <div class="text-center py-2">
                            <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 flex items-center justify-center mx-auto mb-3">
                                <i class="fa-regular fa-calendar-plus text-xl"></i>
                            </div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                No activity planned for this date
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs mx-auto">
                                Choose an activity type below to create your plan or record a workout:
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-5 text-left">
                                {{-- Option 1: Running --}}
                                <a href="{{ route('dashboard.workout.running', ['date' => $selectedDate]) }}"
                                   wire:navigate
                                   class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-cyan-500 dark:hover:border-cyan-400 hover:bg-cyan-50/40 dark:hover:bg-cyan-950/20 transition-all group block">
                                    <div class="w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/50 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-person-running text-lg"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400">
                                        Running
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-snug">
                                        Plan or log distance, pace, duration, and running terrain.
                                    </p>
                                </a>

                                {{-- Option 2: Strength Workout --}}
                                <a href="{{ route('dashboard.workout.strength', ['date' => $selectedDate]) }}"
                                   wire:navigate
                                   class="p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400 hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20 transition-all group block">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-dumbbell text-lg"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                        Strength Workout
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-snug">
                                        Plan or log weightlifting exercises, sets, reps, and volume.
                                    </p>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <button type="button"
                            @click="showModal = false"
                            class="px-4 py-2 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
