<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'group_id',
        'school_id',
        'user_id',
        'date',
        'status',
        'notes'
    ];

    protected $casts = ['date' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function markedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
