<?php

use App\Livewire\Dashboard\WorkoutPlanning;
use App\Models\User;
use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('guest cannot access workout planning', function () {
    $this->get(route('dashboard.workout'))
        ->assertRedirect(route('login'));
});

test('workout planning page renders successfully for authenticated user', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.workout'))
        ->assertOk()
        ->assertSee('Workout Planning');
});

test('displays the current month by default', function () {
    Carbon::setTestNow('2026-10-22');

    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->assertSet('year', 2026)
        ->assertSet('month', 10)
        ->assertSet('selectedDate', '2026-10-22')
        ->assertSee('October');

    Carbon::setTestNow();
});

test('supports month navigation to previous and next month', function () {
    Carbon::setTestNow('2026-10-22');

    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->call('previousMonth')
        ->assertSet('month', 9)
        ->assertSet('year', 2026)
        ->assertSee('September')
        ->call('nextMonth')
        ->assertSet('month', 10)
        ->assertSet('year', 2026)
        ->assertSee('October')
        ->call('nextMonth')
        ->assertSet('month', 11)
        ->assertSet('year', 2026)
        ->assertSee('November');

    Carbon::setTestNow();
});

test('supports previous month wrapping across year boundary', function () {
    Carbon::setTestNow('2026-01-15');

    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->call('previousMonth')
        ->assertSet('month', 12)
        ->assertSet('year', 2025)
        ->call('nextMonth')
        ->assertSet('month', 1)
        ->assertSet('year', 2026);

    Carbon::setTestNow();
});

test('supports direct month and year selection and preserves selection', function () {
    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->set('month', 5)
        ->set('year', 2027)
        ->assertSet('month', 5)
        ->assertSet('year', 2027)
        ->assertSee('May');
});

test('goToToday resets month and year to current date', function () {
    Carbon::setTestNow('2026-10-22');

    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->set('month', 2)
        ->set('year', 2025)
        ->call('goToToday')
        ->assertSet('month', 10)
        ->assertSet('year', 2026)
        ->assertSet('selectedDate', '2026-10-22');

    Carbon::setTestNow();
});

test('highlights today and selected date independently', function () {
    Carbon::setTestNow('2026-10-22');

    $component = Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->call('selectDate', '2026-10-15')
        ->assertSet('selectedDate', '2026-10-15')
        ->assertDispatched('open-date-modal');

    // The calendar days should flag 2026-10-22 as isToday and 2026-10-15 as isSelected
    $days = $component->viewData('calendarDays');
    $day15 = collect($days)->firstWhere('date', '2026-10-15');
    $day22 = collect($days)->firstWhere('date', '2026-10-22');

    expect($day15['isSelected'])->toBeTrue();
    expect($day15['isToday'])->toBeFalse();
    expect($day22['isToday'])->toBeTrue();
    expect($day22['isSelected'])->toBeFalse();

    Carbon::setTestNow();
});

test('displays persisted running activity indicators with real progress data', function () {
    Carbon::setTestNow('2026-10-22');

    Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-10',
        'type' => 'running',
        'title' => 'Morning 5K Tempo',
        'status' => 'completed',
        'distance_km' => 5.25,
        'duration_minutes' => 28,
        'pace' => '5:20',
    ]);

    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->assertSee('Morning 5K Tempo')
        ->assertSee('5.25 km')
        ->assertSee('28m');

    Carbon::setTestNow();
});

test('displays persisted strength workout indicators with sets and progress', function () {
    Carbon::setTestNow('2026-10-22');

    Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-12',
        'type' => 'strength',
        'title' => 'Chest & Triceps Hypertrophy',
        'status' => 'completed',
        'exercises_count' => 4,
        'sets_completed' => 12,
        'total_sets' => 12,
    ]);

    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->assertSee('Chest & Triceps Hypertrophy')
        ->assertSee('12/12 sets');

    Carbon::setTestNow();
});

test('handles dates with multiple activities on the same day', function () {
    Carbon::setTestNow('2026-10-22');

    $run = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-18',
        'type' => 'running',
        'title' => 'Morning Trail Run',
        'status' => 'completed',
        'distance_km' => 8.0,
    ]);

    $strength = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-18',
        'type' => 'strength',
        'title' => 'Evening Core Workout',
        'status' => 'planned',
        'total_sets' => 8,
    ]);

    $component = Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->assertSee('Morning Trail Run')
        ->assertSee('Evening Core Workout');

    $days = $component->viewData('calendarDays');
    $day18 = collect($days)->firstWhere('date', '2026-10-18');

    expect($day18['workouts']->count())->toBe(2);
    expect($day18['hasRunning'])->toBeTrue();
    expect($day18['hasStrength'])->toBeTrue();

    Carbon::setTestNow();
});

test('modal allows inspecting existing activities without duplicating', function () {
    Carbon::setTestNow('2026-10-22');

    $run = Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-14',
        'type' => 'running',
        'title' => 'Threshold Run',
        'status' => 'completed',
        'distance_km' => 6.0,
    ]);

    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->call('selectDate', '2026-10-14')
        ->assertDispatched('open-date-modal')
        ->assertSee('Threshold Run')
        ->assertSee('Open');

    Carbon::setTestNow();
});

test('empty dates show prompt to schedule running or strength workout', function () {
    Carbon::setTestNow('2026-10-22');

    Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->call('selectDate', '2026-10-25')
        ->assertDispatched('open-date-modal')
        ->assertSee('No activity planned for this date')
        ->assertSee('Running')
        ->assertSee('Strength Workout');

    Carbon::setTestNow();
});

test('queries only the selected month and calendar overflow days and not the entire history', function () {
    Carbon::setTestNow('2026-10-22');

    // Create workout in current month
    Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-10-15',
        'title' => 'October Session',
    ]);

    // Create workout 6 months ago
    Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2026-04-15',
        'title' => 'April Old Session',
    ]);

    // Create workout next year
    Workout::factory()->create([
        'user_id' => $this->user->id,
        'date' => '2027-02-15',
        'title' => 'Future 2027 Session',
    ]);

    // Listen to executed queries
    DB::enableQueryLog();

    $component = Livewire::actingAs($this->user)
        ->test(WorkoutPlanning::class)
        ->assertSee('October Session')
        ->assertDontSee('April Old Session')
        ->assertDontSee('Future 2027 Session');

    $queryLog = DB::getQueryLog();
    DB::disableQueryLog();

    // Verify workout query has whereBetween condition limiting the range
    $workoutQuery = collect($queryLog)->first(function ($entry) {
        return str_contains($entry['query'], 'workouts') && str_contains($entry['query'], 'between');
    });

    expect($workoutQuery)->not->toBeNull();
    // Verify there are no repeated N+1 queries in loops
    $workoutQueriesCount = collect($queryLog)->filter(function ($entry) {
        return str_contains($entry['query'], 'workouts');
    })->count();

    expect($workoutQueriesCount)->toBe(1);

    Carbon::setTestNow();
});

test('target date routes render correctly for running and strength', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.workout.running', ['date' => '2026-10-25']))
        ->assertOk()
        ->assertSee('Running')
        ->assertSee('2026-10-25');

    $this->actingAs($this->user)
        ->get(route('dashboard.workout.strength', ['date' => '2026-10-25']))
        ->assertOk()
        ->assertSee('Strength Workout')
        ->assertSee('2026-10-25');
});
