<div class="px-3 sm:px-6 lg:px-8 py-6 w-full max-w-7xl mx-auto dark:bg-gray-900 min-h-screen" x-data="{
        routeModalOpen: false,
        stationModalOpen: false,
        deleteSessionModalOpen: false,
        deleteRouteModalOpen: false,
        deleteStationModalOpen: false
     }" x-on:open-route-modal.window="routeModalOpen = true" x-on:open-station-modal.window="stationModalOpen = true"
    @keydown.escape.window="
        routeModalOpen = false;
        stationModalOpen = false;
        deleteSessionModalOpen = false;
        deleteRouteModalOpen = false;
        deleteStationModalOpen = false;
     ">

    {{-- Breadcrumb & Top Actions --}}
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.workout') }}" wire:navigate
                class="inline-flex items-center text-xs sm:text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Workout Calendar
            </a>
            <span class="text-gray-400 dark:text-gray-600">/</span>
            <span class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300">Running Plan & Interactive
                Map</span>
        </div>

        @if($workoutId)
        <button type="button" @click="deleteSessionModalOpen = true"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:text-white dark:text-rose-400 hover:bg-rose-600 dark:hover:bg-rose-600 border border-rose-200 dark:border-rose-800 rounded-lg transition-colors">
            <i class="fa-regular fa-trash-can"></i>
            <span>Delete Session</span>
        </button>
        @endif
    </div>

    {{-- Page Header --}}
    <div
        class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-gray-200 dark:border-gray-800">
        <div>
            <div class="flex items-center gap-2.5">
                <div
                    class="w-9 h-9 rounded-lg bg-cyan-100 text-cyan-600 dark:bg-cyan-900/40 dark:text-cyan-400 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-person-running"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">
                    {{ $workoutId ? 'Running Plan, Routes & Water Stations' : 'Schedule Running Session' }}
                </h1>
            </div>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                Target Date: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $formattedDate }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold
                {{ $status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : ($status === 'skipped' ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300') }}">
                <span
                    class="w-2 h-2 rounded-full {{ $status === 'completed' ? 'bg-emerald-500' : ($status === 'skipped' ? 'bg-gray-500' : 'bg-amber-500') }}"></span>
                {{ ucfirst($status) }}
            </span>
        </div>
    </div>

    {{-- LEAFLET INTERACTIVE MAP SECTION --}}
    <div wire:poll.5s class="mb-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden"
        wire:ignore x-data="runningMapManager(@js($mapData))" x-init="init()">

        {{-- Map Controls Toolbar --}}
        <div
            class="p-3.5 sm:p-4 bg-gray-50/80 dark:bg-gray-800/90 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white">Interactive Leaflet Map</h2>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Click on map to draw routes or pinpoint
                        water stations</p>
                </div>
            </div>

            {{-- Map Action Modes --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                {{-- View Mode Button --}}
                <button type="button" @click="setMode('view')"
                    :class="mode === 'view' ? 'bg-indigo-600 text-white font-bold' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600'"
                    class="px-3 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-600 shadow-xs transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-eye text-xs"></i>
                    <span>View Map</span>
                </button>

                {{-- Draw Route Mode Button --}}
                <button type="button" @click="setMode('draw_route')"
                    :class="mode === 'draw_route' ? 'bg-cyan-600 text-white font-bold ring-2 ring-cyan-300' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600'"
                    class="px-3 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-600 shadow-xs transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-draw-polygon text-xs"></i>
                    <span>Draw Route</span>
                </button>

                {{-- Add Water Station Mode Button --}}
                <button type="button" @click="setMode('add_station')"
                    :class="mode === 'add_station' ? 'bg-emerald-600 text-white font-bold ring-2 ring-emerald-300' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600'"
                    class="px-3 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-600 shadow-xs transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-droplet text-xs"></i>
                    <span>Pick Drink Spot</span>
                </button>

                {{-- Fit Bounds Button --}}
                <button type="button" @click="fitBounds()" title="Fit all routes & stations in view"
                    class="px-2.5 py-1.5 text-xs bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-600 transition-colors">
                    <i class="fa-solid fa-arrows-to-dot"></i>
                </button>
            </div>
        </div>

        {{-- Interactive Active Mode Hint / Drawing Status Bar --}}
        <div x-show="mode !== 'view'" x-transition
            class="px-4 py-2 text-xs border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-2"
            :class="mode === 'draw_route' ? 'bg-cyan-50 dark:bg-cyan-950/40 text-cyan-900 dark:text-cyan-200' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-200'">

            <div class="flex items-center gap-2">
                <template x-if="mode === 'draw_route'">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-pen text-cyan-600"></i>
                        <span>Click map to add waypoints. Distance: <strong class="text-sm font-bold"
                                x-text="drawnDistanceKm + ' km'"></strong> (<span x-text="tempPoints.length"></span>
                            points)</span>
                    </span>
                </template>

                <template x-if="mode === 'add_station'">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-emerald-600"></i>
                        <span>Click anywhere on the map along your route to place a water station pin.</span>
                    </span>
                </template>
            </div>

            {{-- Drawing Action Buttons --}}
            <template x-if="mode === 'draw_route'">
                <div class="flex items-center gap-2">
                    <button type="button" @click="undoLastPoint()" :disabled="tempPoints.length === 0"
                        class="px-2.5 py-1 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded border border-gray-300 dark:border-gray-600 text-xs disabled:opacity-40">
                        Undo Point
                    </button>
                    <button type="button" @click="clearDrawnPath()" :disabled="tempPoints.length === 0"
                        class="px-2.5 py-1 bg-white dark:bg-gray-800 text-rose-600 dark:text-rose-400 rounded border border-gray-300 dark:border-gray-600 text-xs disabled:opacity-40">
                        Clear
                    </button>
                    <button type="button" @click="saveDrawnPathAsRoute()" :disabled="tempPoints.length < 2"
                        class="px-3 py-1 bg-cyan-600 hover:bg-cyan-700 text-white rounded text-xs font-semibold shadow-xs disabled:opacity-40">
                        Use as New Route
                    </button>
                </div>
            </template>
        </div>

        {{-- Map Container Div --}}
        <div id="running-leaflet-map" class="w-full h-[400px] sm:h-[480px] bg-gray-100 dark:bg-gray-900 relative"></div>
    </div>

    {{-- Main Content Grid: Session Details & Routes --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: Running Session Details Form --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                <h2
                    class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
                    Session Details
                </h2>

                <form wire:submit.prevent="saveSession" class="space-y-4">
                    {{-- Title --}}
                    <div>
                        <label for="session-title"
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Session Title <span class="text-rose-500">*</span>
                        </label>
                        <input id="session-title" type="text" wire:model="title" placeholder="e.g. Morning 10K Long Run"
                            class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:focus:ring-cyan-500">
                        @error('title') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="session-status"
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Session Status <span class="text-rose-500">*</span>
                        </label>
                        <select id="session-status" wire:model.live="status"
                            class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:focus:ring-cyan-500">
                            <option value="planned">Planned (Upcoming)</option>
                            <option value="completed">Completed</option>
                            <option value="skipped">Skipped</option>
                        </select>
                        @error('status') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Planned Distance --}}
                    <div>
                        <label for="planned-dist"
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Planned Distance (km)
                        </label>
                        <div class="relative">
                            <input id="planned-dist" type="number" step="0.01" wire:model="planned_distance_km"
                                placeholder="e.g. 5.0"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 pr-12 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:focus:ring-cyan-500">
                            <span
                                class="absolute inset-y-0 right-3 flex items-center text-xs font-medium text-gray-400">km</span>
                        </div>
                        @error('planned_distance_km') <span
                            class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Actual Results (Highlighted when Completed) --}}
                    <div
                        class="p-3.5 rounded-xl border {{ $status === 'completed' ? 'border-emerald-200 bg-emerald-50/40 dark:border-emerald-800 dark:bg-emerald-950/20' : 'border-gray-200 bg-gray-50/50 dark:border-gray-700 dark:bg-gray-800/50' }} space-y-3">
                        <div class="flex items-center justify-between">
                            <span
                                class="text-xs font-bold uppercase tracking-wider {{ $status === 'completed' ? 'text-emerald-800 dark:text-emerald-300' : 'text-gray-500 dark:text-gray-400' }}">
                                Actual Recorded Results
                            </span>
                            @if($status !== 'completed')
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 italic">Fill when completed</span>
                            @endif
                        </div>

                        {{-- Actual Distance --}}
                        <div>
                            <label for="actual-dist"
                                class="block text-[11px] font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Actual Distance (km)
                            </label>
                            <div class="relative">
                                <input id="actual-dist" type="number" step="0.01"
                                    wire:model.live.debounce.300ms="actual_distance_km" placeholder="e.g. 5.25"
                                    class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2 pr-12 focus:ring-2 focus:ring-emerald-500">
                                <span
                                    class="absolute inset-y-0 right-3 flex items-center text-xs font-medium text-gray-400">km</span>
                            </div>
                            @error('actual_distance_km') <span
                                class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Duration Minutes --}}
                        <div>
                            <label for="actual-duration"
                                class="block text-[11px] font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Duration (minutes)
                            </label>
                            <div class="relative">
                                <input id="actual-duration" type="number"
                                    wire:model.live.debounce.300ms="duration_minutes" placeholder="e.g. 28"
                                    class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2 pr-12 focus:ring-2 focus:ring-emerald-500">
                                <span
                                    class="absolute inset-y-0 right-3 flex items-center text-xs font-medium text-gray-400">min</span>
                            </div>
                            @error('duration_minutes') <span
                                class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Calculated Pace --}}
                        <div>
                            <label for="actual-pace"
                                class="block text-[11px] font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Average Pace (min/km)
                            </label>
                            <input id="actual-pace" type="text" wire:model="pace" placeholder="e.g. 5:20"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2 focus:ring-2 focus:ring-emerald-500">
                            @error('pace') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="session-notes"
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Session Notes
                        </label>
                        <textarea id="session-notes" wire:model="notes" rows="2"
                            placeholder="Weather conditions, shoes worn, how you felt..."
                            class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-cyan-500"></textarea>
                        @error('notes') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Save Button --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg text-sm font-semibold shadow-sm transition-colors focus:ring-2 focus:ring-cyan-500">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>{{ $workoutId ? 'Update Session' : 'Save & Continue' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- RIGHT COLUMN: Routes & Drink Spot Management List --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Routes Header Bar --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                                Planned Routes
                            </h2>
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                {{ $routes->count() }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Total Planned Distance: <span
                                class="font-bold text-gray-800 dark:text-gray-200">{{ number_format($totalRouteDistance, 2) }}
                                km</span>
                            · Water Stations: <span
                                class="font-bold text-gray-800 dark:text-gray-200">{{ $totalStationsCount }}</span>
                        </p>
                    </div>

                </div>
            </div>

            {{-- Routes List --}}
            @if($routes->isEmpty())
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-8 text-center">
                <div
                    class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-route text-xl"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">No routes added for this session</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                    You can manually draw them directly on the Leaflet map above!
                </p>

            </div>
            @else
            <div class="space-y-4">
                @foreach($routes as $route)
                <div wire:key="route-card-{{ $route->id }}"
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

                    {{-- Route Header Banner --}}
                    <div
                        class="p-4 bg-gray-50/70 dark:bg-gray-800/90 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Order Badge & Up/Down reordering --}}
                            <div class="flex items-center gap-1">
                                <span
                                    class="w-6 h-6 rounded-md bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 text-xs font-bold flex items-center justify-center">
                                    #{{ $route->order }}
                                </span>
                                <div class="flex flex-col">
                                    <button type="button" wire:click="moveRoute({{ $route->id }}, 'up')"
                                        aria-label="Move route up"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-[10px] leading-none px-1">
                                        ▲
                                    </button>
                                    <button type="button" wire:click="moveRoute({{ $route->id }}, 'down')"
                                        aria-label="Move route down"
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-[10px] leading-none px-1">
                                        ▼
                                    </button>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                    {{ $route->name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                    <span
                                        class="font-semibold text-gray-700 dark:text-gray-300">{{ number_format($route->planned_distance_km, 2) }}
                                        km</span>
                                    <span>·</span>
                                    <span>{{ $route->terrain_type ?? 'Road' }}</span>
                                    @if($route->elevation_gain_m)
                                    <span>·</span>
                                    <span>+{{ $route->elevation_gain_m }}m elev</span>
                                    @endif
                                    @if(!empty($route->path_coordinates))
                                    <span
                                        class="inline-flex items-center text-[10px] font-medium text-cyan-600 dark:text-cyan-400">
                                        <i class="fa-solid fa-bezier-curve mr-1"></i> Mapped
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Route Action Buttons --}}
                        <div class="flex items-center gap-1.5 self-end sm:self-auto">
                            <button type="button" wire:click="openRouteModal({{ $route->id }})" aria-label="Edit route"
                                class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>

                            <button type="button" wire:click="deleteRoute({{ $route->id }})"
                                wire:confirm="Yakin ingin menghapus rute ini beserta water station di dalamnya?"
                                class="px-2.5 py-1 text-xs text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg transition-colors">
                                <i class="fa-solid fa-trash-can mr-1"></i> Delete
                            </button>
                        </div>
                    </div>

                    {{-- Route Description & Notes --}}
                    @if($route->description || $route->notes)
                    <div
                        class="px-4 py-2.5 bg-white dark:bg-gray-800 text-xs text-gray-600 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                        @if($route->description)
                        <p>{{ $route->description }}</p>
                        @endif
                        @if($route->notes)
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 italic">Note: {{ $route->notes }}
                        </p>
                        @endif
                    </div>
                    @endif

                    {{-- Water Stations Section on this Route --}}
                    <div class="p-4 bg-white dark:bg-gray-800">
                        <div class="flex items-center justify-between mb-3">
                            <span
                                class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-droplet text-cyan-500"></i>
                                <span>Water Stations & Drink Spots ({{ $route->waterStations->count() }})</span>
                            </span>
                        </div>

                        @if($route->waterStations->isEmpty())
                        <p class="text-xs text-gray-400 dark:text-gray-500 italic py-2">
                            No water fountains or drink spots mapped along this route yet.
                        </p>
                        @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                            @foreach($route->waterStations as $station)
                            <div wire:key="station-item-{{ $station->id }}"
                                class="p-3 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 flex items-start justify-between gap-2.5 hover:border-cyan-300 dark:hover:border-cyan-700 transition-colors">
                                <div class="flex items-start gap-2.5 min-w-0">
                                    {{-- Icon --}}
                                    <div
                                        class="w-7 h-7 rounded-md bg-cyan-100 text-cyan-700 dark:bg-cyan-900/50 dark:text-cyan-300 flex items-center justify-center text-xs flex-shrink-0 mt-0.5">
                                        <i class="fa-solid {{ $station->type_icon }}"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-gray-900 dark:text-white truncate">
                                            {{ $station->name }}
                                        </p>
                                        <div
                                            class="flex items-center gap-1.5 text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">
                                            <span
                                                class="font-medium text-cyan-600 dark:text-cyan-400">{{ $station->type_label }}</span>
                                            <span>·</span>
                                            <span title="GPS Coordinates">{{ number_format($station->latitude, 4) }},
                                                {{ number_format($station->longitude, 4) }}</span>
                                        </div>
                                        @if($station->notes)
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate mt-0.5">
                                            "{{ $station->notes }}"
                                        </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Station Actions --}}
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <button type="button"
                                        wire:click="openStationModal({{ $route->id }}, {{ $station->id }})"
                                        aria-label="Edit station"
                                        class="p-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded">
                                        <i class="fa-solid fa-pencil text-[10px]"></i>
                                    </button>
                                    <button type="button" wire:click="deleteStation({{ $station->id }})"
                                        aria-label="Delete station"
                                        class="p-1 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 rounded">
                                        <i class="fa-regular fa-trash-can text-[10px]"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- MODAL 1: Route Create/Edit Modal --}}
    <div x-cloak x-show="routeModalOpen" role="dialog" aria-modal="true" class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="routeModalOpen = false"></div>

        <div class="min-h-full flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg shadow-xl p-6"
                @click.outside="routeModalOpen = false">

                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 mb-4">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">
                        {{ $editingRouteId ? 'Edit Running Route' : 'Add New Running Route' }}
                    </h3>
                    <button type="button" @click="routeModalOpen = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveRoute" class="space-y-3.5">
                    {{-- Route Name --}}
                    <div>
                        <label for="route-name-input"
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Route Name <span class="text-rose-500">*</span>
                        </label>
                        <input id="route-name-input" type="text" wire:model="routeName"
                            placeholder="e.g. River Parkway Loop"
                            class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('routeName') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Distance & Order --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="route-dist-input"
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Planned Distance (km) <span class="text-rose-500">*</span>
                            </label>
                            <input id="route-dist-input" type="number" step="0.01" wire:model="routePlannedDistance"
                                placeholder="e.g. 5.5"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-indigo-500">
                            @error('routePlannedDistance') <span
                                class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="route-order-input"
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Order Sequence <span class="text-rose-500">*</span>
                            </label>
                            <input id="route-order-input" type="number" wire:model="routeOrder" min="1"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-indigo-500">
                            @error('routeOrder') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Terrain & Elevation --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="route-terrain-select"
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Terrain Type
                            </label>
                            <select id="route-terrain-select" wire:model="routeTerrainType"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-indigo-500">
                                <option value="Road">Road (Pavement)</option>
                                <option value="Trail">Trail (Dirt/Gravel)</option>
                                <option value="Track">Athletic Track</option>
                                <option value="Treadmill">Treadmill</option>
                                <option value="Mixed">Mixed Terrain</option>
                            </select>
                        </div>

                        <div>
                            <label for="route-elevation-input"
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Elevation Gain (m)
                            </label>
                            <input id="route-elevation-input" type="number" wire:model="routeElevationGain"
                                placeholder="e.g. 75"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="route-desc-input"
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Description
                        </label>
                        <textarea id="route-desc-input" wire:model="routeDescription" rows="2"
                            placeholder="Turn-by-turn or landmark directions..."
                            class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="routeModalOpen = false"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm">
                            Save Route
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL 2: Water Station Create/Edit Modal --}}
    <div x-cloak x-show="stationModalOpen" role="dialog" aria-modal="true" class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="stationModalOpen = false"></div>

        <div class="min-h-full flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg shadow-xl p-6"
                @click.outside="stationModalOpen = false">

                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 mb-4">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">
                        {{ $editingStationId ? 'Edit Water Station' : 'Add Water Station / Drink Spot' }}
                    </h3>
                    <button type="button" @click="stationModalOpen = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveStation" class="space-y-3.5">
                    {{-- Station Name --}}
                    <div>
                        <label for="station-name-input"
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Location / Spot Name <span class="text-rose-500">*</span>
                        </label>
                        <input id="station-name-input" type="text" wire:model="stationName"
                            placeholder="e.g. Park Pavilion Water Fountain"
                            class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-cyan-500">
                        @error('stationName') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Type & Order --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="station-type-select"
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Spot Type <span class="text-rose-500">*</span>
                            </label>
                            <select id="station-type-select" wire:model="stationType"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-cyan-500">
                                <option value="water_fountain">Water Fountain</option>
                                <option value="convenience_store">Convenience Store</option>
                                <option value="vending_machine">Vending Machine</option>
                                <option value="aid_station">Aid Station</option>
                                <option value="hydration_point">Hydration Point</option>
                            </select>
                            @error('stationType') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="station-order-input"
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Order along Route <span class="text-rose-500">*</span>
                            </label>
                            <input id="station-order-input" type="number" wire:model="stationOrder" min="1"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-cyan-500">
                            @error('stationOrder') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Latitude & Longitude --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="station-lat-input"
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Latitude (-90 to 90) <span class="text-rose-500">*</span>
                            </label>
                            <input id="station-lat-input" type="number" step="any" wire:model="stationLatitude"
                                placeholder="-6.2087634"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-cyan-500">
                            @error('stationLatitude') <span
                                class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="station-lng-input"
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                                Longitude (-180 to 180) <span class="text-rose-500">*</span>
                            </label>
                            <input id="station-lng-input" type="number" step="any" wire:model="stationLongitude"
                                placeholder="106.8455990"
                                class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-cyan-500">
                            @error('stationLongitude') <span
                                class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="station-notes-input"
                            class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Notes
                        </label>
                        <input id="station-notes-input" type="text" wire:model="stationNotes"
                            placeholder="e.g. Free cold refill, cash only, open 24/7"
                            class="w-full text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white p-2.5 focus:ring-2 focus:ring-cyan-500">
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="stationModalOpen = false"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-xs font-semibold bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg shadow-sm">
                            Save Spot
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL 3: Delete Session Confirmation Modal --}}
    <div x-cloak x-show="deleteSessionModalOpen" role="dialog" aria-modal="true"
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="deleteSessionModalOpen = false"></div>

        <div class="min-h-full flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 w-full max-w-md shadow-xl p-6"
                @click.outside="deleteSessionModalOpen = false">

                <div
                    class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400 flex items-center justify-center mx-auto mb-4 text-xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <h3 class="text-base font-bold text-gray-900 dark:text-white text-center">
                    Delete Running Session?
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1.5 leading-relaxed">
                    This will permanently remove this running session, its planned routes, and all mapped water
                    stations. This action cannot be undone.
                </p>

                <div class="flex items-center justify-center gap-3 mt-6">
                    <button type="button" @click="deleteSessionModalOpen = false"
                        class="px-4 py-2 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteSession" @click="deleteSessionModalOpen = false"
                        class="px-4 py-2 text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white rounded-lg shadow-sm">
                        Yes, Delete Session
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL 4: Delete Route Confirmation Modal --}}
    <div x-cloak x-show="routeIdToDelete !== null" role="dialog" aria-modal="true"
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs" @click="routeIdToDelete = null"></div>

        <div class="min-h-full flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 w-full max-w-sm shadow-xl p-5"
                @click.outside="routeIdToDelete = null">

                <h3 class="text-sm font-bold text-gray-900 dark:text-white text-center">
                    Delete Route?
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1">
                    All associated water stations on this route will also be removed.
                </p>

                <div class="flex items-center justify-center gap-3 mt-4">
                    <button type="button" @click="routeIdToDelete = null"
                        class="px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Cancel
                    </button>
                    <button type="button" wire:click="deleteRoute(routeIdToDelete)"
                        class="px-3 py-1.5 text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white rounded-lg shadow-sm">
                        Delete Route
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-pane {
        z-index: 10 !important;
    }

    .leaflet-bottom,
    .leaflet-top {
        z-index: 20 !important;
    }

    .station-marker-pin {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        background-color: #06b6d4;
        color: #ffffff;
        width: 28px;
        height: 28px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.25);
        border: 2px solid #ffffff;
        font-size: 12px;
    }

    .endpoint-marker-pin {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        width: 24px;
        height: 24px;
        color: #ffffff;
        font-size: 11px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        border: 2px solid #ffffff;
    }

</style>
@endpush


@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>



<script>
    function runningMapManager(initialMapData) {

        return {

            map: null,

            mode: 'view', // 'view', 'draw_route', 'add_station'

            routeData: initialMapData.route || null,

            tempPoints: [],

            tempPolyline: null,

            tempPointMarkers: [],

            routeLayers: [],

            stationMarkers: [],

            drawnDistanceKm: '0.00',



            init() {

                this.loadLeafletThen(() => {

                    this.initMap();

                });
                // 🔴 Tangkap Notifikasi Global dengan SweetAlert2 (Mode Toast)
                    Livewire.on('notify', (data) => {
                        const payload = Array.isArray(data) ? data[0] : data;
                        const type = payload.type || 'info'; // success, error, warning, info
                        const message = payload.message || '';
                        
                        // Konfigurasi SweetAlert sebagai Toast
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });

                        Toast.fire({
                            icon: type,
                            title: message
                        });
                    });


                // 🔴 Dengarkan event dari Livewire saat tombol edit diklik

                Livewire.on('focus-route-on-map', ({
                    route
                }) => {

                    this.routeData = route;

                    this.renderRouteAndStations();

                    this.fitBounds();



                    // Scroll halus ke elemen peta jika posisi tombol jauh di bawah

                    const mapEl = document.getElementById('running-leaflet-map');

                    if (mapEl) {

                        mapEl.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                    }

                });

            },



            loadLeafletThen(callback) {

                if (window.L) {

                    callback();

                    return;

                }

                const checkInterval = setInterval(() => {

                    if (window.L) {

                        clearInterval(checkInterval);

                        callback();

                    }

                }, 50);

            },



            initMap() {

                const mapEl = document.getElementById('running-leaflet-map');

                if (!mapEl || this.map) return;



                // Center default: Kota Palu, Sulawesi Tengah

                let center = [-0.8917, 119.8707];

                let zoom = 13;



                // Jika sudah ada rute tersimpan, fokus ke titik awal rute

                if (this.routeData && this.routeData.path && this.routeData.path.length > 0) {

                    center = this.routeData.path[0];

                    zoom = 14;

                } else if (this.routeData && this.routeData.stations && this.routeData.stations.length > 0) {

                    center = [this.routeData.stations[0].lat, this.routeData.stations[0].lng];

                    zoom = 14;

                }



                this.map = L.map(mapEl).setView(center, zoom);



                // Tile OpenStreetMap

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {

                    maxZoom: 19,

                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'

                }).addTo(this.map);




                // Render rute & water station yang ada di DB

                this.renderRouteAndStations();



                // Map Click Listener

                this.map.on('click', (e) => {

                    this.handleMapClick(e);

                });



                // Auto-fit bounds jika rute/station sudah ada

                this.fitBounds();



                // Listen Livewire navigation update

                window.addEventListener('livewire:navigated', () => {

                    setTimeout(() => this.map && this.map.invalidateSize(), 200);

                });

            },



            setMode(newMode) {

                this.mode = newMode;

                const mapEl = document.getElementById('running-leaflet-map');



                if (newMode === 'draw_route') {

                    mapEl.style.cursor = 'crosshair';

                } else if (newMode === 'add_station') {

                    mapEl.style.cursor = 'cell';

                } else {

                    mapEl.style.cursor = '';

                }

            },



            handleMapClick(e) {

                const lat = parseFloat(e.latlng.lat.toFixed(7));

                const lng = parseFloat(e.latlng.lng.toFixed(7));



                if (this.mode === 'draw_route') {

                    this.tempPoints.push([lat, lng]);

                    this.updateTempPolyline();

                } else if (this.mode === 'add_station') {

                    if (!this.routeData || !this.routeData.id) {

                        alert('Silakan buat rute terlebih dahulu sebelum menambah water station.');

                        this.setMode('view');

                        return;

                    }



                    // Buka modal station via Livewire

                    this.$wire.openStationModal(this.routeData.id, null, lat, lng);

                    this.setMode('view');

                }

            },



            updateTempPolyline() {

                if (this.tempPolyline) {

                    this.map.removeLayer(this.tempPolyline);

                }

                this.tempPointMarkers.forEach(m => this.map.removeLayer(m));

                this.tempPointMarkers = [];



                if (this.tempPoints.length === 0) {

                    this.drawnDistanceKm = '0.00';

                    return;

                }



                // Garis putus-putus saat menggambar

                this.tempPolyline = L.polyline(this.tempPoints, {

                    color: '#06b6d4',

                    weight: 5,

                    dashArray: '8, 8',

                    opacity: 0.9

                }).addTo(this.map);



                // Dot marker pada setiap waypoint

                this.tempPoints.forEach((pt, idx) => {

                    const dot = L.circleMarker(pt, {

                        radius: 5,

                        color: idx === 0 ? '#10b981' : (idx === this.tempPoints.length - 1 ? '#ef4444' :
                            '#06b6d4'),

                        fillColor: '#ffffff',

                        fillOpacity: 1,

                        weight: 2

                    }).addTo(this.map);

                    this.tempPointMarkers.push(dot);

                });



                // Hitung total jarak sepanjang waypoint

                let meters = 0;

                for (let i = 0; i < this.tempPoints.length - 1; i++) {

                    const p1 = L.latLng(this.tempPoints[i]);

                    const p2 = L.latLng(this.tempPoints[i + 1]);

                    meters += p1.distanceTo(p2);

                }



                this.drawnDistanceKm = (meters / 1000).toFixed(2);

            },



            undoLastPoint() {

                this.tempPoints.pop();

                this.updateTempPolyline();

            },



            clearDrawnPath() {

                this.tempPoints = [];

                this.updateTempPolyline();

            },



            // AUTO-SAVE langsung ke Database via Livewire

            saveDrawnPathAsRoute() {

                if (this.tempPoints.length < 2) return;



                const name = 'Running Route';

                const distance = parseFloat(this.drawnDistanceKm);

                const coordinates = this.tempPoints;



                // Panggil method autoSaveRoute di Livewire Component

                this.$wire.autoSaveRoute(name, distance, coordinates).then(() => {

                    this.clearDrawnPath();

                    this.setMode('view');

                });

            },



            renderRouteAndStations() {

                // Clear existing layers

                this.routeLayers.forEach(l => this.map.removeLayer(l));

                this.stationMarkers.forEach(m => this.map.removeLayer(m));

                this.routeLayers = [];

                this.stationMarkers = [];



                if (!this.routeData) return;



                //Render Polyline Rute Utama
                if (this.routeData.path && this.routeData.path.length > 1) {

                    const line = L.polyline(this.routeData.path, {

                        color: '#06b6d4',

                        weight: 5,

                        opacity: 0.85

                    }).addTo(this.map);



                    line.bindPopup(`

<div class="text-xs">

<strong class="block text-sm font-bold">${this.routeData.name}</strong>

<span>Jarak: ${this.routeData.distance_km} km</span>

<div class="mt-2">

<button type="button"

onclick="if(confirm('Hapus rute ini?')) { Livewire.find('${this.$wire.__instance.id}').deleteRoute(${this.routeData.id}) }"

class="px-2 py-1 bg-rose-600 text-white text-[11px] font-semibold rounded shadow-xs">

Delete Route

</button>

</div>

</div>

`);

                    this.routeLayers.push(line);



                    // Marker Start (S) & End (E)

                    const startIcon = L.divIcon({

                        className: 'endpoint-marker-pin bg-emerald-500',

                        html: '<span class="text-white font-bold text-xs flex items-center justify-center h-full">S</span>',

                        iconSize: [22, 22],

                        iconAnchor: [11, 11]

                    });

                    const endIcon = L.divIcon({

                        className: 'endpoint-marker-pin bg-rose-500',

                        html: '<span class="text-white font-bold text-xs flex items-center justify-center h-full">E</span>',

                        iconSize: [22, 22],

                        iconAnchor: [11, 11]

                    });



                    const startMarker = L.marker(this.routeData.path[0], {
                        icon: startIcon
                    }).addTo(this.map).bindPopup('<strong>Start Point</strong>');

                    const endMarker = L.marker(this.routeData.path[this.routeData.path.length - 1], {
                        icon: endIcon
                    }).addTo(this.map).bindPopup('<strong>End Point</strong>');



                    this.routeLayers.push(startMarker);

                    this.routeLayers.push(endMarker);

                }



                // 2. Render Water Stations

                if (this.routeData.stations) {

                    this.routeData.stations.forEach(station => {

                        const stationIcon = L.divIcon({

                            className: 'station-marker-pin',

                            html: '<i class="fa-solid fa-droplet text-white"></i>',

                            iconSize: [28, 28],

                            iconAnchor: [14, 14]

                        });



                        const marker = L.marker([station.lat, station.lng], {
                            icon: stationIcon
                        }).addTo(this.map);

                        marker.bindPopup(`

<div class="text-xs">

<div class="font-bold text-gray-900">${station.name}</div>

<div class="text-[11px] text-cyan-600 font-semibold">${station.type_label || station.type}</div>

${station.notes ? `<div class="text-[10px] text-gray-600 italic mt-0.5">"${station.notes}"</div>` : ''}

</div>

`);

                        this.stationMarkers.push(marker);

                    });

                }

            },



            fitBounds() {

                if (!this.map || !this.routeData) return;



                const allLatLngs = [];



                if (this.routeData.path && this.routeData.path.length > 0) {

                    this.routeData.path.forEach(pt => allLatLngs.push(pt));

                }

                if (this.routeData.stations) {

                    this.routeData.stations.forEach(st => allLatLngs.push([st.lat, st.lng]));

                }



                if (allLatLngs.length > 0) {

                    this.map.fitBounds(L.latLngBounds(allLatLngs), {
                        padding: [40, 40]
                    });

                }

            }

        };

    }

</script>

@endpush



@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    function runningMapManager(initialMapData) {

        return {

            map: null,

            mode: 'view', // 'view', 'draw_route', 'add_station'

            routesData: initialMapData.routes || [],

            tempPoints: [],

            tempPolyline: null,

            tempPointMarkers: [],

            routeLayers: [],

            stationMarkers: [],

            drawnDistanceKm: '0.00',



            init() {

                this.loadLeafletThen(() => {

                    this.initMap();

                });

            },



            loadLeafletThen(callback) {

                if (window.L) {

                    callback();

                    return;

                }

                const checkInterval = setInterval(() => {

                    if (window.L) {

                        clearInterval(checkInterval);

                        callback();

                    }

                }, 50);

            },



            initMap() {

                const mapEl = document.getElementById('running-leaflet-map');

                if (!mapEl || this.map) return;



                // Center default: Indonesia / Jakarta or first station coordinate

                let center = [-6.2088, 106.8456];

                let zoom = 13;



                // Find first available coordinate among routes or stations

                const firstRoute = this.routesData.find(r => r.path && r.path.length > 0);

                if (firstRoute) {

                    center = firstRoute.path[0];

                    zoom = 14;

                } else {

                    const firstStationRoute = this.routesData.find(r => r.stations && r.stations.length > 0);

                    if (firstStationRoute) {

                        center = [firstStationRoute.stations[0].lat, firstStationRoute.stations[0].lng];

                        zoom = 14;

                    }

                }



                this.map = L.map(mapEl).setView(center, zoom);



                // Add OpenStreetMap tiles

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {

                    maxZoom: 19,

                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'

                }).addTo(this.map);



                // Render existing routes and water stations

                this.renderAllRoutesAndStations();



                // Map Click Listener

                this.map.on('click', (e) => {

                    this.handleMapClick(e);

                });



                // Auto-fit if data exists

                this.fitBounds();



                // Listen for Livewire updates

                window.addEventListener('livewire:navigated', () => {

                    setTimeout(() => this.map && this.map.invalidateSize(), 200);

                });

            },



            setMode(newMode) {

                this.mode = newMode;

                const mapEl = document.getElementById('running-leaflet-map');



                if (newMode === 'draw_route') {

                    mapEl.style.cursor = 'crosshair';

                } else if (newMode === 'add_station') {

                    mapEl.style.cursor = 'cell';

                } else {

                    mapEl.style.cursor = '';

                }

            },



            handleMapClick(e) {

                const lat = parseFloat(e.latlng.lat.toFixed(7));

                const lng = parseFloat(e.latlng.lng.toFixed(7));



                if (this.mode === 'draw_route') {

                    this.tempPoints.push([lat, lng]);

                    this.updateTempPolyline();

                } else if (this.mode === 'add_station') {

                    // Place visual preview marker

                    const tempIcon = L.divIcon({

                        className: 'station-marker-pin',

                        html: '<i class="fa-solid fa-droplet text-white"></i>',

                        iconSize: [28, 28],

                        iconAnchor: [14, 14]

                    });



                    L.marker([lat, lng], {
                            icon: tempIcon
                        }).addTo(this.map)

                        .bindPopup(`<strong>Dropped Drink Spot</strong><br>Lat: ${lat}, Lng: ${lng}`).openPopup();



                    // If at least one route exists, open modal with selected route

                    const firstRoute = this.routesData[0];

                    if (firstRoute) {

                        @this.openStationModal(firstRoute.id, null, lat, lng);

                    } else {

                        alert('Please add a route first before adding water stations.');

                    }



                    this.setMode('view');

                }

            },



            updateTempPolyline() {

                if (this.tempPolyline) {

                    this.map.removeLayer(this.tempPolyline);

                }

                this.tempPointMarkers.forEach(m => this.map.removeLayer(m));

                this.tempPointMarkers = [];



                if (this.tempPoints.length === 0) {

                    this.drawnDistanceKm = '0.00';

                    return;

                }



                // Draw connecting line

                this.tempPolyline = L.polyline(this.tempPoints, {

                    color: '#06b6d4',

                    weight: 5,

                    dashArray: '8, 8',

                    opacity: 0.9

                }).addTo(this.map);



                // Add small dot markers on waypoints

                this.tempPoints.forEach((pt, idx) => {

                    const dot = L.circleMarker(pt, {

                        radius: 5,

                        color: idx === 0 ? '#10b981' : (idx === this.tempPoints.length - 1 ? '#ef4444' :
                            '#06b6d4'),

                        fillColor: '#ffffff',

                        fillOpacity: 1,

                        weight: 2

                    }).addTo(this.map);

                    this.tempPointMarkers.push(dot);

                });



                // Calculate total distance along waypoints

                let meters = 0;

                for (let i = 0; i < this.tempPoints.length - 1; i++) {

                    const p1 = L.latLng(this.tempPoints[i]);

                    const p2 = L.latLng(this.tempPoints[i + 1]);

                    meters += p1.distanceTo(p2);

                }



                this.drawnDistanceKm = (meters / 1000).toFixed(2);

            },



            undoLastPoint() {

                this.tempPoints.pop();

                this.updateTempPolyline();

            },



            clearDrawnPath() {

                this.tempPoints = [];

                this.updateTempPolyline();

            },



            saveDrawnPathAsRoute() {

                if (this.tempPoints.length < 2) return;



                // Buat nama otomatis, contoh: "Route #1", "Route #2", dst.

                const routeCount = (this.mapData ? .routes ? .length || 0) + 1;

                const autoName = `Route #${routeCount}`;

                const distance = parseFloat(this.drawnDistanceKm);



                // Format koordinat ke [lat, lng]

                const coordinates = this.tempPoints.map(p => [p.lat, p.lng]);



                // Panggil method Livewire langsung

                this.$wire.autoSaveRoute(autoName, distance, coordinates).then(() => {

                    // Reset state drawing setelah auto save

                    this.clearDrawnPath();

                    this.setMode('view');

                });

            }



            renderAllRoutesAndStations() {

                // Clear existing layers

                this.routeLayers.forEach(l => this.map.removeLayer(l));

                this.stationMarkers.forEach(m => this.map.removeLayer(m));

                this.routeLayers = [];

                this.stationMarkers = [];



                const palette = ['#06b6d4', '#6366f1', '#10b981', '#f59e0b', '#ec4899'];



                this.routesData.forEach((route, idx) => {

                    const color = palette[idx % palette.length];



                    //Draw route polyline if waypoints exist
                    if (route.path && route.path.length > 1) {

                        const line = L.polyline(route.path, {

                            color: color,

                            weight: 5,

                            opacity: 0.85

                        }).addTo(this.map);



                        line.bindPopup(
                            `<strong>Route #${route.order}: ${route.name}</strong><br>Distance: ${route.distance_km} km<br>Terrain: ${route.terrain_type || 'Road'}`
                            );

                        this.routeLayers.push(line);



                        // Start & End markers

                        const startIcon = L.divIcon({

                            className: 'endpoint-marker-pin bg-emerald-500',

                            html: 'S',

                            iconSize: [22, 22],

                            iconAnchor: [11, 11]

                        });

                        const endIcon = L.divIcon({

                            className: 'endpoint-marker-pin bg-rose-500',

                            html: 'E',

                            iconSize: [22, 22],

                            iconAnchor: [11, 11]

                        });



                        const startMarker = L.marker(route.path[0], {
                            icon: startIcon
                        }).addTo(this.map).bindPopup(`<strong>Start of ${route.name}</strong>`);

                        const endMarker = L.marker(route.path[route.path.length - 1], {
                            icon: endIcon
                        }).addTo(this.map).bindPopup(`<strong>End of ${route.name}</strong>`);



                        this.routeLayers.push(startMarker);

                        this.routeLayers.push(endMarker);

                    }



                    // 2. Draw water stations on this route

                    if (route.stations) {

                        route.stations.forEach(station => {

                            const stationIcon = L.divIcon({

                                className: 'station-marker-pin',

                                html: '<i class="fa-solid fa-droplet text-white"></i>',

                                iconSize: [28, 28],

                                iconAnchor: [14, 14]

                            });



                            const marker = L.marker([station.lat, station.lng], {
                                icon: stationIcon
                            }).addTo(this.map);

                            marker.bindPopup(`

<div class="text-xs">

<div class="font-bold text-gray-900">${station.name}</div>

<div class="text-[11px] text-cyan-600 font-semibold">${station.type_label}</div>

<div class="text-[10px] text-gray-500 mt-1">Route #${route.order}: ${route.name} (Stop #${station.order})</div>

${station.notes ? `<div class="text-[10px] text-gray-600 italic mt-0.5">"${station.notes}"</div>` : ''}

<div class="mt-2 text-[10px] text-gray-400">Coords: ${station.lat.toFixed(5)}, ${station.lng.toFixed(5)}</div>

</div>

`);

                            this.stationMarkers.push(marker);

                        });

                    }

                });

            },



            fitBounds() {

                if (!this.map) return;



                const allLatLngs = [];



                this.routesData.forEach(route => {

                    if (route.path && route.path.length > 0) {

                        route.path.forEach(pt => allLatLngs.push(pt));

                    }

                    if (route.stations) {

                        route.stations.forEach(st => allLatLngs.push([st.lat, st.lng]));

                    }

                });



                if (allLatLngs.length > 0) {

                    this.map.fitBounds(L.latLngBounds(allLatLngs), {
                        padding: [40, 40]
                    });

                }

            }

        };

    }

</script>

@endpush
