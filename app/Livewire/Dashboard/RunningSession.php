<?php

namespace App\Livewire\Dashboard;

use App\Models\RunningRoute;
use App\Models\WaterStation;
use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Running Plan & Route Management')]
class RunningSession extends Component
{
    // Date & Session identification
    public string $date;

    public ?int $workoutId = null;

    // Running Session form properties
    public string $title = 'Running Session';

    public string $status = 'planned'; // planned, completed, skipped

    public ?float $planned_distance_km = null;

    public ?float $actual_distance_km = null;

    public ?int $duration_minutes = null;

    public ?string $pace = null;

    public ?string $notes = null;

    // Route Modal & form properties
    public bool $showRouteModal = false;

    public ?int $editingRouteId = null;

    public string $routeName = '';

    public string $routeDescription = '';

    public ?float $routePlannedDistance = null;

    public int $routeOrder = 1;

    public string $routeTerrainType = 'Road';

    public ?int $routeElevationGain = null;

    public string $routeNotes = '';

    public ?string $routePathCoordinates = '[]';

    // Water Station Modal & form properties
    public bool $showStationModal = false;

    public ?int $activeRouteIdForStation = null;

    public ?int $editingStationId = null;

    public string $stationName = '';

    public string $stationType = 'water_fountain';

    public ?float $stationLatitude = null;

    public ?float $stationLongitude = null;

    public string $stationNotes = '';

    public int $stationOrder = 1;

    // Delete Modals
    public bool $showDeleteSessionModal = false;

    public ?int $routeIdToDelete = null;

    public ?int $stationIdToDelete = null;

    public function mount(string $date): void
    {
        try {
            $parsed = Carbon::createFromFormat('Y-m-d', $date);
            $this->date = $parsed->format('Y-m-d');
        } catch (\Exception $e) {
            $this->redirectRoute('dashboard.workout');

            return;
        }

        $this->loadSession();
    }

    public function loadSession(): void
    {
        $workout = Workout::query()
            ->where('user_id', Auth::id())
            ->where('date', $this->date)
            ->where('type', 'running')
            ->first();

        if ($workout) {
            $this->workoutId = $workout->id;
            $this->title = $workout->title;
            $this->status = $workout->status;
            $this->planned_distance_km = $workout->planned_distance_km;
            $this->actual_distance_km = $workout->distance_km;
            $this->duration_minutes = $workout->duration_minutes;
            $this->pace = $workout->pace;
            $this->notes = $workout->notes;
        } else {
            $this->workoutId = null;
            $this->title = 'Running Session';
            $this->status = 'planned';
            $this->planned_distance_km = null;
            $this->actual_distance_km = null;
            $this->duration_minutes = null;
            $this->pace = null;
            $this->notes = null;
        }
    }

    // Auto-calculate pace when distance or duration changes
    public function updatedActualDistanceKm(): void
    {
        $this->calculatePace();
    }

    public function updatedDurationMinutes(): void
    {
        $this->calculatePace();
    }

    public function calculatePace(): void
    {
        if ($this->actual_distance_km && $this->actual_distance_km > 0 && $this->duration_minutes && $this->duration_minutes > 0) {
            $decimalPace = $this->duration_minutes / $this->actual_distance_km;
            $minutes = floor($decimalPace);
            $seconds = round(($decimalPace - $minutes) * 60);

            if ($seconds >= 60) {
                $minutes += 1;
                $seconds = 0;
            }

            $this->pace = sprintf('%d:%02d', $minutes, $seconds);
        }
    }

    // -------------------------------------------------------------
    // RUNNING SESSION CRUD
    // -------------------------------------------------------------

    public function saveSession(): void
    {
        $this->validate([
            'title' => 'required|string|max:120',
            'status' => 'required|in:planned,completed,skipped',
            'planned_distance_km' => 'nullable|numeric|min:0.01|max:999.99',
            'actual_distance_km' => 'nullable|numeric|min:0.01|max:999.99',
            'duration_minutes' => 'nullable|integer|min:1|max:1440',
            'pace' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () {
            $workout = Workout::updateOrCreate(
                [
                    'id' => $this->workoutId,
                    'user_id' => Auth::id(),
                ],
                [
                    'user_id' => Auth::id(),
                    'date' => $this->date,
                    'type' => 'running',
                    'title' => $this->title,
                    'status' => $this->status,
                    'planned_distance_km' => $this->planned_distance_km,
                    'distance_km' => $this->actual_distance_km,
                    'duration_minutes' => $this->duration_minutes,
                    'pace' => $this->pace,
                    'notes' => $this->notes,
                    'completed_at' => $this->status === 'completed' ? now() : null,
                ]
            );

            $this->workoutId = $workout->id;
        });

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Running session saved successfully.',
        ]);
    }

    public function deleteSession(): void
    {
        if ($this->workoutId) {
            $workout = Workout::query()
                ->where('id', $this->workoutId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            DB::transaction(function () use ($workout) {
                $workout->delete();
            });

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Running session deleted.',
            ]);

            $this->redirectRoute('dashboard.workout', navigate: true);
        }
    }

    // -------------------------------------------------------------
    // ROUTE CRUD
    // -------------------------------------------------------------

    public function openRouteModal(?int $routeId = null): void
    {
        if (! $this->workoutId) {
            $this->saveSession();
        }

        $this->resetValidation();

        if ($routeId) {
            $route = RunningRoute::query()
                ->where('id', $routeId)
                ->where('user_id', Auth::id())
                ->with(['waterStations' => fn ($q) => $q->orderBy('order')])
                ->firstOrFail();

            $this->editingRouteId = $route->id;
            $this->routeName = $route->name;
            $this->routeDescription = $route->description ?? '';
            $this->routePlannedDistance = $route->planned_distance_km;
            $this->routeOrder = $route->order;
            $this->routeTerrainType = $route->terrain_type ?? 'Road';
            $this->routeElevationGain = $route->elevation_gain_m;
            $this->routeNotes = $route->notes ?? '';
            $this->routePathCoordinates = json_encode($route->path_coordinates ?? []);

            // 🔴 Kirim event ke Alpine.js untuk fokus ke rute & water station terkait
            $this->dispatch('focus-route-on-map', route: [
                'id' => $route->id,
                'name' => $route->name,
                'distance_km' => $route->planned_distance_km,
                'terrain_type' => $route->terrain_type,
                'path' => $route->path_coordinates ?? [],
                'stations' => $route->waterStations->map(fn ($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'type' => $s->type,
                    'type_label' => $s->type_label ?? $s->type,
                    'lat' => (float) $s->latitude,
                    'lng' => (float) $s->longitude,
                    'notes' => $s->notes,
                ])->values()->all(),
            ]);
        } else {
                $maxOrder = RunningRoute::query()
                    ->where('workout_id', $this->workoutId)
                    ->where('user_id', Auth::id())
                    ->max('order') ?? 0;

                $this->editingRouteId = null;
                $this->routeName = '';
                $this->routeDescription = '';
                $this->routePlannedDistance = null;
                $this->routeOrder = $maxOrder + 1;
                $this->routeTerrainType = 'Road';
                $this->routeElevationGain = null;
                $this->routeNotes = '';
                $this->routePathCoordinates = '[]';
            }

            $this->showRouteModal = true;
            $this->dispatch('open-route-modal', ['path' => $this->routePathCoordinates]);

        $this->showRouteModal = true;
    }

    public function saveRoute(): void
    {
        $this->validate([
            'routeName' => 'required|string|max:120',
            'routeDescription' => 'nullable|string|max:1000',
            'routePlannedDistance' => 'required|numeric|min:0.01|max:999.99',
            'routeOrder' => 'required|integer|min:1|max:100',
            'routeTerrainType' => 'nullable|string|max:50',
            'routeElevationGain' => 'nullable|integer|min:0|max:10000',
            'routeNotes' => 'nullable|string|max:1000',
        ]);

        $workout = Workout::query()
            ->where('id', $this->workoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $parsedPath = null;
        if ($this->routePathCoordinates) {
            $decoded = is_string($this->routePathCoordinates) ? json_decode($this->routePathCoordinates, true) : $this->routePathCoordinates;
            if (is_array($decoded) && count($decoded) > 0) {
                $parsedPath = $decoded;
            }
        }

        DB::transaction(function () use ($workout, $parsedPath) {
            RunningRoute::updateOrCreate(
                [
                    'id' => $this->editingRouteId,
                    'user_id' => Auth::id(),
                ],
                [
                    'workout_id' => $workout->id,
                    'user_id' => Auth::id(),
                    'name' => $this->routeName,
                    'description' => $this->routeDescription ?: null,
                    'planned_distance_km' => $this->routePlannedDistance,
                    'path_coordinates' => $parsedPath,
                    'order' => $this->routeOrder,
                    'terrain_type' => $this->routeTerrainType ?: 'Road',
                    'elevation_gain_m' => $this->routeElevationGain,
                    'notes' => $this->routeNotes ?: null,
                ]
            );

            // If session planned distance is empty, auto-populate from total routes
            $totalRouteDistance = RunningRoute::where('workout_id', $workout->id)->sum('planned_distance_km');
            if (! $workout->planned_distance_km) {
                $workout->update(['planned_distance_km' => $totalRouteDistance]);
                $this->planned_distance_km = (float) $totalRouteDistance;
            }
        });

        $this->showRouteModal = false;
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $this->editingRouteId ? 'Route updated successfully.' : 'Route created successfully.',
        ]);
    }

    /**
     * Delete a route by ID.
     */
    public function deleteRoute(int $routeId): void
    {
        $route = RunningRoute::query()
            ->where('id', $routeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        DB::transaction(function () use ($route) {
            $route->delete();
        });

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Route successfully deleted.',
        ]);

        
        $this->redirectRoute('dashboard.workout.running', ['date' => now()->format('Y-m-d')], navigate: true);
    }

    public function moveRoute(int $routeId, string $direction): void
    {
        $route = RunningRoute::query()
            ->where('id', $routeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $targetOrder = $direction === 'up' ? $route->order - 1 : $route->order + 1;
        if ($targetOrder < 1) {
            return;
        }

        $neighbor = RunningRoute::query()
            ->where('workout_id', $route->workout_id)
            ->where('user_id', Auth::id())
            ->where('order', $targetOrder)
            ->first();

        DB::transaction(function () use ($route, $neighbor, $targetOrder) {
            if ($neighbor) {
                $neighbor->update(['order' => $route->order]);
            }
            $route->update(['order' => $targetOrder]);
        });
    }

    // -------------------------------------------------------------
    // WATER STATION / DRINK SPOT CRUD
    // -------------------------------------------------------------

    public function openStationModal(int $routeId, ?int $stationId = null, ?float $lat = null, ?float $lng = null): void
    {
        // Enforce route ownership
        $route = RunningRoute::query()
            ->where('id', $routeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->activeRouteIdForStation = $route->id;
        $this->resetValidation();

        if ($stationId) {
            $station = WaterStation::query()
                ->where('id', $stationId)
                ->where('user_id', Auth::id())
                ->where('running_route_id', $route->id)
                ->firstOrFail();

            $this->editingStationId = $station->id;
            $this->stationName = $station->name;
            $this->stationType = $station->type;
            $this->stationLatitude = $station->latitude;
            $this->stationLongitude = $station->longitude;
            $this->stationNotes = $station->notes ?? '';
            $this->stationOrder = $station->order;
        } else {
            $maxOrder = WaterStation::query()
                ->where('running_route_id', $route->id)
                ->max('order') ?? 0;

            $this->editingStationId = null;
            $this->stationName = '';
            $this->stationType = 'water_fountain';
            $this->stationLatitude = $lat;
            $this->stationLongitude = $lng;
            $this->stationNotes = '';
            $this->stationOrder = $maxOrder + 1;
        }

        $this->showStationModal = true;
        $this->dispatch('open-station-modal', [
            'lat' => $this->stationLatitude,
            'lng' => $this->stationLongitude,
        ]);
    }

public function saveStation(): void
    {
        $this->validate([
            'stationName' => 'required|string|max:120',
            'stationType' => 'required|string',
            'stationLatitude' => 'required|numeric|between:-90,90',
            'stationLongitude' => 'required|numeric|between:-180,180',
            'stationNotes' => 'nullable|string|max:1000',
            'stationOrder' => 'required|integer|min:1|max:100',
        ]);

        $route = RunningRoute::query()
            ->where('id', $this->activeRouteIdForStation)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        DB::transaction(function () use ($route) {
            WaterStation::updateOrCreate(
                [
                    'id' => $this->editingStationId,
                    'user_id' => Auth::id(),
                ],
                [
                    'running_route_id' => $route->id,
                    'user_id' => Auth::id(),
                    'name' => $this->stationName,
                    'type' => $this->stationType,
                    'latitude' => $this->stationLatitude,
                    'longitude' => $this->stationLongitude,
                    'notes' => $this->stationNotes ?: null,
                    'order' => $this->stationOrder,
                ]
            );
        });

        $this->showStationModal = false;
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $this->editingStationId ? 'Water station updated.' : 'Water station added.',
        ]);

        // 🔴 Ambil data rute terbaru beserta stasiunnya lalu lempar ke peta Alpine
        $route->load(['waterStations' => fn ($q) => $q->orderBy('order')]);
        $this->dispatchMapUpdate($route);
    }

    public function deleteStation(int $stationId): void
    {
        $station = WaterStation::query()
            ->where('id', $stationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $routeId = $station->running_route_id;

        DB::transaction(function () use ($station) {
            $station->delete();
        });

        $this->stationIdToDelete = null;
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Water station removed.',
        ]);

        // 🔴 Ambil data rute terbaru (yang stasiunnya sudah hilang) lalu lempar ke peta Alpine
        $route = RunningRoute::with(['waterStations' => fn ($q) => $q->orderBy('order')])->findOrFail($routeId);
        $this->dispatchMapUpdate($route);
    }

    /**
     * Helper khusus untuk melempar pembaruan rute ke Map Alpine.
     */
    private function dispatchMapUpdate(RunningRoute $route): void
    {
        $this->dispatch('focus-route-on-map', route: [
            'id' => $route->id,
            'name' => $route->name,
            'distance_km' => $route->planned_distance_km,
            'terrain_type' => $route->terrain_type,
            'path' => $route->path_coordinates ?? [],
            'stations' => $route->waterStations->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'type' => $s->type,
                'type_label' => $s->type_label ?? $s->type,
                'lat' => (float) $s->latitude,
                'lng' => (float) $s->longitude,
                'notes' => $s->notes,
            ])->values()->all(),
        ]);
    }

    public function moveStation(int $stationId, string $direction): void
    {
        $station = WaterStation::query()
            ->where('id', $stationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $targetOrder = $direction === 'up' ? $station->order - 1 : $station->order + 1;
        if ($targetOrder < 1) {
            return;
        }

        $neighbor = WaterStation::query()
            ->where('running_route_id', $station->running_route_id)
            ->where('order', $targetOrder)
            ->first();

        DB::transaction(function () use ($station, $neighbor, $targetOrder) {
            if ($neighbor) {
                $neighbor->update(['order' => $station->order]);
            }
            $station->update(['order' => $targetOrder]);
        });
    }

    /**
     * Auto-save route directly from map drawing.
     */
    public function autoSaveRoute(string $name, float $distance, array $coordinates): void
    {
        // 1. Pastikan Workout Session sudah tersimpan terlebih dahulu
        if (! $this->workoutId) {
            $this->saveSession();
        }

        $workout = Workout::query()
            ->where('id', $this->workoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // 2. Hitung urutan route tertinggi
        $maxOrder = RunningRoute::query()
            ->where('workout_id', $workout->id)
            ->where('user_id', Auth::id())
            ->max('order') ?? 0;

        // 3. Simpan Route baru ke Database
        DB::transaction(function () use ($workout, $name, $distance, $coordinates, $maxOrder) {
            RunningRoute::create([
                'workout_id' => $workout->id,
                'user_id' => Auth::id(),
                'name' => $name,
                'planned_distance_km' => $distance,
                'path_coordinates' => $coordinates,
                'order' => $maxOrder + 1,
                'terrain_type' => 'Road',
            ]);

            // Auto update planned distance session jika masih kosong
            $totalRouteDistance = RunningRoute::where('workout_id', $workout->id)->sum('planned_distance_km');
            if (! $workout->planned_distance_km) {
                $workout->update(['planned_distance_km' => $totalRouteDistance]);
                $this->planned_distance_km = (float) $totalRouteDistance;
            }
        });

        // 4. Notifikasi ke UI
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Route saved successfully!',
        ]);
    }

    public function render()
    {
        $routes = collect();

        if ($this->workoutId) {
            $routes = RunningRoute::query()
                ->where('workout_id', $this->workoutId)
                ->where('user_id', Auth::id())
                ->with(['waterStations' => fn ($q) => $q->orderBy('order')])
                ->orderBy('order')
                ->get();
        }

        $totalRouteDistance = $routes->sum('planned_distance_km');
        $totalStationsCount = $routes->sum(fn ($r) => $r->waterStations->count());

        $mapData = [
            'routes' => $routes->map(fn ($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'distance_km' => $r->planned_distance_km,
                'order' => $r->order,
                'terrain_type' => $r->terrain_type,
                'path' => $r->path_coordinates ?? [],
                'stations' => $r->waterStations->map(fn ($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'type' => $s->type,
                    'type_label' => $s->type_label,
                    'type_icon' => $s->type_icon,
                    'lat' => (float) $s->latitude,
                    'lng' => (float) $s->longitude,
                    'notes' => $s->notes,
                    'order' => $s->order,
                ])->values()->all(),
            ])->values()->all(),
        ];

        return view('livewire.dashboard.running-session', [
            'routes' => $routes,
            'totalRouteDistance' => $totalRouteDistance,
            'totalStationsCount' => $totalStationsCount,
            'formattedDate' => Carbon::parse($this->date)->translatedFormat('l, d F Y'),
            'mapData' => $mapData,
        ])->layout('layouts.dashboard.main');
    }
}
