<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WorkoutSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $now = Carbon::today();
        $year = $now->year;
        $month = $now->month;

        foreach ($users as $user) {
            // Day 2: Completed Morning Run
            Workout::firstOrCreate([
                'user_id' => $user->id,
                'date' => Carbon::createFromDate($year, $month, 2)->format('Y-m-d'),
                'type' => 'running',
            ], [
                'title' => 'Morning Easy 5K',
                'status' => 'completed',
                'distance_km' => 5.2,
                'duration_minutes' => 28,
                'pace' => '5:23',
                'completed_at' => Carbon::createFromDate($year, $month, 2)->setTime(7, 30),
                'notes' => 'Felt good and easy breathing throughout.',
            ]);

            // Day 4: Completed Strength Workout
            Workout::firstOrCreate([
                'user_id' => $user->id,
                'date' => Carbon::createFromDate($year, $month, 4)->format('Y-m-d'),
                'type' => 'strength',
            ], [
                'title' => 'Upper Body Hypertrophy',
                'status' => 'completed',
                'exercises_count' => 5,
                'total_sets' => 15,
                'sets_completed' => 15,
                'completed_at' => Carbon::createFromDate($year, $month, 4)->setTime(18, 0),
                'notes' => 'Bench press, bent over rows, overhead press, bicep curls, tricep dips.',
            ]);

            // Day 8: Completed Long Run
            Workout::firstOrCreate([
                'user_id' => $user->id,
                'date' => Carbon::createFromDate($year, $month, 8)->format('Y-m-d'),
                'type' => 'running',
            ], [
                'title' => 'Sunday Long Run 10K',
                'status' => 'completed',
                'distance_km' => 10.5,
                'duration_minutes' => 58,
                'pace' => '5:31',
                'completed_at' => Carbon::createFromDate($year, $month, 8)->setTime(6, 45),
            ]);

            // Day 12: Dual activity date (both running and strength on same date)
            Workout::firstOrCreate([
                'user_id' => $user->id,
                'date' => Carbon::createFromDate($year, $month, 12)->format('Y-m-d'),
                'type' => 'running',
            ], [
                'title' => 'Morning Interval Sprints',
                'status' => 'completed',
                'distance_km' => 4.0,
                'duration_minutes' => 22,
                'pace' => '5:30',
                'completed_at' => Carbon::createFromDate($year, $month, 12)->setTime(7, 0),
            ]);

            Workout::firstOrCreate([
                'user_id' => $user->id,
                'date' => Carbon::createFromDate($year, $month, 12)->format('Y-m-d'),
                'type' => 'strength',
            ], [
                'title' => 'Leg Day & Calves',
                'status' => 'completed',
                'exercises_count' => 4,
                'total_sets' => 12,
                'sets_completed' => 12,
                'completed_at' => Carbon::createFromDate($year, $month, 12)->setTime(17, 30),
            ]);

            // Day 16: Planned Strength Workout
            Workout::firstOrCreate([
                'user_id' => $user->id,
                'date' => Carbon::createFromDate($year, $month, 16)->format('Y-m-d'),
                'type' => 'strength',
            ], [
                'title' => 'Full Body Conditioning',
                'status' => 'planned',
                'exercises_count' => 4,
                'total_sets' => 12,
                'sets_completed' => 0,
                'notes' => 'Focus on core and stability.',
            ]);

            // Day 20: Planned Tempo Run
            Workout::firstOrCreate([
                'user_id' => $user->id,
                'date' => Carbon::createFromDate($year, $month, 20)->format('Y-m-d'),
                'type' => 'running',
            ], [
                'title' => 'Threshold Tempo Run',
                'status' => 'planned',
                'distance_km' => 7.0,
                'notes' => 'Target pace: 5:10/km.',
            ]);

            // Day 24: Completed Strength Workout
            Workout::firstOrCreate([
                'user_id' => $user->id,
                'date' => Carbon::createFromDate($year, $month, 24)->format('Y-m-d'),
                'type' => 'strength',
            ], [
                'title' => 'Push / Pull Session',
                'status' => 'completed',
                'exercises_count' => 6,
                'total_sets' => 18,
                'sets_completed' => 18,
                'completed_at' => Carbon::createFromDate($year, $month, 24)->setTime(18, 30),
            ]);
        }
    }
}

