<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'school_id',
        'director_id',
        'enrollment_date',
        'coins',
        'balance',
        'parent_name',
        'parent_phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes'
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'coins' => 'integer',
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function director()
    {
        return $this->belongsTo(Director::class);
    }

    public function groups()
    {
        return $this->belongsToMany(
            Group::class,
            'group_students',
            'student_id',
            'group_id'
        );
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function lessonCharges()
    {
        return $this->hasMany(LessonCharge::class);
    }
    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }
}
