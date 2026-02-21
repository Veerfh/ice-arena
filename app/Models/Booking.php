<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Booking extends Model
{
    protected $table = 'bookings';
    
    protected $fillable = [
        'full_name',
        'phone',
        'hours',
        'skate_id',
        'skate_size',
        'total_amount',
        'is_paid',
        'payment_id',
        'payment_status'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'hours' => 'integer',
        'skate_size' => 'integer',
        'total_amount' => 'integer'
    ];

    public function skate(): BelongsTo
    {
        return $this->belongsTo(Skate::class);
    }

    public function access(): MorphOne
    {
        return $this->morphOne(Access::class, 'accessible');
    }

    public function calculateTotal(): int
    {
        $total = 300; // Входной билет
        
        if ($this->skate_id) {
            $total += 150 * $this->hours;
        }
        
        return $total;
    }

    public function createAccess(): ?Access
    {
        if (!$this->is_paid) {
            return null;
        }

        return $this->access()->create([
            'access_code' => Access::generateCode(),
            'valid_from' => now(),
            'valid_until' => now()->addHours($this->hours),
            'is_used' => false
        ]);
    }
}