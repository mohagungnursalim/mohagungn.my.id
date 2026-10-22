<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'type',
        'title',
        'status',
        'planned_distance_km',
        'distance_km',
        'duration_minutes',
        'pace',
        'exercises_count',
        'sets_completed',
        'total_sets',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'completed_at' => 'datetime',
            'planned_distance_km' => 'float',
            'distance_km' => 'float',
            'duration_minutes' => 'integer',
            'exercises_count' => 'integer',
            'sets_completed' => 'integer',
            'total_sets' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function routes(): HasMany
    {
        return $this->hasMany(RunningRoute::class, 'workout_id')->orderBy('order');
    }

    public function isRunning(): bool
    {
        return $this->type === 'running';
    }

    public function isStrength(): bool
    {
        return $this->type === 'strength';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPlanned(): bool
    {
        return $this->status === 'planned';
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->status === 'completed') {
            return 100;
        }

        if ($this->isStrength() && $this->total_sets > 0) {
            return (int) min(100, round(($this->sets_completed / $this->total_sets) * 100));
        }

        return 0;
    }

    public function getProgressSummaryAttribute(): string
    {
        if ($this->isRunning()) {
            if ($this->isCompleted()) {
                $parts = [];
                if ($this->distance_km) {
                    $parts[] = rtrim(rtrim(number_format($this->distance_km, 2), '0'), '.').' km';
                }
                if ($this->duration_minutes) {
                    $parts[] = "{$this->duration_minutes}m";
                }
                if ($this->pace) {
                    $parts[] = "{$this->pace}/km";
                }

                return count($parts) ? implode(' · ', $parts) : 'Completed';
            }

            // Planned running session
            if ($this->planned_distance_km) {
                return rtrim(rtrim(number_format($this->planned_distance_km, 2), '0'), '.').' km target';
            }

            if ($this->distance_km) {
                return rtrim(rtrim(number_format($this->distance_km, 2), '0'), '.').' km';
            }

            return 'Planned';
        }

        if ($this->isStrength()) {
            if ($this->total_sets > 0) {
                return "{$this->sets_completed}/{$this->total_sets} sets";
            }
            if ($this->exercises_count > 0) {
                return "{$this->exercises_count} exercises";
            }

            return $this->isCompleted() ? 'Completed' : 'Planned';
        }

        return ucfirst($this->status);
    }
}
