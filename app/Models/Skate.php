<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skate extends Model
{
    protected $fillable = [
        'model',
        'brand',
        'size',
        'quantity',
        'image'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }
}