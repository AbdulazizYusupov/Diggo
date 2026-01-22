<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'amount',
        'type',
        'description',
        'transactionable_type',
        'transactionable_id',
        'balance_after',
        'created_by',
        'transaction_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function transactionable()
    {
        return $this->morphTo();
    }
}
