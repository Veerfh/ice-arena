<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'is_paid',
        'payment_id'
    ];

    protected $casts = [
        'is_paid' => 'boolean'
    ];
}