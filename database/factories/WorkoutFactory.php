<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkoutFactory extends Factory
{
    protected $model = Workout::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['running', 'strength']);
        $status = fake()->randomElement(['planned', 'completed']);

        $data = [
            'user_id' => User::factory(),
            'date' => fake()->date(),
            'type' => $type,
            'title' => $type === 'running' ? fake()->randomElement(['Morning Easy Run', 'Interval Training', 'Long Run 10K', 'Tempo Run']) : fake()->randomElement(['Upper Body Push', 'Lower Body Squats', 'Full Body Hypertrophy', 'Core & Stability']),
            'status' => $status,
            'notes' => fake()->optional()->sentence(),
            'completed_at' => $status === 'completed' ? now() : null,
        ];

        if ($type === 'running') {
            $distance = fake()->randomFloat(2, 3, 15);
            $data['distance_km'] = $distance;
            $data['duration_minutes'] = $status === 'completed' ? (int) round($distance * fake()->numberBetween(5, 7)) : null;
            $data['pace'] = $status === 'completed' ? fake()->randomElement(['5:10', '5:35', '6:02', '4:45']) : null;
        } else {
            $exercises = fake()->numberBetween(3, 6);
            $sets = $exercises * fake()->numberBetween(3, 4);
            $data['exercises_count'] = $exercises;
            $data['total_sets'] = $sets;
            $data['sets_completed'] = $status === 'completed' ? $sets : fake()->numberBetween(0, $sets - 1);
        }

        return $data;
    }

    public function running(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'running',
            'title' => 'Outdoor Run',
            'distance_km' => 5.0,
            'duration_minutes' => 30,
            'pace' => '6:00',
        ]);
    }

    public function strength(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'strength',
            'title' => 'Strength Training',
            'exercises_count' => 4,
            'total_sets' => 12,
            'sets_completed' => 12,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function planned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'planned',
            'completed_at' => null,
            'sets_completed' => 0,
        ]);
    }
}

