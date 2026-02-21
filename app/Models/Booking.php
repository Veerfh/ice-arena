<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
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
        'is_paid' => 'boolean'
    ];

    public function skate(): BelongsTo
    {
        return $this->belongsTo(Skate::class);
    }

    public function calculateTotal(): int
    {
        $total = 300; // Входной билет
        
        if ($this->skate_id) {
            $total += 150 * $this->hours;
        }
        
        return $total;
    }
}