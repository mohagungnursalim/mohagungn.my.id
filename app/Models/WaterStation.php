<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'running_route_id',
        'user_id',
        'name',
        'type',
        'latitude',
        'longitude',
        'notes',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'order' => 'integer',
        ];
    }

    public function runningRoute(): BelongsTo
    {
        return $this->belongsTo(RunningRoute::class, 'running_route_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'water_fountain' => 'Water Fountain',
            'convenience_store' => 'Convenience Store',
            'vending_machine' => 'Vending Machine',
            'aid_station' => 'Aid Station',
            'hydration_point' => 'Hydration Point',
            default => ucfirst(str_replace('_', ' ', $this->type ?? 'Water Station')),
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'water_fountain' => 'fa-faucet-drip',
            'convenience_store' => 'fa-store',
            'vending_machine' => 'fa-bottle-water',
            'aid_station' => 'fa-kit-medical',
            default => 'fa-droplet',
        };
    }
}

