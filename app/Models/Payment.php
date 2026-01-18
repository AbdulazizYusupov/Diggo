<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'school_id',
        'user_id',
        'amount',
        'discount',
        'net_amount',
        'director_commission',
        'director_id',
        'payment_type',
        'status',
        'payment_month',
        'is_installment',
        'installment_number',
        'total_installments',
        'notes',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'director_commission' => 'decimal:2',
        'is_installment' => 'boolean',
        'payment_month' => 'date',
        'paid_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function director()
    {
        return $this->belongsTo(Director::class);
    }
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
    public function commission()
    {
        return $this->hasOne(DirectorCommission::class);
    }
}
