<?php

namespace App\Livewire\Dashboard;

use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Workout Planning')]
class WorkoutPlanning extends Component
{
    public int $year;

    public int $month;

    public ?string $selectedDate = null;

    /**
     * Keep track of query string for deep-linking and bookmarking months.
     */
    protected $queryString = [
        'year' => ['except' => ''],
        'month' => ['except' => ''],
    ];

    public function mount(): void
    {
        $today = Carbon::today();

        $requestedYear = request()->query('year');
        $requestedMonth = request()->query('month');

        $this->year = is_numeric($requestedYear) && $requestedYear >= 2000 && $requestedYear <= 2100
            ? (int) $requestedYear
            : (int) $today->year;

        $this->month = is_numeric($requestedMonth) && $requestedMonth >= 1 && $requestedMonth <= 12
            ? (int) $requestedMonth
            : (int) $today->month;

        $this->selectedDate = $today->format('Y-m-d');
    }

    public function previousMonth(): void
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->subMonth();
        $this->year = (int) $date->year;
        $this->month = (int) $date->month;
    }

    public function nextMonth(): void
    {
        $date = Carbon::createFromDate($this->year, $this->month, 1)->addMonth();
        $this->year = (int) $date->year;
        $this->month = (int) $date->month;
    }

    public function goToToday(): void
    {
        $today = Carbon::today();
        $this->year = (int) $today->year;
        $this->month = (int) $today->month;
        $this->selectedDate = $today->format('Y-m-d');
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->dispatch('open-date-modal');
    }

    public function updatedYear(): void
    {
        $this->year = max(2000, min(2100, (int) $this->year));
    }

    public function updatedMonth(): void
    {
        $this->month = max(1, min(12, (int) $this->month));
    }

    public function render()
    {
        $startOfMonth = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // 7-column Monday-to-Sunday ISO week layout
        // Monday = 1, Sunday = 7
        $startDayOfWeek = $startOfMonth->dayOfWeekIso; // 1 to 7
        $leadingDaysCount = $startDayOfWeek - 1;
        $calendarStart = $startOfMonth->copy()->subDays($leadingDaysCount);

        $endDayOfWeek = $endOfMonth->dayOfWeekIso; // 1 to 7
        $trailingDaysCount = 7 - $endDayOfWeek;
        $calendarEnd = $endOfMonth->copy()->addDays($trailingDaysCount);

        // Targeted query: only activities within this calendar grid window for the authenticated user
        $userId = Auth::id();
        $workouts = Workout::query()
            ->where('user_id', $userId)
            ->whereBetween('date', [$calendarStart->toDateString(), $calendarEnd->toDateString()])
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        // Group by Y-m-d to prevent any N+1 queries in template
        $workoutsByDate = $workouts->groupBy(fn ($w) => $w->date->format('Y-m-d'));

        // Build calendar days array
        $calendarDays = [];
        $cursor = $calendarStart->copy();
        $todayStr = Carbon::today()->format('Y-m-d');

        while ($cursor->lte($calendarEnd)) {
            $dateStr = $cursor->format('Y-m-d');
            /** @var Collection $dayWorkouts */
            $dayWorkouts = $workoutsByDate->get($dateStr, collect());

            $calendarDays[] = [
                'date' => $dateStr,
                'day' => (int) $cursor->format('j'),
                'isCurrentMonth' => $cursor->month === $this->month,
                'isToday' => $dateStr === $todayStr,
                'isSelected' => $dateStr === $this->selectedDate,
                'workouts' => $dayWorkouts,
                'runningCount' => $dayWorkouts->where('type', 'running')->count(),
                'strengthCount' => $dayWorkouts->where('type', 'strength')->count(),
                'hasRunning' => $dayWorkouts->contains(fn ($w) => $w->type === 'running'),
                'hasStrength' => $dayWorkouts->contains(fn ($w) => $w->type === 'strength'),
                'hasCompleted' => $dayWorkouts->contains(fn ($w) => $w->status === 'completed'),
                'hasPlanned' => $dayWorkouts->contains(fn ($w) => $w->status === 'planned'),
            ];

            $cursor->addDay();
        }

        // Summary metrics for the active month (in-memory aggregation, 0 extra queries)
        $currentMonthWorkouts = $workouts->filter(fn ($w) => $w->date->month === $this->month && $w->date->year === $this->year);
        $monthSummary = [
            'total' => $currentMonthWorkouts->count(),
            'completed' => $currentMonthWorkouts->where('status', 'completed')->count(),
            'planned' => $currentMonthWorkouts->where('status', 'planned')->count(),
            'running_km' => (float) $currentMonthWorkouts->where('type', 'running')->where('status', 'completed')->sum('distance_km'),
            'strength_sessions' => $currentMonthWorkouts->where('type', 'strength')->count(),
        ];

        // Selected date activities for inspection modal
        $selectedActivities = $this->selectedDate ? ($workoutsByDate->get($this->selectedDate, collect())) : collect();

        return view('livewire.dashboard.workout-planning', [
            'calendarDays' => $calendarDays,
            'currentMonthDate' => $startOfMonth,
            'monthSummary' => $monthSummary,
            'selectedActivities' => $selectedActivities,
            'selectedDateFormatted' => $this->selectedDate ? Carbon::parse($this->selectedDate)->translatedFormat('l, d F Y') : '',
            'years' => range(Carbon::today()->year - 3, Carbon::today()->year + 3),
            'months' => [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
            ],
            'weekDays' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        ])->layout('layouts.dashboard.main');
    }
}
