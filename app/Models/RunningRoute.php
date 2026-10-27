<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RunningRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_id',
        'user_id',
        'name',
        'description',
        'planned_distance_km',
        'path_coordinates',
        'order',
        'terrain_type',
        'elevation_gain_m',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'planned_distance_km' => 'float',
            'path_coordinates' => 'array',
            'order' => 'integer',
            'elevation_gain_m' => 'integer',
        ];
    }

    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function waterStations(): HasMany
    {
        return $this->hasMany(WaterStation::class, 'running_route_id')->orderBy('order');
    }
}
