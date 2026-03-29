<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeLog extends Model
{
    protected $fillable = [
        'charge_month',
        'charged_count',
        'skipped_count',
        'no_payment_count',
        'error_count',
        'total_amount',
        'details',
        'created_by',
        'run_at',
    ];

    protected $casts = [
        'charge_month' => 'date',
        'details'      => 'array',
        'run_at'       => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Badge color helper
    public function getStatusColorAttribute(): string
    {
        if ($this->error_count > 0)      return 'red';
        if ($this->no_payment_count > 0) return 'yellow';
        return 'green';
    }
}
