<?php

use App\Livewire\Dashboard\RunningSession;
use App\Livewire\Dashboard\WorkoutPlanning;
use App\Models\RunningRoute;
use App\Models\User;
use App\Models\WaterStation;
use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
});

test('guest cannot access running session page', function () {
    $this->get(route('dashboard.workout.running', ['date' => '2026-10-22']))
        ->assertRedirect(route('login'));
});

test('authenticated user can view running session page for date', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.workout.running', ['date' => '2026-10-22']))
        ->assertOk()
        ->assertSee('Schedule Running Session')
        ->assertSee('22 October 2026');
});

test('can create a new running session', function () {
    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->set('title', 'Morning River Run')
        ->set('status', 'planned')
        ->set('planned_distance_km', 7.5)
        ->set('notes', 'Easy aerobic effort.')
        ->call('saveSession')
        ->assertDispatched('notify');

    $workout = Workout::where('user_id', $this->user->id)
        ->where('date', '2026-10-22')
        ->where('type', 'running')
        ->first();

    expect($workout)->not->toBeNull();
    expect($workout->title)->toBe('Morning River Run');
    expect($workout->status)->toBe('planned');
    expect($workout->planned_distance_km)->toBe(7.5);
    expect($workout->notes)->toBe('Easy aerobic effort.');
});

test('can view and update an existing running session', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
        'title' => 'Initial Run Title',
        'status' => 'planned',
        'planned_distance_km' => 5.0,
    ]);

    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->assertSet('title', 'Initial Run Title')
        ->assertSet('status', 'planned')
        ->assertSet('planned_distance_km', 5.0)
        ->set('title', 'Updated Run Title')
        ->set('status', 'completed')
        ->set('actual_distance_km', 5.2)
        ->set('duration_minutes', 26)
        ->call('saveSession')
        ->assertDispatched('notify');

    $workout->refresh();
    expect($workout->title)->toBe('Updated Run Title');
    expect($workout->status)->toBe('completed');
    expect($workout->distance_km)->toBe(5.2);
    expect($workout->duration_minutes)->toBe(26);
    expect($workout->pace)->toBe('5:00');
    expect($workout->completed_at)->not->toBeNull();
});

test('session validation enforces title and status', function () {
    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->set('title', '')
        ->set('status', 'invalid_status')
        ->call('saveSession')
        ->assertHasErrors(['title', 'status']);
});

test('can delete running session and cascades to child routes and stations', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    $route = RunningRoute::create([
        'workout_id' => $workout->id,
        'user_id' => $this->user->id,
        'name' => 'Route 1',
        'planned_distance_km' => 5.0,
        'order' => 1,
    ]);

    $station = WaterStation::create([
        'running_route_id' => $route->id,
        'user_id' => $this->user->id,
        'name' => 'Fountain 1',
        'type' => 'water_fountain',
        'latitude' => -6.2088,
        'longitude' => 106.8456,
        'order' => 1,
    ]);

    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('deleteSession')
        ->assertRedirect(route('dashboard.workout'));

    expect(Workout::find($workout->id))->toBeNull();
    expect(RunningRoute::find($route->id))->toBeNull();
    expect(WaterStation::find($station->id))->toBeNull();
});

test('can add a route to a running session', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('openRouteModal')
        ->assertSet('editingRouteId', null)
        ->set('routeName', 'Park Loop 5K')
        ->set('routePlannedDistance', 5.0)
        ->set('routeOrder', 1)
        ->set('routeTerrainType', 'Trail')
        ->set('routeElevationGain', 45)
        ->set('routeDescription', 'Run clockwise around the lake.')
        ->call('saveRoute')
        ->assertDispatched('notify');

    $route = RunningRoute::where('workout_id', $workout->id)->first();
    expect($route)->not->toBeNull();
    expect($route->name)->toBe('Park Loop 5K');
    expect($route->planned_distance_km)->toBe(5.0);
    expect($route->terrain_type)->toBe('Trail');
    expect($route->elevation_gain_m)->toBe(45);
    expect($route->user_id)->toBe($this->user->id);
});

test('can edit an existing route without creating duplicates', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    $route = RunningRoute::create([
        'workout_id' => $workout->id,
        'user_id' => $this->user->id,
        'name' => 'Original Name',
        'planned_distance_km' => 4.0,
        'order' => 1,
    ]);

    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('openRouteModal', $route->id)
        ->assertSet('editingRouteId', $route->id)
        ->assertSet('routeName', 'Original Name')
        ->set('routeName', 'Renamed Loop')
        ->set('routePlannedDistance', 4.5)
        ->call('saveRoute')
        ->assertDispatched('notify');

    expect(RunningRoute::where('workout_id', $workout->id)->count())->toBe(1);
    $route->refresh();
    expect($route->name)->toBe('Renamed Loop');
    expect($route->planned_distance_km)->toBe(4.5);
});

test('route validation enforces name and positive distance', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('openRouteModal')
        ->set('routeName', '')
        ->set('routePlannedDistance', -5)
        ->call('saveRoute')
        ->assertHasErrors(['routeName', 'routePlannedDistance']);
});

test('can reorder routes up and down', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    $route1 = RunningRoute::create([
        'workout_id' => $workout->id,
        'user_id' => $this->user->id,
        'name' => 'First Route',
        'planned_distance_km' => 3.0,
        'order' => 1,
    ]);

    $route2 = RunningRoute::create([
        'workout_id' => $workout->id,
        'user_id' => $this->user->id,
        'name' => 'Second Route',
        'planned_distance_km' => 4.0,
        'order' => 2,
    ]);

    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('moveRoute', $route2->id, 'up');

    $route1->refresh();
    $route2->refresh();

    expect($route2->order)->toBe(1);
    expect($route1->order)->toBe(2);
});

test('can add and edit water station on a route', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    $route = RunningRoute::create([
        'workout_id' => $workout->id,
        'user_id' => $this->user->id,
        'name' => 'Main Loop',
        'planned_distance_km' => 5.0,
        'order' => 1,
    ]);

    // Create Water Station
    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('openStationModal', $route->id)
        ->set('stationName', 'Central Park Fountain')
        ->set('stationType', 'water_fountain')
        ->set('stationLatitude', -6.20876)
        ->set('stationLongitude', 106.84559)
        ->set('stationNotes', 'Cold water available 24/7')
        ->set('stationOrder', 1)
        ->call('saveStation')
        ->assertDispatched('notify');

    $station = WaterStation::where('running_route_id', $route->id)->first();
    expect($station)->not->toBeNull();
    expect($station->name)->toBe('Central Park Fountain');
    expect($station->type)->toBe('water_fountain');
    expect($station->latitude)->toBe(-6.20876);
    expect($station->longitude)->toBe(106.84559);
    expect($station->user_id)->toBe($this->user->id);

    // Edit Water Station
    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('openStationModal', $route->id, $station->id)
        ->set('stationName', 'Updated Fountain Name')
        ->call('saveStation')
        ->assertDispatched('notify');

    $station->refresh();
    expect($station->name)->toBe('Updated Fountain Name');
});

test('water station coordinates are validated', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    $route = RunningRoute::create([
        'workout_id' => $workout->id,
        'user_id' => $this->user->id,
        'name' => 'Main Loop',
        'planned_distance_km' => 5.0,
        'order' => 1,
    ]);

    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('openStationModal', $route->id)
        ->set('stationName', 'Bad Station')
        ->set('stationLatitude', 95.0) // Invalid: must be <= 90
        ->set('stationLongitude', -195.0) // Invalid: must be >= -180
        ->call('saveStation')
        ->assertHasErrors(['stationLatitude', 'stationLongitude']);
});

test('user cannot access or modify another users running route or station', function () {
    $otherWorkout = Workout::factory()->create([
        'user_id' => $this->otherUser->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    $otherRoute = RunningRoute::create([
        'workout_id' => $otherWorkout->id,
        'user_id' => $this->otherUser->id,
        'name' => 'Other User Route',
        'planned_distance_km' => 6.0,
        'order' => 1,
    ]);

    $otherStation = WaterStation::create([
        'running_route_id' => $otherRoute->id,
        'user_id' => $this->otherUser->id,
        'name' => 'Other User Station',
        'type' => 'water_fountain',
        'latitude' => 0.0,
        'longitude' => 0.0,
        'order' => 1,
    ]);

    // Attempt to edit other user's route
    expect(function () use ($otherRoute) {
        Livewire::actingAs($this->user)
            ->test(RunningSession::class, ['date' => '2026-10-22'])
            ->call('openRouteModal', $otherRoute->id);
    })->toThrow(ModelNotFoundException::class);

    // Attempt to add station to other user's route
    expect(function () use ($otherRoute) {
        Livewire::actingAs($this->user)
            ->test(RunningSession::class, ['date' => '2026-10-22'])
            ->call('openStationModal', $otherRoute->id);
    })->toThrow(ModelNotFoundException::class);

    // Attempt to delete other user's station
    expect(function () use ($otherStation) {
        Livewire::actingAs($this->user)
            ->test(RunningSession::class, ['date' => '2026-10-22'])
            ->call('deleteStation', $otherStation->id);
    })->toThrow(ModelNotFoundException::class);
});

test('saved running session immediately reflects on the workout calendar', function () {
    Carbon::setTestNow('2026-10-22');

    // Create session via Livewire
    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->set('title', 'Marathon Prep Run')
        ->set('status', 'completed')
        ->set('actual_distance_km', 21.1)
        ->set('duration_minutes', 115)
        ->call('saveSession');

    // Calendar should display the session
    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->assertSee('Marathon Prep Run')
        ->assertSee('21.1 km');

    Carbon::setTestNow();
});

test('can save route with leaflet map path coordinates', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    $pathCoordinates = [
        [-6.2088, 106.8456],
        [-6.2095, 106.8462],
        [-6.2110, 106.8475],
    ];

    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('openRouteModal')
        ->set('routeName', 'Leaflet Drawn Route')
        ->set('routePlannedDistance', 3.25)
        ->set('routePathCoordinates', json_encode($pathCoordinates))
        ->call('saveRoute')
        ->assertDispatched('notify');

    $route = RunningRoute::where('workout_id', $workout->id)->first();
    expect($route)->not->toBeNull();
    expect($route->name)->toBe('Leaflet Drawn Route');
    expect($route->path_coordinates)->toBe($pathCoordinates);
    expect($route->planned_distance_km)->toBe(3.25);
});

test('can open water station modal with coordinates from leaflet map click', function () {
    $workout = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-22',
        'type' => 'running',
    ]);

    $route = RunningRoute::create([
        'workout_id' => $workout->id,
        'user_id' => $this->user->id,
        'name' => 'Route on Map',
        'planned_distance_km' => 5.0,
        'order' => 1,
    ]);

    Livewire::actingAs($this->user)
        ->test(RunningSession::class, ['date' => '2026-10-22'])
        ->call('openStationModal', $route->id, null, -6.2087634, 106.8455990)
        ->assertSet('stationLatitude', -6.2087634)
        ->assertSet('stationLongitude', 106.8455990)
        ->set('stationName', 'Hydration Point Picked From Map')
        ->call('saveStation')
        ->assertDispatched('notify');

    $station = WaterStation::where('running_route_id', $route->id)->first();
    expect($station)->not->toBeNull();
    expect($station->latitude)->toBe(-6.2087634);
    expect($station->longitude)->toBe(106.8455990);
    expect($station->name)->toBe('Hydration Point Picked From Map');
});
