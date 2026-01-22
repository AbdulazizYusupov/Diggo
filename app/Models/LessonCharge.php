<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'group_id',
        'amount',
        'charge_month',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'charge_month' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
