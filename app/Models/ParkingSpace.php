<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingSpace extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_available',
        'zone'
    ];

    protected $casts = [
        'is_available' => 'boolean'
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(VehicleEntry::class);
    }

    public function getCurrentEntry()
    {
        return $this->entries()->whereNull('exit_time')->first();
    }
}