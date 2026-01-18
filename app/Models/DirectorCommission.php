<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'director_id',
        'payment_id',
        'school_id',
        'amount',
        'status',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function director()
    {
        return $this->belongsTo(Director::class);
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
